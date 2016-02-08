<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFunction extends Model
{
    protected $table = 'user_function';

    public function category()
    {
        return $this->belongsTo('App\Models\UserFunctionCategory');
    }
}