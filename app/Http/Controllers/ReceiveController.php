<?php
namespace App\Http\Controllers;

use App\Models\Receive;
use App\Models\ReceiveDetail;
use App\Models\Product;
use App\Models\Carrier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enum\TxStatus;


class ReceiveController extends Controller
{
    protected $tx_direction = 'receive';
    protected $tx_type = 'receive';
    protected $transaction = null;
    protected $url;

    public function __construct()
    {
        $this->middleware('auth');
        $this->transaction = new Transaction();

        $this->url = url('/transaction/receive');
    }

    public function getIndex($id)
    {
        //get data
        $data = $this->transaction->getTransaction('receive', $id);


        //get list data initially here, rather than making an ajax call on init
        $list_data = $this->getListData();

        //set tx settings
        $txSetting = ['new' => false,
                      'edit' => true,
                      'direction' => $this->tx_direction,
                      'type' => $this->tx_type];

        return response()->view('pages.receive', ['main_data' => $data,
                                                  'url' => $this->url,
                                                  'product_data' => $list_data['product_data'],
                                                  'carrier_data' => $list_data['carrier_data'],
                                                  'tx_setting' => collect($txSetting)]);
    }

    public function getNew()
    {
        //get list data initially here, rather than making an ajax call on init
        $list_data = $this->getListData();

        //set tx settings
        $txSetting = ['new' => true,
                      'edit' => true,
                      'direction' => $this->tx_direction,
                      'type' => $this->tx_type];

        return response()->view('pages.receive', ['main_data' => collect(['items' => []]),
                                                  'url' => $this->url,
                                                  'product_data' => $list_data['product_data'],
                                                  'carrier_data' => $list_data['carrier_data'],
                                                  'tx_setting' => collect($txSetting)]);
    }

    protected function getListData()
    {
        //retrieve data for dropdown lists
        $product = new Product();
        $data['product_data'] = $product->getUserProductList();
        $carrier = new Carrier();
        $data['carrier_data'] = $carrier->getUserCarrierList('receive');

        return $data;
    }

    public function postNew(Request $request)
    {
        $result = $this->transaction->newReceiveTx($request);
        return $result;
    }

    public function postUpdate(Request $request)
    {
        return $this->transaction->updateAsn($request);
    }

    public function putCancel($id)
    {
        //AsnReceive::find($id)->delete();
    }
}
?>