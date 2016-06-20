<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveBin extends Model
{
    use SoftDeletes;

    protected $table = 'receive_bin';
}
