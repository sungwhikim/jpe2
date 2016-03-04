<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Country;

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
        Warehouse::find($id)->delete();
    }
}
?>