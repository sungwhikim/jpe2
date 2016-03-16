<?php
namespace App\Http\Controllers;

use App\Models\InventoryLog;

class InventoryLogController extends Controller
{
    protected $my_name = 'inventory_log';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = InventoryLog::orderBy('created_at')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/inventory_log');

        return response()->view('pages.inventory_log', ['main_data' => $data,
                                                        'url' => $url,
                                                        'my_name' => $this->my_name]);
    }
}
