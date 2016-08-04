<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';

    public function province()
    {
        return $this->hasOne('App\Models\Province', 'id', 'province_id');
    }
}
