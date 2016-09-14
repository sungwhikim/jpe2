<?php
namespace App\Http\Controllers;

use App\Models\UserWarehouse;
use App\Models\Warehouse;
use App\Models\Country;
use App\User;

use DB;
use Log;
use Exception;

class WarehouseController extends Controller
{
    protected $my_name = 'warehouse';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Warehouse::orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/warehouse');

        //set lists
        $country = new Country();
        $country_data = $country->ListWithProvinces();

        return response()->view('pages.warehouse', ['main_data' => $data,
                                                    'url' => $url,
                                                    'my_name' => $this->my_name,
                                                    'country_data' => $country_data]);
    }

    public function getById($id)
    {
        return Warehouse::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($name)
    {
        return Warehouse::select('id')->where('name', 'ILIKE', $name)
                                      ->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables;
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $warehouses = $this->getCheckDuplicate($name);
        if( count($warehouses) > 0 )
        {
            $error_message = array('errorMsg' => 'The warehouse with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //create new item
            $warehouse_id = $this->saveItem();

            /* add warehouse for all admins */
            //get user model and call function to update warehouse/client list
            $user_model = new User();
            $user_model->addAllWarehouseClientAdmin(true, false);
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
            $error_message = array('errorMsg' => 'The client was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return response()->json(['id' => $warehouse_id]);
    }

    public function postUpdate()
    {
        return $this->saveItem();
    }

    private function saveItem()
    {
        $warehouse = ( !empty(request()->json('id')) ) ? Warehouse::find(request()->json('id')) : new Warehouse();
        $warehouse->name        = request()->json('name');
        $warehouse->care_of     = request()->json('care_of');
        $warehouse->address1    = request()->json('address1');
        $warehouse->address2    = request()->json('address2');
        $warehouse->city        = request()->json('city');
        $warehouse->postal_code = request()->json('postal_code');
        $warehouse->province_id = request()->json('province_id');
        $warehouse->country_id  = request()->json('country_id');
        $warehouse->active      = ( !empty(request()->json('active')) ) ? true : false;
        $warehouse->save();

        return $warehouse->id;
    }

    public function putDelete($id)
    {
        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //delete all references in the user table
            UserWarehouse::where('warehouse_id', '=', $id)->delete();

            //delete the warehouse
            Warehouse::find($id)->delete();
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
            $error_message = array('errorMsg' => 'The client was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return true;
    }
}
?>