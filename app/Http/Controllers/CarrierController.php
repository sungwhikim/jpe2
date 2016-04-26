<?php

namespace App\Http\Controllers;

use App\Models\AsnReceive;
use App\Models\Carrier;
use App\Models\Receive;
use App\Models\Warehouse;
use App\Models\Client;
use App\Models\CarrierClientWarehouse;
use DB;
use Log;

class CarrierController extends Controller
{
    protected $my_name = 'carrier';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Carrier::orderBy('name')->get();

        //add warehouses and clients to each carrier - this is slow, but carriers are not used very often and there
        //are not that many carriers.
        foreach( $data as $item )
        {
            $item->client_warehouse = CarrierClientWarehouse::
                                        select('carrier_id', 'client.id as client_id', 'client.short_name AS client_name',
                                               'warehouse.id as warehouse_id', 'warehouse.name AS warehouse_name', 'ship', 'receive')
                                        ->join('client', 'carrier_client_warehouse.client_id', '=', 'client.id')
                                        ->join('warehouse', 'carrier_client_warehouse.warehouse_id', '=', 'warehouse.id')
                                        ->where('carrier_id', '=', $item->id)
                                        ->orderBy('warehouse.name')
                                        ->orderBy('client.name')
                                        ->get()->toArray();
        }

        //we need to send the url to do Ajax queries back here
        $url = url('/carrier');

        //get the data lists
        $warehouse_data = Warehouse::select('id', 'name', 'active')->orderBy('name')->get();
        $client_data = Client::select('id', 'short_name', 'name', 'active')->orderBy('name')->get();

        return view('pages.carrier', ['main_data' => $data,
                                      'url' => $url,
                                      'my_name' => $this->my_name,
                                      'warehouse_data' => $warehouse_data,
                                      'client_data' => $client_data]);
    }

    public function getListByUserClientWarehouse($type)
    {
        $carrier = new Carrier();
        return $carrier->getUserCarrierList('receive');
    }

    public function getCheckDuplicate($name)
    {
        $carriers = Carrier::where('name', 'ILIKE', $name)->get();

        return $carriers;
    }

    public function postNew()
    {
        //set carrier name to a variable
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $carriers = $this->getCheckDuplicate($name);
        if( count($carriers) > 0 )
        {
            $error_message = array('errorMsg' => 'The Carrier ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $carrier_id = $this->saveItem();

        return response()->json(['id' => $carrier_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            $carrier = ( !empty(request()->json('id')) ) ? Carrier::find(request()->json('id')) : new Carrier();
            $carrier->name = request()->json('name');
            $carrier->active = ( !empty(request()->json('active')) ) ? true : false;
            $carrier->save();

            /* Update client warehouse */
            //delete all current data
            CarrierClientWarehouse::where('carrier_id', '=', $carrier->id)->delete();

            //merge both lists and add
            $items = request()->json('client_warehouse_new', []);
            $items = array_merge($items, request()->json('client_warehouse', []));

            foreach( $items as $item )
            {
                $object = new CarrierClientWarehouse();
                $object->carrier_id = $carrier->id;
                $object->warehouse_id = $item['warehouse_id'];
                $object->client_id = $item['client_id'];
                $object->ship = $item['ship'];
                $object->receive = $item['receive'];
                $object->save();
            }
        }
        catch(\Exception $e)
        {
            //rollback since something failed
            DB::rollback();

            /* MAKE SURE THE LOG IS BEING WRITTEN CORRECTLY IN PRODUCTION MODE */
            //log error so we can trace it if need be later
            Log::info(auth()->user());
            Log::error($e);

            //set error message.  Don't send verbose error if not in debug mode
            $err_msg = ( env('APP_DEBUG') === true ) ? $e->getMessage() : 'SQL error. Please try again or report the issue to the admin.';

            //send back error
            $error_message = array('errorMsg' => 'The carrier was not updated with the error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return $carrier->id;
    }

    public function putDelete($id)
    {
        /* check to see if this carrier has been used, if so, it cannot be deleted.  The database constraint will
           prevent it too, but this is friendlier than a general server error. */
        $asn_receive = AsnReceive::select('id')->where('carrier_id', '=', $id)->get();
        $receive = Receive::select('id')->where('carrier_id', '=', $id)->get();

        //usage in transaction found
        if( count($asn_receive) > 0 || count($receive) > 0 )
        {
            $error_message = array('errorMsg' => 'This carrier cannot be deleted as it has been used in a transaction.
                                                  Please set it to inactive instead if you do not want it to show up in lists.');
            return response()->json($error_message);
        }

        //we can delete it since usage was not found.  The else statement is redundant but is there for clarity.
        else
        {
            Carrier::find($id)->delete();
        }
    }
}