<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant1;
use App\Models\ProductVariant2;
use App\Models\ProductVariant3;
use App\Models\ProductVariant4;
use App\Models\ProductType;

use DB;

class ProductController extends Controller
{
    protected $my_name = 'product';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getList()
    {
        return Product::where('warehouse_id', '=', auth()->user()->current_warehouse_id)
                        ->where('client_id', '=', auth()->user()->current_client_id)
                        ->orderBy('sku')->get();
    }

    public function getListView()
    {
        //get the current warehouse and client
        $warehouse_id = auth()->user()->current_warehouse_id;
        $client_id = auth()->user()->current_client_id;

        //if not set, then we need to make the user set it first
        if( $warehouse_id === null || $client_id === null )
        {

        }

        //get the list data with the default sort set the same as in the angular table
        $data = $this->getList();

        //we need to send the url to do Ajax queries back here
        $url = url('/product');

        //build the list data
        $product_type_data = ProductType::orderBy('name')->get();

        //build UOM and variant array - This is so we don't have to do variable assignments in the template and control
        //the number of each items here in the controller.
        for( $i = 1; $i < 9; $i++ ) { $uom[$i] = 'uom' . $i; }

        return response()->view('pages.product', ['main_data' => $data,
                                                  'url' => $url,
                                                  'my_name' => $this->my_name,
                                                  'uom' => $uom,
                                                  'product_type_data' => $product_type_data]);
    }

    public function getCheckDuplicate($sku, $client_id, $warehouse_id)
    {
        return Product::select('id')
                        ->where('sku', 'ILIKE', $sku)
                        ->where('client_id', '=', $client_id)
                        ->where('warehouse_id', '=', $warehouse_id)
                        ->take(1)->get();
    }

    public function postNew()
    {
        //get values to be used to check for duplicates
        $sku = request()->json('sku');
        $client_id = auth()->user()->current_client_id;
        $warehouse_id = auth()->user()->current_warehouse_id;

        //first check to make sure this is not a duplicate
        $products = $this->getCheckDuplicate($sku, $client_id, $warehouse_id);
        if( count($products) > 0 )
        {
            $error_message = array('errorMsg' => 'The product with sku of ' . $sku . ' already exists for this warehouse and client.');
            return response()->json($error_message);
        }

        //create new item
        $product_id = $this->saveItem();

        return response()->json(['id' => $product_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $product = !empty(request()->json('id')) ? Product::find(request()->json('id')) : new Product();
        $product->client_id       = auth()->user()->current_client_id;
        $product->warehouse_id    = auth()->user()->current_warehouse_id;
        $product->sku             = request()->json('sku');
        $product->sku_client      = request()->json('sku_client', null);
        $product->name            = request()->json('name');
        $product->barcode         = request()->json('barcode', null);
        $product->barcode_client  = request()->json('barcode_client', null);
        $product->rfid            = request()->json('rfid', null);
        $product->product_type_id = request()->json('product_type_id');
        $product->custom1         = request()->json('custom1', null);
        $product->custom2         = request()->json('custom2', null);
        $product->custom3         = request()->json('custom3', null);
        $product->uom1            = request()->json('uom1');
        $product->uom2            = request()->json('uom2', null);
        $product->uom3            = request()->json('uom3', null);
        $product->uom4            = request()->json('uom4', null);
        $product->uom5            = request()->json('uom5', null);
        $product->uom6            = request()->json('uom6', null);
        $product->uom7            = request()->json('uom7', null);
        $product->uom8            = request()->json('uom8', null);
        $product->oversized_pallet= ( !empty(request()->json('oversized_pallet')) ) ? true : false;
        $product->active          = ( !empty(request()->json('active')) ) ? true : false;

        $product->save();
        $product_id = $product->id;

        return $product_id;
    }

    private function checkUsage($id)
    {
        return false;
    }

    public function putDelete($id)
    {
        //if product has been used in a transaction, it cannot be deleted
        if( $this->checkUsage($id) )
        {
            $error_message = array('errorMsg' => 'This product cannot be deleted, because it has been used in a transaction.
                                                  Please set to inactive if you no longer want to use this product.');
            return response()->json($error_message);
        }

        //delete related tables
        ProductVariant1::where('product_id', '=', $id)->delete();
        ProductVariant2::where('product_id', '=', $id)->delete();
        ProductVariant3::where('product_id', '=', $id)->delete();
        ProductVariant4::where('product_id', '=', $id)->delete();

        //delete main table
        Product::find($id)->delete();
    }
}