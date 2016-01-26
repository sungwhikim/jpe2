<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Country;
use App\Models\Province;

class WarehouseController extends Controller
{
    protected $my_name = 'warehouse';

    public function getList()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Warehouse::orderBy('short_name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/warehouse');

        return response()->view('pages.warehouse', ['main_data' => $data,
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
        return Warehouse::where('id', '=', $id)->get();
    }

    public function getByShortName($short_name)
    {
        return Warehouse::select('id')->where('short_name', 'ILIKE', $short_name)->take(1)->get();
    }

    public function getCheckDuplicate($short_name, $name)
    {
        return Warehouse::select('id')->where('short_name', 'ILIKE', $short_name)
                                      ->orWhere('name', 'ILIKE', $name)
                                      ->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables
        $short_name = request()->json('short_name');
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $warehouses = $this->getCheckDuplicate($short_name, $name);
        if( count($warehouses) > 0 )
        {
            $error_message = array('errorMsg' => 'The warehouse with short name of ' . $short_name . ' and name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $warehouse_id = $this->saveItem();

        return response()->json(['id' => $warehouse_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $warehouse = ( !empty(request()->json('id')) ) ? Warehouse::find(request()->json('id')) : new Warehouse();
        $warehouse->short_name  = request()->json('short_name');
        $warehouse->name        = request()->json('name');
        $warehouse->address1    = request()->json('address1');
        $warehouse->address2    = request()->json('address2');
        $warehouse->city        = request()->json('city');
        $warehouse->postal_code = request()->json('postal_code');
        $warehouse->province_id = request()->json('province_id');
        $warehouse->country_id  = request()->json('country_id');
        $warehouse->save();

        return $warehouse->id;
    }

    public function putDelete($id)
    {
        Warehouse::find($id)->delete();

        return Warehouse::all();
    }
}
?>