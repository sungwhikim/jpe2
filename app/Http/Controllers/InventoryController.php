<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Bin;
use App\Models\Product;
use App\Enum\InventoryActivityType;

use DB;
use Log;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class InventoryController extends Controller
{
    protected $my_name = 'inventory';
    protected $product_model = null;
    protected $inventory_model = null;

    public function __construct()
    {
        $this->middleware('auth');

        //init variables
        $this->product_model = new Product();
        $this->inventory_model = new Inventory();
    }

    public function getIndex()
    {
        //get the list data with the default sort set the same as in the angular table
        $product_data = $this->product_model->getUserProductListWithVariant();

        //we need to send the url to do Ajax queries back here
        $url = url('/inventory');

        //set params
        $params = ['main_data' => collect([]),
                   'url' => $url,
                   'my_name' => $this->my_name,
                   'product_data' => $product_data];

        return view('pages.inventory', $params);
    }

    /**
     * This is meant to be used as a pass through function for Ajax calls
     *
     * @return mixed
     */
    public function getProductList()
    {
        return $this->product_model->getUserProductList();
    }

    /**
     * This is a pass through method to the model to get the product inventory and bins
     *
     * @param $product_id
     *
     * @return mixed
     */
    public function getProductInventory($product_id)
    {
        //get the inventory
        $data['inventory'] = $this->inventory_model->getProductInventory($product_id);

        //get the UOM list
        $data['uom'] = $this->product_model->getUomList($product_id, true);

        return $data;
    }

    /**
     * Save the inventory data
     *
     * @param $product_id
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws Exception
     */
    public function postSave($product_id)
    {
        //get the data and parse
        $bins = request()->json('bins');
        $uom_multiplier = request()->json('uomMultiplier');

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            /* loop through and update data */
            foreach ( $bins as $bin )
            {
                /* NEED TO ADD CHECK FOR NON-ZERO BIN ITEMS AND NOT ALLOW INACTIVATION IF ANY QUANTITIES EXIST */
                //update active flag.
                $bin_model = Bin::find($bin['id']);
                $bin_model->active = $bin['active'];
                $bin_model->default = $bin['default'];
                $bin_model->save();

                /* Run the loop for bin items and new items separately because neither could be set and it is easier
                   to do this rather than trying to merge it into one array */
                //go through each bin item if set
                if ( isset($bin['bin_items']) )
                {
                    foreach ( $bin['bin_items'] as $bin_item )
                    {
                        //only update if new quantity is set and different than inventory value
                        if ( isset($bin_item['quantity_new']) && is_numeric($bin_item['quantity_new']) && $bin_item['quantity'] != $bin_item['quantity_new'] )
                        {
                            //prevent negative values
                            if( $bin_item['quantity_new'] < 0 ) { throw new Exception('Bin quantity cannot be negative'); }

                            //prevent decimals
                            if( fmod($bin_item['quantity_new'], 1) != 0 ) { throw new Exception('Bin quantity must be a whole number'); }

                            //set quantity to raw quantity
                            $bin_item['quantity_new'] = $bin_item['quantity_new'] * $uom_multiplier;

                            //get variants
                            $variant1_id = $this->getVariantId($product_id, $bin_item['variant1_value'], $bin['variant1'], 'product_variant1');
                            $variant2_id = $this->getVariantId($product_id, $bin_item['variant2_value'], $bin['variant2'], 'product_variant2');
                            $variant3_id = $this->getVariantId($product_id, $bin_item['variant3_value'], $bin['variant3'], 'product_variant3');
                            $variant4_id = $this->getVariantId($product_id, $bin_item['variant4_value'], $bin['variant4'], 'product_variant4');

                            //set quantity difference to add to inventory table.  It is the difference because the table is a log of
                            //all inventory activity
                            $quantity_difference = $bin_item['quantity_new']  - $bin_item['quantity'];

                            //update the inventory table
                            $this->inventory_model->addInventoryItem($quantity_difference, $bin['id'], $bin_item['receive_date'],
                                $variant1_id, $variant2_id, $variant3_id, $variant4_id, InventoryActivityType::INVENTORY_EDIT, null, null);
                        }
                    }
                }

                //see if any new bin item need to be added
                if ( isset($bin['new_bin_items']) )
                {
                    //go through each new bin item
                    foreach ( $bin['new_bin_items'] as $bin_item )
                    {
                        //only update if new quantity is set and greater than 0.  Since it is a new item, we can enforce the greater than 0 requirement.
                        if ( isset($bin_item['quantity_new']) && is_numeric($bin_item['quantity_new']) && $bin_item['quantity_new'] > 0 )
                        {
                            //get variants
                            $variant1_id = $this->getVariantId($product_id, $bin_item['variant1_value'], $bin['variant1'], 'product_variant1');
                            $variant2_id = $this->getVariantId($product_id, $bin_item['variant2_value'], $bin['variant2'], 'product_variant2');
                            $variant3_id = $this->getVariantId($product_id, $bin_item['variant3_value'], $bin['variant3'], 'product_variant3');
                            $variant4_id = $this->getVariantId($product_id, $bin_item['variant4_value'], $bin['variant4'], 'product_variant4');

                            //set quantity to raw quantity
                            $bin_item['quantity_new'] = $bin_item['quantity_new'] * $uom_multiplier;

                            //set quantity difference to add to inventory table.  It is the difference because the table is a log of
                            //all inventory activity
                            $quantity_difference = $bin_item['quantity_new'] - $bin_item['quantity'];

                            //update the inventory table
                            $this->inventory_model->addInventoryItem($quantity_difference, $bin['id'], $bin_item['receive_date'],
                                $variant1_id, $variant2_id, $variant3_id, $variant4_id, InventoryActivityType::INVENTORY_EDIT, null, null);
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

    /**
     * For used by Ajax call to return the total inventory for the variant(s) chosen
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getVariantInventoryTotal()
    {
        $inventory_model = new Inventory();
        $data['inventory_total'] = $inventory_model->getVariantInventoryTotal(request()->json('product_id'), request()->json('variant1_id'),
                                        request()->json('variant2_id'), request()->json('variant3_id'), request()->json('variant4_id'));

        return $data;
    }
}