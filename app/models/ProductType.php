<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'product_type';

    public function getDefaultUomList()
    {
        //create 8 UOM and make it a nested array to create a set of object because of angular.js
        for( $i = 1; $i <= 8; $i++ )
        {
            $values[] = ['name' => 'uom' . $i ];
        }

        return collect($values);
    }
}
