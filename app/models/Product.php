<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    public function getUserProductList()
    {
        return Product::select('product.id', 'product.sku', 'product.name', 'product.active')
                        ->where('product.warehouse_id', '=', auth()->user()->current_warehouse_id)
                        ->where('product.client_id', '=', auth()->user()->current_client_id)
                        ->where('product.active', '=', true)
                        ->get();
    }
}
