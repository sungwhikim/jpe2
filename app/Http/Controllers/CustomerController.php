<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Country;
use App\Models\Warehouse;
use App\Models\Client;
use App\Models\CustomerClientWarehouse;

class CustomerController extends Controller
{
    protected $my_name = 'customer';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Customer::select('customer.*', 'province.name as province_name')
                          ->join('province', 'customer.province_id', '=', 'province.id')
                          ->orderBy('customer.name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/customer');

        //set lists
        $country = new Country();
        $country_data = $country->ListWithProvinces();

    /* FILTER DOWN BOTH LISTS TO ONLY WH AND CLIENT AVAILABLE TO THE LOGGED IN USER */
        $warehouse_data = Warehouse::select('id', 'name', 'active')->orderBy('name')->get();
        $client_data = Client::select('id', 'short_name', 'name', 'active')->orderBy('name')->get();

        return response()->view('pages.customer', ['main_data' => $data,
                                                    'url' => $url,
                                                    'my_name' => $this->my_name,
                                                    'country_data' => $country_data,
                                                    'warehouse_data' => $warehouse_data,
                                                    'client_data' => $client_data]);
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

        //create new item
        $customer_id = $this->saveItem();

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
        $customer->province_id = request()->json('province_id');
        $customer->country_id  = request()->json('country_id');
        $customer->active      = ( !empty(request()->json('active')) ) ? true : false;
        $customer->save();

        return $customer->id;
    }

    public function putDelete($id)
    {
        Customer::find($id)->delete();
    }
}
