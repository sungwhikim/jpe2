<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant1 extends Model
{
    protected $table = 'variant1';

    protected $fillable = ['product_id', 'name', 'value'];
}