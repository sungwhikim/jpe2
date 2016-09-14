<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Country;
use App\Models\Warehouse;
use App\Models\Client;
use App\Models\CustomerClientWarehouse;

use DB;
use Log;
use Exception;

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
        return CustomerClientWarehouse::select('customer_id', 'client.id as client_id', 'client.short_name AS client_name',
                                               'warehouse.id as warehouse_id', 'warehouse.name AS warehouse_name')
                                        ->join('client', 'customer_client_warehouse.client_id', '=', 'client.id')
                                        ->join('warehouse', 'customer_client_warehouse.warehouse_id', '=', 'warehouse.id')
                                        ->where('customer_id', '=', $customer_id)
                                        ->orderBy('warehouse.name')
                                        ->orderBy('client.name')
                                        ->get();
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
        
        //create new item
        $customer_id = $this->saveItem();

        return response()->json(['id' => $customer_id]);
    }

    public function postUpdate()
    {
        return $this->saveItem();
    }

    private function saveItem()
    {
        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
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
            $customer->province_id = request()->json('province_id');
            $customer->country_id  = request()->json('country_id');
            $customer->active      = ( !empty(request()->json('active')) ) ? true : false;
            $customer->save();
            
            //set customer id
            $customer_id = $customer->id;

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
            else
            {
                /* Update client warehouse */
                //delete all current data
                CustomerClientWarehouse::where('customer_id', '=', $customer_id)->delete();

                //merge both lists and add
                $items = request()->json('client_warehouse_new', []);
                $items = array_merge($items, request()->json('client_warehouse', []));

                foreach( $items as $item )
                {
                    $object = new CustomerClientWarehouse();
                    $object->customer_id = $customer->id;
                    $object->warehouse_id = $item['warehouse_id'];
                    $object->client_id = $item['client_id'];
                    $object->save();
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
            $error_message = ['errorMsg' => 'The customer was not saved. Error: ' . $err_msg];

            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return response()->json($customer_id);
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
