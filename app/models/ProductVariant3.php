<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant3 extends Model
{
    protected $table = 'product_variant3';

    protected $fillable = ['product_id', 'name', 'value'];
}