<?php

namespace App;

use App\Models\UserClient;
use App\Models\UserWarehouse;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Models\UserGroup;
use App\Models\Warehouse;
use App\Models\Client;

use DB;
use Log;
use Exception;

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
    protected $fillable = ['username', 'name', 'email', 'password', 'user_group_id',
                           'default_warehouse_id', 'default_client_id',
                           'current_warehouse_id', 'current_client_id', 'active'];

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
     * The warehouses and clients the user has access to
     */
    public function warehouseClientList()
    {
        $warehouses = Warehouse::select('warehouse.id', 'warehouse.name')
                                 ->join('user_warehouse', 'warehouse.id', '=', 'user_warehouse.warehouse_id')
                                 ->where('user_warehouse.user_id', '=', $this->id)
                                 ->where('active', '=', true)
                                 ->orderBy('name')
                                 ->get();

        //add clients the user has access to
        foreach( $warehouses as $warehouse )
        {
            $warehouse->clients = Client::select('client.id', 'client.short_name', 'client.name', 'client.active', 'client.show_barcode_client')
                                  ->join('client_warehouse', 'client.id', '=', 'client_warehouse.client_id')
                                  ->join('user_client', 'client_warehouse.client_id', '=', 'user_client.client_id')
                                  ->where('active', '=', true)
                                  ->where('client_warehouse.warehouse_id', '=', $warehouse->id)
                                  ->where('user_client.user_id', '=', $this->id)
                                  ->orderBy('short_name')
                                  ->get()->toArray();
        }

        return $warehouses;
    }

    /**
     * Gets the warehouse and client selection
     */
    public function warehouseClientGet()
    {
        //if current is set return it
        if( $this->current_warehouse_id != null && $this->current_client_id != null )
        {
            return $this->warehouseClientCurrent();
        }

        //since current is not set, check for default and set it to current and return it
        if( $this->default_warehouse_id != null && $this->default_client_id != null )
        {
            //update the current value
            $this->current_warehouse_id = $this->default_warehouse_id;
            $this->current_client_id = $this->default_client_id;
            $this->save();

            return $this->warehouseClientCurrent();
        }

        //since neither the default nor the current items were set, return nothing
        return ['warehouse_id' => null,
                'warehouse_name' => null,
                'client_id' => null,
                'client_short_name' => null,
                'show_barcode_client' => null];
    }

    /**
     * The currently set warehouse and client
     */
    protected function warehouseClientCurrent()
    {
        //return the value
        return ['warehouse_id' => $this->current_warehouse_id,
                'warehouse_name' => Warehouse::where('id', '=', $this->current_warehouse_id)->pluck('name'),
                'client_id' => $this->current_client_id,
                'client_short_name' => Client::where('id', '=', $this->current_client_id)->pluck('short_name'),
                'show_barcode_client' => Client::where('id', '=', $this->current_client_id)->pluck('show_barcode_client')];
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
                            ->where('user_function.active', '=', true)
                            ->orderBy('user_function_category.sort_order')->orderBy('user_function.sort_order')->get();

        return $result->groupBy('user_function_category_name');
    }

    public function addAllWarehouseClientAdmin($add_warehouses, $add_clients)
    {
        //get list of all the admins
        $admins = User::select('id')->where('user_group_id', '=', 1)->get();

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //loop through and update each user
            foreach( $admins as $admin )
            {
                $message = $this->addWarehouseClient($admin->id, $add_warehouses, $add_clients);

                //check for error and bubble it up
                if( $message !== true ) { throw new Exception('Adding warehouse/client failed for ' . $admin->name); }
            }
        }
        catch(\Exception $e)
        {
            //rollback since something failed
            DB::rollback();

            //log error so we can trace it if need be later
            Log::info(auth()->user());
            Log::error($e);

            //set error message.  Don't send verbose error if not in debug mode
            $err_msg = ( env('APP_DEBUG') === true ) ? $e->getMessage() : 'SQL error. Please try again or report the issue to the admin.';

            //send back error
            $error_message = array('errorMsg' => 'The item was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return true;
    }

    /**
     * Adds all warehouse and/or clients for a give user id
     *
     * @param $user_id
     * @param $add_warehouses
     * @param $add_clients
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function addWarehouseClient($user_id, $add_warehouses, $add_clients)
    {
        //get all warehouses and clients
        $warehouses = Warehouse::select('id')->get();
        $clients = Client::select('id')->get();

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //update warehouses
            if( $add_warehouses )
            {
                //delete first
                UserWarehouse::where('user_id', '=', $user_id)->delete();

                //add
                foreach( $warehouses as $warehouse)
                {
                    $user_warehouse = new UserWarehouse();
                    $user_warehouse->user_id = $user_id;
                    $user_warehouse->warehouse_id = $warehouse->id;
                    $user_warehouse->save();
                }
            }

            //update clients
            if( $add_clients )
            {
                //delete first
                UserClient::where('user_id', '=', $user_id)->delete();

                //add
                foreach( $clients as $client )
                {
                    $user_client = new UserClient();
                    $user_client->user_id = $user_id;
                    $user_client->client_id = $client->id;
                    $user_client->save();
                }
            }
        }
        catch(\Exception $e)
        {
            //rollback since something failed
            DB::rollback();

            //log error so we can trace it if need be later
            Log::info(auth()->user());
            Log::error($e);

            //set error message.  Don't send verbose error if not in debug mode
            $err_msg = ( env('APP_DEBUG') === true ) ? $e->getMessage() : 'SQL error. Please try again or report the issue to the admin.';

            //send back error
            $error_message = array('errorMsg' => 'The admin user warehouses/clients. Error: ' . $err_msg);
            return $error_message;
        }

        //if we got here, then everything worked!
        DB::commit();

        return true;
    }

    /**
     * Authenticate access to the path/route
     */
    public function validateRoute()
    {
        $route = '/' . request()->route()->uri();

        $result = UserGroup::select('user_function.*')
                            ->join('user_group_to_user_function', 'user_group.id', '=', 'user_group_to_user_function.user_group_id')
                            ->join('user_function', 'user_function.id', '=', 'user_group_to_user_function.user_function_id')
                            ->where('user_group.id', '=', $this->user_group_id)
                            ->where('user_function.active', '=', true)
                            ->where('user_function.url', '=', $route)->get();

        debugbar()->info($result);
    }
}
