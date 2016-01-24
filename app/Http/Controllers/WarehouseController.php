<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function getList()
    {
        $warehouses = Warehouse::all();

        return $warehouses;
    }

    public function getItem($id)
    {
        $warehouse =  Warehouse::find($id);

        return $warehouse;
    }

    public function deleteItem($id)
    {
        $result = Warehouse::find($id)->delete();

        return $result;
    }

    public function addTestItem()
    {
        $warehouse = new Warehouse();

        $warehouse->short_name = '1290';
        $warehouse->name       = 'Miss';
        $warehouse->address1   = '123 any street';
        $warehouse->city       = 'Missasaga';
        $warehouse->province   = 'ON';
        $warehouse->country    = "CA";

        $warehouse->save();

        return $warehouse;
    }
}
?>