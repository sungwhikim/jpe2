<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ProductVariant1;
use App\Models\ProductVariant2;
use App\Models\ProductVariant3;
use App\Models\ProductVariant4;
use App\Models\ProductType;
use App\Models\Bin;

use DB;

class Product extends Model
{
    protected $table = 'product';

    public function getUserProductList()
    {
        return Product::select('product.id', 'product.sku', 'product.name', 'product.barcode', 'product.barcode_client', 'product.active')
                      ->where('product.warehouse_id', '=', auth()->user()->current_warehouse_id)
                      ->where('product.client_id', '=', auth()->user()->current_client_id)
                      ->where('product.active', '=', true)
                      ->get();
    }

    public function getTxVariant($product_id, $get_inventory = false)
    {
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

        return $data;
    }

    public function getUomList($product_id, $set_default_uom)
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

        /* now pivot the data so we get a list of UOM with the multiplication to get to the base uom */
        //initialize variables
        $multiplier_total = 1;
        $uom_data = $uom[0];

        //set first UOM as we always know what it is
        $data[0] = ['key' => 'uom1',
                    'name' => $uom_data['uom1_name'],
                    'multiplier' => 1,
                    'multiplier_total' => 1];

        //now set the reset based on if it is active or not
        for( $i = 2; $i <= 8; $i++ )
        {
            //set key, which is numbered one more than the array key
            $key = 'uom' . $i;

            //add UOM if active
            if( $uom_data[$key . '_active'] === true )
            {
                //set multipliers
                $multiplier = $uom_data[$key];
                $multiplier_total = $multiplier_total * $multiplier;

                $data[] = ['key' => $key,
                           'name' => $uom_data[$key . '_name'],
                           'multiplier' => $multiplier,
                           'multiplier_total' => $multiplier_total];
            }
        }

        //find selected uom total multiplier
        foreach( $data as $item )
        {
            if( $item['key'] == $uom[0]['default_uom'] ) { $uom_selected = $item; }
        }

        //build final data
        $return['uoms'] = $data;

        //only add default selected uom if this is a new tx item
        if( $set_default_uom === true )
        {
            $return['selectedUom'] = $uom_selected['key'];
            $return['selectedUomMultiplierTotal'] = $uom_selected['multiplier_total'];
        }

        return $return;
    }

    public function getInventoryBin($product_id)
    {
        $bins = Bin::select('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position', 'bin.default',
                            DB::raw('SUM(inventory.quantity) as inventory'), DB::raw('0 AS quantity'))
                   ->leftJoin('inventory', 'inventory.bin_id', '=', 'bin.id')
                   ->where('bin.product_id', '=', $product_id)
                   ->where('bin.active', '=', true)
                   ->groupBy('bin.id')->groupBy('bin.aisle')->groupBy('bin.section')->groupBy('bin.tier')
                   ->groupBy('bin.position')->groupBy('bin.default')
                   ->orderBy('bin.aisle')->orderBy('bin.section')->orderBy('bin.tier')->orderBy('bin.position')
                   ->get();

        return $bins;
    }
}
