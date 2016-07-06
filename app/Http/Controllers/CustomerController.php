<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Country;
use App\Models\Warehouse;
use App\Models\Client;
use App\Models\CustomerClientWarehouse;

use DB;
use Log;

class CustomerController extends Controller
{
    protected $my_name = 'customer';
    protected $url = null;

    public function __construct()
    {
        $this->middleware('auth');

        $this->url = url('/customer');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Customer::select('customer.*', 'province.name as province_name')
                          ->join('province', 'customer.province_id', '=', 'province.id')
                          ->orderBy('customer.name')->get();

        //set lists
        $country = new Country();
        $country_data = $country->ListWithProvinces();

    /* FILTER DOWN BOTH LISTS TO ONLY WH AND CLIENT AVAILABLE TO THE LOGGED IN USER */
        $warehouse_data = Warehouse::select('id', 'name', 'active')->orderBy('name')->get();
        $client_data = Client::select('id', 'short_name', 'name', 'active')->orderBy('name')->get();

        return response()->view('pages.customer', ['main_data' => $data,
                                                    'url' => $this->url,
                                                    'my_name' => $this->my_name,
                                                    'country_data' => $country_data,
                                                    'warehouse_data' => $warehouse_data,
                                                    'client_data' => $client_data]);
    }

    public function getNewPopup()
    {
        return response()->view('pages.customer-popup', ['url' => $this->url,
                                                         'my_name' => $this->my_name,
                                                         'warehouse_id' => auth()->user()->current_warehouse_id,
                                                         'client_id' => auth()->user()->current_client_id]);
    }

    public function getClientWarehouse($customer_id)
    {
        $clients = CustomerClientWarehouse::select('client_id')
                                            ->where('customer_id', '=', $customer_id)->get();

        $warehouses = CustomerClientWarehouse::select('warehouse_id')
                                               ->where('customer_id', '=', $customer_id)->get();

        return ['clients' => $clients->toArray(),
                'warehouses' => $warehouses->toArray()];
    }

    public function getCheckDuplicate($name)
    {
        return Customer::select('id')->where('name', 'ILIKE', $name)->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $customers = $this->getCheckDuplicate($name);
        if( count($customers) > 0 )
        {
            $error_message = array('errorMsg' => 'The customer with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //create new item
            $customer_id = $this->saveItem();

            //add to warehouse client table if it was a popup and warehouse and client id's were sent in
            $warehouse_id = request()->json('warehouse_id');
            $client_id = request()->json('client_id');
            if ( !empty($warehouse_id) && !empty($client_id) )
            {
                $customer_client_warehouse = new CustomerClientWarehouse();
                $customer_client_warehouse->customer_id = $customer_id;
                $customer_client_warehouse->warehouse_id = $warehouse_id;
                $customer_client_warehouse->client_id = $client_id;
                $customer_client_warehouse->save();
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
            $error_message = array('errorMsg' => 'The customer was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return response()->json(['id' => $customer_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $customer = ( !empty(request()->json('id')) ) ? Customer::find(request()->json('id')) : new Customer();
        $customer->name        = request()->json('name');
        $customer->contact     = request()->json('contact');
        $customer->email       = request()->json('email');
        $customer->phone       = request()->json('phone');
        $customer->fax         = request()->json('fax');
        $customer->address1    = request()->json('address1');
        $customer->address2    = request()->json('address2');
        $customer->city        = request()->json('city');
        $customer->postal_code = request()->json('postal_code');
        //set to null if -1 was sent in.  We can't send in null because then the client side validation won't work with
        //angular.  The reason for null province id is due to foreign countries with questionable province definitions
        //and existing data is dirty without set provinces for customer data
        $customer->province_id = ( request()->json('province_id') == -1 ) ? null : request()->json('province_id');
        $customer->country_id  = request()->json('country_id');
        $customer->active      = ( !empty(request()->json('active')) ) ? true : false;
        $customer->save();

        return $customer->id;
    }

    public function putDelete($id)
    {
        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //delete the items in the warehouse client table first
            CustomerClientWarehouse::where('customer_id', '=', $id)->delete();

            //delete from the main table
            Customer::find($id)->delete();
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
            $error_message = array('errorMsg' => 'The customer was not deleted. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();
    }

    public function getListByWarehouseClient()
    {
        $customer_model = new Customer();
        return $customer_model->getUserCustomerList();
    }
}
