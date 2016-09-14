<?php
namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\Product;
use App\Models\Client;
use DB;

class ProductTypeController extends Controller
{
    protected $my_name = 'product_type';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = ProductType::select('product_type.*', DB::raw("COALESCE(variant1, '') || ' ' ||
                                                               COALESCE(variant2, '') || ' ' ||
                                                               COALESCE(variant3, '') || ' ' ||
                                                               COALESCE(variant4, '') as variants"))
                             ->orderBy('product_type.name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/product-type');

        //get default uom data
        $product_type = new ProductType();
        $default_uom_data = $product_type->getDefaultUomList();

        return response()->view('pages.product-type', ['main_data' => $data,
                                                       'url' => $url,
                                                       'my_name' => $this->my_name,
                                                       'default_uom_data' => $default_uom_data]);
    }

    public function getCheckDuplicate($name)
    {
        return ProductType::select('id')
                            ->where('name', 'ILIKE', $name)
                            ->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $product_types = $this->getCheckDuplicate($name);
        if( count($product_types) > 0 )
        {
            $error_message = array('errorMsg' => 'The product type with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $product_type_id = $this->saveItem();

        return response()->json(['id' => $product_type_id]);
    }

    public function postUpdate()
    {
        return $this->saveItem();
    }

    private function saveItem()
    {
        $product_type = ( !empty(request()->json('id')) ) ? ProductType::find(request()->json('id')) : new ProductType();
        $product_type->name            = request()->json('name');
        $product_type->description     = request()->json('description');
        $product_type->default_uom     = request()->json('default_uom');
        $product_type->active          = ( !empty(request()->json('active')) ) ? true : false;
        $product_type->variant1        = ( strlen(trim(request()->json('variant1', null))) > 0 ) ? request()->json('variant1') : null;
        $product_type->variant2        = ( strlen(trim(request()->json('variant2', null))) > 0 ) ? request()->json('variant2') : null;
        $product_type->variant3        = ( strlen(trim(request()->json('variant3', null))) > 0 ) ? request()->json('variant3') : null;
        $product_type->variant4        = ( strlen(trim(request()->json('variant4', null))) > 0 ) ? request()->json('variant4') : null;
        $product_type->variant1_active = ( !empty(request()->json('variant1_active')) ) ? true : false;
        $product_type->variant2_active = ( !empty(request()->json('variant2_active')) ) ? true : false;
        $product_type->variant3_active = ( !empty(request()->json('variant3_active')) ) ? true : false;
        $product_type->variant4_active = ( !empty(request()->json('variant4_active')) ) ? true : false;
        $product_type->uom1            = request()->json('uom1', null);
        $product_type->uom2            = request()->json('uom2', null);
        $product_type->uom3            = request()->json('uom3', null);
        $product_type->uom4            = request()->json('uom4', null);
        $product_type->uom5            = request()->json('uom5', null);
        $product_type->uom6            = request()->json('uom6', null);
        $product_type->uom7            = request()->json('uom7', null);
        $product_type->uom8            = request()->json('uom8', null);
        $product_type->uom1_active     = ( !empty(request()->json('uom1_active')) ) ? true : false;
        $product_type->uom2_active     = ( !empty(request()->json('uom2_active')) ) ? true : false;
        $product_type->uom3_active     = ( !empty(request()->json('uom3_active')) ) ? true : false;
        $product_type->uom4_active     = ( !empty(request()->json('uom4_active')) ) ? true : false;
        $product_type->uom5_active     = ( !empty(request()->json('uom5_active')) ) ? true : false;
        $product_type->uom6_active     = ( !empty(request()->json('uom6_active')) ) ? true : false;
        $product_type->uom7_active     = ( !empty(request()->json('uom7_active')) ) ? true : false;
        $product_type->uom8_active     = ( !empty(request()->json('uom8_active')) ) ? true : false;

        $product_type->save();
        $product_type_id = $product_type->id;

        return $product_type_id;
    }

    public function putDelete($id)
    {
        /* we need to check to make sure this product type has not been used before it can be deleted. */
        $result = Product::select('sku')->where('product_type_id', '=', $id)->take(1)->get();

        //no usage found so we can delete
        if( count($result) == 0 )
        {
            ProductType::find($id)->delete();
        }
        //usage found so we cannot delete and tell the user to inactivate it instead
        else
        {
            $error_message = array('errorMsg' => 'This product type is being used for the following products
                                                  and cannot be deleted. Please inactivate it if you do not want
                                                  to use it any longer. ' . implode(', ', $result));
            return response()->json($error_message);
        }

    }
}
