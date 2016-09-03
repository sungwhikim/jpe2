<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsnDetail extends Model
{
    use SoftDeletes;

    protected $table = 'asn_detail';
}
