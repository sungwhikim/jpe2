<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Country;
use App\Models\UserClient;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\ClientWarehouse;
use App\Models\ProductType;
use App\User;

use DB;
use Log;
use Exception;

class ClientController extends Controller
{
    protected $my_name = 'client';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Client::select('client.*',
                               'province.name as province_name',
                               'company.name as company_name',
                                DB::raw('taxable::varchar(5)'))
                      ->leftJoin('province', 'client.province_id', '=', 'province.id')
                      ->leftJoin('company', 'client.company_id', '=', 'company.id')
                      ->orderBy('client.name')->get();

        //add warehouses to each client
        foreach( $data as $item )
        {
            $item->warehouses = ClientWarehouse::where('client_id', '=', $item->id)
                                                 ->lists('warehouse_id')->toArray();
        }

        //we need to send the url to do Ajax queries back here
        $url = url('/client');

        //build the list data
        $warehouse_data = Warehouse::select('id', 'name', 'active')->orderBy('name')->get();
        $company_data = Company::select('id', 'short_name', 'name')->orderBy('name')->get();
        $product_type_data = ProductType::select('id', 'name')->orderBy('name')->get();
        $country = new Country();
        $country_data = $country->ListWithProvinces();

        return response()->view('pages.client', ['main_data' => $data,
                                                 'url' => $url,
                                                 'my_name' => $this->my_name,
                                                 'country_data' => $country_data,
                                                 'company_data' => $company_data,
                                                 'warehouse_data' => $warehouse_data,
                                                 'product_type_data' => $product_type_data]);
    }

    public function getById($id)
    {
        return Client::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($name)
    {
        return Client::select('id')->where('name', 'ILIKE', $name)->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $clients = $this->getCheckDuplicate($name);
        if( count($clients) > 0 )
        {
            $error_message = array('errorMsg' => 'The client with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $client_id = $this->saveItem();

        /* add client for all admins */
        //get user model and call function to update warehouse/client list
        $user_model = new User();
        $user_model->addAllWarehouseClientAdmin(false, true);

        return response()->json(['id' => $client_id]);
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
            $client = ( !empty(request()->json('id')) ) ? Client::find(request()->json('id')) : new Client();
            $client->short_name      = request()->json('short_name');
            $client->name            = request()->json('name');
            $client->contact         = request()->json('contact');
            $client->email           = request()->json('email');
            $client->phone           = request()->json('phone');
            $client->fax             = request()->json('fax');
            $client->address1        = request()->json('address1');
            $client->address2        = request()->json('address2');
            $client->city            = request()->json('city');
            $client->postal_code     = request()->json('postal_code');
            $client->province_id     = request()->json('province_id');
            $client->country_id      = request()->json('country_id');
            $client->billing_contact = request()->json('billing_contact');
            $client->billing_email   = request()->json('billing_email');
            $client->terms           = request()->json('terms');
            $client->company_id      = request()->json('company_id');
            $client->billing_country_id = request()->json('billing_country_id');
            $client->taxable         = ( request()->json('taxable') == 'true' ) ? true : false;
            $client->active          = ( !empty(request()->json('active')) ) ? true : false;
            $client->fifo_lifo       = request()->json('fifo_lifo');
            $client->show_barcode_client    = ( request()->json('show_barcode_client') == 'true' ) ? true : false;
            $client->product_type_id = request()->json('product_type_id');
            $client->invoice_attachment_type = request()->json('invoice_attachment_type');
            $client->tx_email_csv    = ( request()->json('tx_email_csv') == 'true' ) ? true : false;
            $client->save();
            $client_id = $client->id;

            /* Update warehouses */
            //delete all current data
            ClientWarehouse::where('client_id', '=', $client_id)->delete();

            //add warehouses
            $warehouses = request()->json('warehouses', []);
            foreach( $warehouses as $key => $warehouse_id )
            {
                $object = new ClientWarehouse();
                $object->client_id = $client_id;
                $object->warehouse_id = $warehouse_id;
                $object->save();
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
            $error_message = array('errorMsg' => 'The client was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return $client_id;
    }

    public function putDelete($id)
    {
        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //delete from user client table
            UserClient::where('client_id', '=', $id)->delete();

            //delete warehouses
            ClientWarehouse::where('client_id', '=', $id)->delete();

            //delete client
            Client::find($id)->delete();
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
            $error_message = array('errorMsg' => 'The client was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return true;
    }
}
