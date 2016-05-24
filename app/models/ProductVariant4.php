<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant4 extends Model
{
    protected $table = 'product_variant4';

    protected $fillable = ['product_id', 'name', 'value'];
}