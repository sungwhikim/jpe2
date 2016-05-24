<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Bin;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Enum\InventoryLogType;
use DB;
use Log;

class InventoryController extends Controller
{
    protected $my_name = 'inventory';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex()
    {
        //get the list data with the default sort set the same as in the angular table
        $product = new Product();
        $product_data = $product->getUserProductList();

        //we need to send the url to do Ajax queries back here
        $url = url('/inventory');

        //set params
        $params = ['main_data' => collect([]),
                   'url' => $url,
                   'my_name' => $this->my_name,
                   'product_data' => $product_data];

        return view('pages.inventory', $params);
    }

    public function getProductList()
    {
        $product = new Product();
        return $product->getUserProductList();
    }

    public function getProductInventory($product_id)
    {
        //first grab the bins and the rollup total in each and the product type data to get the variants
        $bins = Inventory::select('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position', 'bin.active', 'bin.default',
                                  DB::raw('SUM(inventory.quantity) as total'),
                                  'product_type.variant1', 'product_type.variant2', 'product_type.variant3', 'product_type.variant4')
                           ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                           ->join('product', 'bin.product_id', '=', 'product.id')
                           ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                           ->where('bin.product_id', '=', $product_id)
                           ->groupBy('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position', 'bin.active',
                                     'product_type.variant1', 'product_type.variant2', 'product_type.variant3', 'product_type.variant4')
                           ->orderBy('bin.aisle')->orderBy('bin.section')->orderBy('bin.tier')->orderBy('bin.position')
                           ->get();

        //now add the variants and items from each receive date
        foreach( $bins as $bin )
        {
            $bin_data = Inventory::select('inventory.*', 'bin.default',
                                          'product_variant1.value as variant1_value',
                                          'product_variant2.value as variant2_value',
                                          'product_variant3.value as variant3_value',
                                          'product_variant4.value as variant4_value')
                                 ->join('bin', 'inventory.bin_id', '=', 'bin.id')
                                 ->leftJoin('product_variant1', 'inventory.variant1_id', '=', 'product_variant1.id')
                                 ->leftJoin('product_variant2', 'inventory.variant2_id', '=', 'product_variant2.id')
                                 ->leftJoin('product_variant3', 'inventory.variant3_id', '=', 'product_variant3.id')
                                 ->leftJoin('product_variant4', 'inventory.variant4_id', '=', 'product_variant4.id')
                                 ->where('inventory.bin_id', '=', $bin->id)
                                 ->orderBy('product_variant1.value')
                                 ->orderBy('product_variant2.value')
                                 ->orderBy('product_variant3.value')
                                 ->orderBy('product_variant4.value')
                                 ->orderBy('inventory.receive_date')
                                 ->orderBy('inventory.quantity')
                                 ->get();

            //$bin_data_sorted = $bin_data->sortBy('receive_date');
            $bin->bin_items = $bin_data;
        }

        return $bins;
    }

    public function postSave($product_id)
    {
        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            /* loop through and update data */
            foreach ( collect(request()->all()) as $bin ) {
                /* NEED TO ADD CHECK FOR NON-ZERO BIN ITEMS AND NOT ALLOW INACTIVATION IF ANY QUANTITIES EXIST */
                //update active flag.
                $bin_model = Bin::find($bin['id']);
                $bin_model->active = $bin['active'];
                $bin_model->default = $bin['default'];
                $bin_model->save();

                //go through each bin item if set
                if ( isset($bin['bin_items']) ) {
                    foreach ( $bin['bin_items'] as $bin_item ) {
                        //only update if new quantity is set
                        if ( isset($bin_item['quantity_new']) && is_numeric($bin_item['quantity_new']) ) {
                            //update the main inventory table
                            $this->updateInventoryItem($product_id, $bin_item);
                        }
                    }
                }

                //see if any new bin item need to be added
                if ( isset($bin['new_bin_items']) ) {
                    //go through each new bin item
                    foreach ( $bin['new_bin_items'] as $new_bin_item ) {
                        //only update if new quantity is set and greater than 0.  Since it is a new item, we can enforce the greater than 0 requirement.
                        if ( isset($new_bin_item['quantity_new']) && is_numeric($new_bin_item['quantity_new']) && $new_bin_item['quantity_new'] > 0 ) {
                            //get variants
                            $new_bin_item['variant1_id'] = $this->getVariantId($product_id, $new_bin_item['variant1_value'], $bin['variant1'], 'variant1');
                            $new_bin_item['variant2_id'] = $this->getVariantId($product_id, $new_bin_item['variant2_value'], $bin['variant2'], 'variant2');
                            $new_bin_item['variant3_id'] = $this->getVariantId($product_id, $new_bin_item['variant3_value'], $bin['variant3'], 'variant3');
                            $new_bin_item['variant4_id'] = $this->getVariantId($product_id, $new_bin_item['variant4_value'], $bin['variant4'], 'variant4');

                            //check if this item exists
                            $result = Inventory::select('id', 'bin_id', 'quantity')
                                                 ->where('bin_id', '=', $bin['id'])
                                                 ->where('receive_date', '=', $new_bin_item['receive_date'])
                                                 ->where('variant1_id', '=', $new_bin_item['variant1_id'])
                                                 ->where('variant2_id', '=', $new_bin_item['variant2_id'])
                                                 ->where('variant3_id', '=', $new_bin_item['variant3_id'])
                                                 ->where('variant4_id', '=', $new_bin_item['variant4_id'])
                                                 ->take(1)->get();

                            //exists
                            if( count($result) > 0 )
                            {
                                //update model item
                                $new_bin_item['quantity'] = $result[0]['quantity'];
                                $new_bin_item['id'] = $result[0]['id'];
                                $new_bin_item['bin_id'] = $result[0]['bin_id'];

                                //update the inventory table
                                $this->updateInventoryItem($product_id, $new_bin_item);
                            }

                            //doesn't exist so create new
                            else
                            {
                                //add to main inventory table
                                $inventory_item = new Inventory();
                                $inventory_item->bin_id = $bin['id'];
                                $inventory_item->quantity = $new_bin_item['quantity_new'];
                                $inventory_item->receive_date = $new_bin_item['receive_date'];
                                $inventory_item->variant1_id = $new_bin_item['variant1_id'];
                                $inventory_item->variant2_id = $new_bin_item['variant2_id'];
                                $inventory_item->variant3_id = $new_bin_item['variant3_id'];
                                $inventory_item->variant4_id = $new_bin_item['variant4_id'];
                                $inventory_item->save();

                                //update inventory log table
                                $inventory_log = new InventoryLog();
                                $inventory_log->addItem(InventoryLogType::INVENTORY_EDIT, $product_id, $bin['id'], $new_bin_item);
                            }
                        }
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            //rollback since something failed
            DB::rollback();

            //log error so we can trace it if need be later
            Log::info(auth()->user());
            Log::error($e);

            //set error message.  Don't send verbose error if not in debug mode
            $err_msg = ( env('APP_DEBUG') === true ) ? $e->getMessage() : 'SQL error. Please try again or report the issue to the admin.';

            //send back error
            $error_message = array('errorMsg' => 'The inventory was not updated with the error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        //return updated data as a way to refresh the list and reset the UX
        return $this->getProductInventory($product_id);
    }

    private function updateInventoryItem($product_id, $bin_item)
    {
        $inventory_item = Inventory::find($bin_item['id']);
        $inventory_item->quantity = $bin_item['quantity_new'];
        $inventory_item->save();

        //update inventory log table
        $inventory_log = new InventoryLog();
        $inventory_log->addItem(InventoryLogType::INVENTORY_EDIT, $product_id, $bin_item['bin_id'], $bin_item);
    }

    private function getVariantId($product_id, $variant_value, $variant_name, $table)
    {
        //if value is null, then id is null
        if( $variant_value === null || strlen($variant_name) == 0 ) { return null; }

        //check to see if this variant exists
        $variant = DB::table($table)
                       ->where('product_id', '=', $product_id)
                       ->where('value', 'ILIKE', $variant_value)
                       ->take(1)->get();

        //exists
        if( count($variant) > 0 )
        {
            return $variant[0]->id;
        }

        //does not exists so create it
        else
        {
            return DB::table($table)->insertGetId(['product_id' => $product_id,
                                                   'name' => $variant_name,
                                                   'value' => $variant_value,
                                                   'active' => true,
                                                   'created_at' => date('Y-m-d G:i:s'),
                                                   'updated_at' => date('Y-m-d G:i:s')]);
        }
    }
}