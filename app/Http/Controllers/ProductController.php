<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use App\Models\Province;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\ProductWarehouse;
use App\Models\ProductType;
use DB;

class ProductController extends Controller
{
    protected $my_name = 'product';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Product::orderBy('sku')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/product');

        //build the list data
        $product_type_data = ProductType::select('id', 'name')->orderBy('name')->get();

        return response()->view('pages.product', ['main_data' => $data,
                                                  'url' => $url,
                                                  'my_name' => $this->my_name,
                                                  'product_type_data' => $product_type_data]);
    }

    public function getCheckDuplicate($name, $client_id)
    {
        return Product::select('id')
                        ->where('name', 'ILIKE', $name)
                        ->where('client_id', '=', $client_id)
                        ->take(1)->get();
    }

    public function postNew()
    {
        //get values to be used to check for duplicates
        $sku = request()->json('sku');
        $client_id = request()->json('client_id');

        //first check to make sure this is not a duplicate
        $products = $this->getCheckDuplicate($sku, $client_id);
        if( count($products) > 0 )
        {
            $error_message = array('errorMsg' => 'The product with sku of ' . $sku . ' already exists for this client.');
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
        $product = ( !empty(request()->json('id')) ) ? Product::find(request()->json('id')) : new Product();
        $product->sku             = request()->json('sku');
        $product->name            = request()->json('name');
        $product->bar_code        = request()->json('bar_code');
        $product->client_sku      = request()->json('client_sku');
        $product->product_type_id = request()->json('product_type_id');
        $product->custom1         = request()->json('custom1', null);
        $product->custom2         = request()->json('custom2', null);
        $product->custom3         = request()->json('custom3', null);
        $product->uom1            = request()->json('uom1', null);
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

    public function putDelete($id)
    {
        Product::find($id)->delete();
    }
}
