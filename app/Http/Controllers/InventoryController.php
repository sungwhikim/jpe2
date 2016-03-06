<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Product;

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
        $product_data = $this->getProducts();

        //we need to send the url to do Ajax queries back here
        $url = url('/inventory');

        //set params
        $params = ['main_data' => collect([]),
                   'url' => $url,
                   'my_name' => $this->my_name,
                   'product_data' => $product_data];

        return view('pages.inventory', $params);
    }

    protected function getProducts()
    {
        return Product::where('product.warehouse_id', '=', auth()->user()->current_warehouse_id)
                        ->where('product.client_id', '=', auth()->user()->current_client_id)
                        ->get();
    }

    public function getProduct($product_id)
    {
        $data = Inventory::with('variant1', 'variant2', 'variant3', 'variant4')
                           ->where('product_id', '=', $product_id)
                           ->get();
  debugbar()->info($data);
        return $data;
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