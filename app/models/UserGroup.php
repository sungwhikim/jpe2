<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_group';

    public function userFunctions()
    {
        return $this->hasManyThrough('UserFunction', 'UserGroupToUserFunction');
    }
}