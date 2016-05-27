<?php
namespace App\Http\Controllers;

use App\Models\AsnReceive;
use App\Models\AsnReceiveDetail;
use App\Models\Product;
use App\Models\Carrier;
use App\Models\Receive;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enum\TxStatus;

//use App\Models\Services\TransactionService;

class AsnReceiveController extends TransactionController
{
    protected $tx_direction = 'receive';
    protected $tx_type = 'asn_receive';
    protected $tx_title = 'ASN - Receiving';
    protected $transaction = null;
    protected $url;

    public function __construct()
    {
        $this->middleware('auth');
        $this->transaction = new Transaction();

        $this->url = url('/transaction/asn/receive');
    }

    public function getIndex($asn_receive_id)
    {
        //get transaction data
        $tx_data = $this->transaction->getTransaction('asn_receive', $asn_receive_id);

        //get list data initially here, rather than making an ajax call on init
        $list_data = $this->getListData();

        //set edit mode
        $edit_mode = ( $tx_data->tx_status_id === 1 ) ? true : false;

        //set tx settings
        $txSetting = ['new' => false,
                      'edit' => $edit_mode,
                      'direction' => $this->tx_direction,
                      'type' => $this->tx_type];

        return response()->view('pages.asn-receive', ['main_data' => $tx_data,
                                                      'url' => $this->url,
                                                      'tx_title' => $this->tx_title,
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

        return response()->view('pages.asn-receive', ['main_data' => collect(['items' => []]),
                                                      'url' => $this->url,
                                                      'tx_title' => $this->tx_title,
                                                      'product_data' => $list_data['product_data'],
                                                      'carrier_data' => $list_data['carrier_data'],
                                                      'tx_setting' => collect($txSetting)]);
    }

    private function setViewData($new, $tx_id)
    {

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
        $result = $this->transaction->saveTx($request, $this->tx_type);
        return $result;
    }

    public function postUpdate(Request $request)
    {

    }

    public function putCancel($id)
    {
        //AsnReceive::find($id)->delete();
    }
}
?>