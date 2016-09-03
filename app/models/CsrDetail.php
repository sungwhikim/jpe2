<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsrDetail extends Model
{
    use SoftDeletes;

    protected $table = 'csr_detail';
}
