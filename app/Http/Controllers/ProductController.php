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
        $product_type = new ProductType();
        $product_type_data = ProductType::orderBy('name')->get();
        $default_uom_data = $product_type->getDefaultUomList();

        //build UOM and variant array - This is so we don't have to do variable assignments in the template and control
        //the number of each items here in the controller.
        for( $i = 1; $i < 9; $i++ ) { $uom[$i] = 'uom' . $i; }

        return response()->view('pages.product', ['main_data' => $data,
                                                  'url' => $url,
                                                  'my_name' => $this->my_name,
                                                  'uom' => $uom,
                                                  'default_uom_data' => $default_uom_data,
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

     /* HARDCODE IT TO BE THE DEFAULT FOR THE PRODUCT TYPE FOR NOW.  CHANGE IT TO BE PER PRODUCT LATER */
        $product->default_uom     = request()->json('product_type')['default_uom'];

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

    public function getTxDetail($product_id)
    {
        //get the uom list data first
        $data['uoms'] = $this->getUomList($product_id);

        //now get the inventory with variants
        $data['variants'] = $this->getTxVariant($product_id);

        return $data;
    }

    public function getTxVariant($product_id)
    {
        /*
        $variants = Product::select('variant1.name AS variant1_name', 'variant1.value AS variant1_value',
                                    'variant2.name AS variant2_name', 'variant2.value AS variant2_value',
                                    'variant3.name AS variant3_name', 'variant3.value AS variant3_value',
                                    'variant4.name AS variant4_name', 'variant4.value AS variant4_value')
                             ->leftJoin('variant1', 'product.id', '=', 'variant1.product_id')
                             ->leftJoin('variant2', 'product.id', '=', 'variant2.product_id')
                             ->leftJoin('variant3', 'product.id', '=', 'variant3.product_id')
                             ->leftJoin('variant4', 'product.id', '=', 'variant4.product_id')
                             ->where('product.id', '=', $product_id)
                             ->groupBy('variant1.value')
                             ->groupBy('variant2.value')
                             ->groupBy('variant3.value')
                             ->groupBy('variant4.value')
                             ->groupBy('variant1.name')
                             ->groupBy('variant2.name')
                             ->groupBy('variant3.name')
                             ->groupBy('variant4.name')
                             ->get()->toArray();
        */

        /* RETURN VARIANTS IN 4 DIFFERENT QUERIES FOR NOW.  IT IS SLOW AND WE NEED TO OPTIMIZE IT LATER */
        $result = Product::select('product_type.*')
                           ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                           ->where('product.id', '=', $product_id)->get()->toArray();

        //this is kind of a weird loop, but it is better than using a block of 4 case statements.
        //it is to build the variant list.
        $product_type_data = $result[0];
        for( $i = 1; $i <= 4; $i++ )
        {
            //set key base
            $key = 'variant' . $i;

            //set active flag and name
            $data[$key . '_active'] = $product_type_data[$key . '_active'];
            $data[$key . '_name'] = $product_type_data[$key];

            //if it is active, then add the list of variants used
            if( $data[$key . '_active'] === true )
            {
                $variants = ProductVariant1::select('id', 'name', 'value')
                                             ->where('product_id', '=', $product_id)
                                             ->get()->toArray();

                $data[$key . '_variants'] = $variants;
            }

        }


debugbar()->info($data);
        /* SET TOTAL INVENTORY AS PLACEHOLDER FOR NOW */
        $data['total_quantity'] = 0;

        return $data;
    }

    public function getUomList($product_id)
    {
        //get the uom
        $uom = Product::select('product_type.uom1 AS uom1_name',
                               'product_type.uom2 AS uom2_name',
                               'product_type.uom3 AS uom3_name',
                               'product_type.uom4 AS uom4_name',
                               'product_type.uom5 AS uom5_name',
                               'product_type.uom6 AS uom6_name',
                               'product_type.uom7 AS uom7_name',
                               'product_type.uom8 AS uom8_name',
                               'product_type.uom1_active',
                               'product_type.uom2_active',
                               'product_type.uom3_active',
                               'product_type.uom4_active',
                               'product_type.uom5_active',
                               'product_type.uom6_active',
                               'product_type.uom7_active',
                               'product_type.uom8_active',
                               'product.*')
                         ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                         ->where('product.id', '=', $product_id)->get()->toArray();

        /* now pivot the data so we get a list of UOM with the mulitplication to get to the base uom */
        //initialize variables
        $multiplier = 1;
        $uom_data = $uom[0];

        //set first UOM as we always know what it is
        $data[0] = ['name' => $uom_data['uom1_name'],
                    'multiplier' => $multiplier];

        //now set the reset based on if it is active or not
        for( $i = 2; $i <= 8; $i++ )
        {
            //set key, which is numbered one more than the array key
            $key = 'uom' . $i;

            //add UOM if active
            if( $uom_data[$key . '_active'] === true )
            {
                $data[] = ['name' => $uom_data[$key . '_name'],
                           'multiplier' => $uom_data[$key]];
            }
        }

        return $data;
    }
}
