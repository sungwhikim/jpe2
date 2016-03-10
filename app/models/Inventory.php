<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    public function variant1()
    {
        $this->hasOne('App\Models\ProductVariant1', 'id', 'variant1_id');
    }

    public function variant2()
    {
        $this->hasOne('App\Models\ProductVariant2', 'id', 'variant2_id');
    }

    public function variant3()
    {
        $this->hasOne('App\Models\ProductVariant3', 'id', 'variant3_id');
    }

    public function variant4()
    {
        $this->hasOne('App\Models\ProductVariant4', 'id', 'variant4_id');
    }
}