<?php
namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\Inventory;
use App\Models\InventoryLog;
use Illuminate\Http\Request;

class BinController extends Controller
{
    protected $my_name = 'bin';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Bin::select('bin.*', 'province.name as province_name')
                          ->join('province', 'bin.province_id', '=', 'province.id')
                          ->orderBy('bin.name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/bin');

        //get country data
        $country = new Country();
        $country_data = $country->ListWithProvinces();

        return response()->view('pages.bin', ['main_data' => $data,
                                                    'url' => $url,
                                                    'my_name' => $this->my_name,
                                                    'country_data' => $country_data]);
    }

    public function getCheckDuplicate($product_id, $aisle, $section, $tier, $position)
    {
        return Bin::select('id')
                    ->where('product_id', '=', $product_id)
                    ->where('aisle', 'ILIKE', $aisle)
                    ->where('section', '=', $section)
                    ->where('tier', '=', $tier)
                    ->where('position', '=', $position)
                    ->take(1)->get();
    }

    public function postNew(Request $request)
    {
        //set to variables
        $product_id = request()->json('product_id');
        $aisle = request()->json('aisle');
        $section = request()->json('section');
        $tier = request()->json('tier');
        $position = request()->json('position');

        //validate inputs
        $this->validate($request, [
            'aisle' => 'required|max:2|min:2',
            'section' => 'required|integer|max:99',
            'tier' => 'required|integer|max:99',
            'position' => 'required|integer|max:99'
        ]);

        //check to make sure this is not a duplicate
        $bins = $this->getCheckDuplicate($product_id, $aisle, $section, $tier, $position);
        if( count($bins) > 0 )
        {
            $error_message = array('errorMsg' => 'The bin for this product already exists.');
            return response()->json($error_message);
        }

        //create new item
        $bin = $this->saveItem();

        return response()->json($bin);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $bin = ( !empty(request()->json('id')) ) ? Bin::find(request()->json('id')) : new Bin();
        $bin->product_id  = request()->json('product_id');
        $bin->aisle       = strtoupper(request()->json('aisle'));
        $bin->section     = request()->json('section');
        $bin->tier        = request()->json('tier');
        $bin->position    = request()->json('position');
        $bin->default     = false;
        $bin->active      = ( !empty(request()->json('active')) ) ? true : false;
        $bin->save();

        //set default bin
        if( !empty(request()->json('default')) && request()->json('default') === true )
        {
            //update all to not be default first
            Bin::where('product_id', '=', request()->json('product_id'))->update(['default' => false]);

            //set current one to be the default
            $bin->default = true;
            $bin->save();
        }

        return $bin;
    }

    public function putDelete($id)
    {
        //The bin cannot be deleted if it has ever been used.
        //It has been used if there is an entry in the inventory_log or inventory table.  The delete will fail anyways due to
        //foreign keys, but we want to check and warn the user with a friendly message.
        $result = Inventory::where('bin_id', '=', $id)->take(1)->get();
        $result_log = InventoryLog::where('bin_id', '=', $id)->take(1)->get();

        if( count($result) > 0 || count($result_log) > 0 )
        {
            $error_message = array('errorMsg' => 'This bin cannot be deleted as it has been used in a transaction.
                                                  Please set it to inactive instead if you do not want it to show up in lists.');
            return response()->json($error_message);
        }

        Bin::find($id)->delete();
    }
}
