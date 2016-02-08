<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFunctionCategory extends Model
{
    protected $table = 'user_function_category';

    function items()
    {
        return $this->hasMany('App\Models\UserFunction');
    }
}