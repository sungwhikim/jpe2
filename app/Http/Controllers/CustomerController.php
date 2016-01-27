<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Country;
use App\Models\Province;

class CustomerController extends Controller
{
    protected $my_name = 'customer';

    public function getList()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Customer::orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/customer');

        return response()->view('pages.customer', ['main_data' => $data,
                                                    'url' => $url,
                                                    'my_name' => $this->my_name,
                                                    'country_data' => $this->getCountryList()]);
    }

    private function getCountryList()
    {
        //first get the list of countries
        $countries = Country::select('id', 'code', 'name')->orderBy('code')->get();

        //loop through and add the provinces
        foreach( $countries as $country )
        {
            $provinces = Province::select('id', 'code', 'name')->where('country_id', '=', $country->id)->get();
            $country->provinces = $provinces->toArray();
        }

        return $countries;
    }

    public function getById($id)
    {
        return Customer::where('id', '=', $id)->get();
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

        return Customer::all();
    }
}
