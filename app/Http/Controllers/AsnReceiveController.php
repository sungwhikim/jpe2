<?php
namespace App\Http\Controllers;

use App\Models\AsnReceive;
use App\Models\Product;
use App\Models\CarrierClientWarehouse;
//use App\Models\Services\TransactionService;

class AsnReceiveController extends Controller
{
    protected $my_name = 'asn_receive';
    protected $tx_type_name = 'ASN Receive';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex($asn_receive_id = null)
    {
        //place holder until data needs to be set
        $data = collect([]);

        //get product data initially here, rather than making another ajax call on init
        $product = new Product();
        $product_data = $product->getUserProductList();

        //set url
        $url = url('/asn/receive');

        return response()->view('pages.asn-receive', ['main_data' => $data,
                                                      'url' => $url,
                                                      'my_name' => $this->my_name,
                                                      'product_data' => $product_data,
                                                      'tx_type_name' => $this->tx_type_name]);
    }

    public function postSave()
    {
        /* DO DATA VALIDATION */



        return response()->json(['id' => $user_group_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $user_group = ( !empty(request()->json('id')) ) ? AsnReceive::find(request()->json('id')) : new AsnReceive();
        $user_group->name        = request()->json('name');
        $user_group->active      = ( !empty(request()->json('active')) ) ? true : false;
        $user_group->save();

        //delete all user functions before adding them back
        AsnReceiveToUserFunction::where('user_group_id', '=', $user_group->id)->delete();

        //process user functions
        if( request()->json('user_function_id') !== null )
        {
            foreach( request()->json('user_function_id') as $user_function_id )
            {
                //add them all back
                $user_group_to_user_function = new AsnReceiveToUserFunction();
                $user_group_to_user_function->user_group_id = $user_group->id;
                $user_group_to_user_function->user_function_id = $user_function_id;
                $user_group_to_user_function->save();
            }
        }

        return $user_group->id;
    }

    public function putCancel($id)
    {
        //AsnReceive::find($id)->delete();
    }
}
?>