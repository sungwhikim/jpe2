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

    public function postNew()
    {
        //set code to a variable
        $code = request()->json('code');

        //first check to make sure this is not a duplicate
        $countries = $this->getCheckDuplicate($code);
        if( count($countries) > 0 )
        {
            $error_message = array('errorMsg' => 'The inventory code of ' . $code . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $inventory_id = $this->saveItem();

        return response()->json(['id' => $inventory_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $inventory = ( !empty(request()->json('id')) ) ? Inventory::find(request()->json('id')) : new Inventory();
        $inventory->code = request()->json('code');
        $inventory->name = request()->json('name');
        $inventory->currency_name = request()->json('currency_name');
        $inventory->currency_prefix = request()->json('currency_prefix');
        $inventory->save();

        return $inventory->id;
    }

    public function putDelete($id)
    {
        Inventory::find($id)->delete();
    }
}