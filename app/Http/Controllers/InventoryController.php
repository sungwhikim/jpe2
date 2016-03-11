<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Product;
use DB;

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
        $product_data = $this->getProductList();

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
        return Product::select('product.id', 'product.sku', 'product.name', 'product.active',
                               'product_type.variant1', 'product_type.variant1_active',
                               'product_type.variant2', 'product_type.variant2_active',
                               'product_type.variant3', 'product_type.variant3_active',
                               'product_type.variant4', 'product_type.variant4_active')
                        ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                        ->where('product.warehouse_id', '=', auth()->user()->current_warehouse_id)
                        ->where('product.client_id', '=', auth()->user()->current_client_id)
                        ->where('product.active', '=', true)
                        ->get();
    }

    public function getProductInventory($product_id)
    {
        //first grab the bins and the rollup total in each and the product type data to get the variants
        $bins = Inventory::select('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position', 'bin.active',
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
            $bin_data = Inventory::select('inventory.*',
                                          'product_variant1.value as variant1_value',
                                          'product_variant2.value as variant2_value',
                                          'product_variant3.value as variant3_value',
                                          'product_variant4.value as variant4_value')
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

        debugbar()->info($bins->toArray());

        return $bins;
    }
    
    public function getCheckDuplicate($code)
    {
        return Inventory::select('id')->where('code', 'ILIKE', $code)->take(1)->get();
    }

    public function postSave($product_id)
    {
        /* for debugging */
        debugbar()->info(request()->json());

    /* !!!! WRAP BELOW IN A TRANSACTION !!!!! */
        /* loop through and update data */
        foreach( request()->json() as $bin )
        {
            //go through each bin item
            foreach( $bin as $bin_item )
            {
                //only update if new quantity is set
                if( isset($bin_item->quantity_new) && is_numeric($bin_item->quantity_new) )
                {
                    //update the main inventory table
                    $bin_item = Inventory::get($bin->id);
                    $bin_item->quantity = $bin->quantity_new;
                    $bin_item->save();

                    //update inventory log table
                    $bin_log = new InventoryLog();

                }
            }

            //see if any new bin item need to be added
            if( isset($bin->new_items) )
            {
                //go through each bin item
                foreach( $bin->new_items as $new_bin_item )
                {
                    //only update if new quantity is set
                    if( isset($new_bin_item->quantity_new) && is_numeric($new_bin_item->quantity_new) )
                    {
                        //add to main inventory table
                        $new_bin_item = new Inventory();
                        $new_bin_item->bin_id = $bin->id;
                        $new_bin_item->quantity = $new_bin_item->quantity_new;
                        $new_bin_item->save();

                        //update inventory log table
                        $bin_log = new InventoryLog();

                    }
                }
            }

        }

        //return updated data as a way to refresh the list and reset the UX
        return $this->getProductInventory($product_id);
    }

    public function putDelete($id)
    {
        Inventory::find($id)->delete();
    }
}