<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use \App\Models\UserGroup;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'name', 'email', 'password', 'user_group_id', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The group the user belongs to
     */
    public function userGroup()
    {
        return $this->hasOne('App\Models\UserGroup', 'id', 'user_group_id');
    }

    /**
     * The user functions returned in a list
     */
    public function userFunctions()
    {
        $result = UserGroup::select('user_group.name as user_group_name', 'user_function.*',
                                    'user_function_category.sort_order as category_sort_order',
                                    'user_function.sort_order as function_sort_order')
                            ->join('user_group_to_user_function', 'user_group.id', '=', 'user_group_to_user_function.user_group_id')
                            ->join('user_function', 'user_function.id', '=', 'user_group_to_user_function.user_function_id')
                            ->join('user_function_category', 'user_function.user_function_category_id', '=', 'user_function_category.id')
                            ->where('user_group.id', '=', $this->user_group_id)
                            ->orderBy('user_function_category.sort_order')->orderBy('user_function.sort_order')->get();

        return $result->groupBy('user_function_category_name');
    }
}
