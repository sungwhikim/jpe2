<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouse';

    public function province()
    {
        return $this->hasOne('App\Models\Province', 'id', 'province_id');
    }
}
