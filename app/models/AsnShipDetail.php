<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsnShipDetail extends Model
{
    use SoftDeletes;

    protected $table = 'asn_ship_detail';
}
