<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant2 extends Model
{
    protected $table = 'product_variant2';

    protected $fillable = ['product_id', 'name', 'value'];
}