<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Carrier;
use App\Models\Customer;
use App\Enum\TxStatus;

class TransactionController extends Controller
{
    protected $transaction_model;

    protected $tx_direction;
    protected $tx_type;
    protected $tx_title;
    protected $tx_view;

    public function __construct()
    {
        $this->middleware('auth');
        $this->transaction_model = new Transaction();

        /* INIT THESE VARIABLES IN THE CHILD CONSTRUCTOR */
        $this->tx_direction = '';
        $this->tx_type = '';
        $this->tx_title = '';
        $this->tx_view = '';
    }

    public function getIndex($tx_id)
    {
        //get data
        $tx_data = $this->transaction_model->getTransaction($this->tx_type, $tx_id);

        //set edit mode
        $edit_mode = ( $tx_data->tx_status_id === TxStatus::active ) ? true : false;

        //set tx settings
        $tx_setting = $this->getTxSettings($this->tx_type, $this->tx_direction, false, $edit_mode, false);

        //generate view
        return response()->view($this->tx_view, $this->buildViewData($tx_data, $tx_setting));
    }

    public function getNew()
    {
        //set tx settings
        $tx_setting = $this->getTxSettings($this->tx_type, $this->tx_direction, true, true, false);

        //generate view
        return response()->view($this->tx_view, $this->buildViewData(collect(['items' => []]), $tx_setting));
    }

    public function getConvert($tx_id)
    {
        //get data
        $tx_data = $this->transaction_model->getTransaction('asn_' . $this->tx_type, $tx_id, true);

        //set tx settings
        $tx_setting = $this->getTxSettings($this->tx_type, $this->tx_direction, true, true, true);

        //generate view
        return response()->view($this->tx_view, $this->buildViewData($tx_data, $tx_setting));
    }

    public function postNew(Request $request)
    {
        $result = $this->transaction_model->newTransaction($request, $this->tx_type);
        return $result;
    }

    public function postUpdate($tx_id, Request $request)
    {
        $result = $this->transaction_model->updateTransaction($request, $this->tx_type, $tx_id);
        return $result;
    }

    public function putVoid($tx_id)
    {
        $result = $this->transaction_model->voidTransaction($this->tx_type, $tx_id);
        return $result;
    }

    /**
     * This sets up the transaction settings array
     *
     * @param $tx_type
     * @param $direction
     * @param $new
     * @param $edit_mode
     * @param $convert
     *
     * @return array
     */
    public function getTxSettings($tx_type, $direction, $new, $edit_mode, $convert)
    {
        return ['new' => $new,
                'edit' => $edit_mode,
                'convert' => $convert,
                'direction' => $direction,
                'type' => $tx_type];
    }

    /**
     * This builds the list data needed to populate drop down lists in the transaction screens
     *
     * @param $direction
     *
     * @return array
     */
    public function getListData($direction)
    {
        //retrieve data for dropdown lists common to all
        $product = new Product();
        $data['product_data'] = $product->getUserProductList();
        $carrier = new Carrier();
        $data['carrier_data'] = $carrier->getUserCarrierList($direction);

        //if it is a shipping transaction, we need to also grab the customer list
        if( $direction = 'ship' )
        {
            $customer = new customer();
            $data['customer_data'] = $customer->getUserCustomerList();
        }

        return $data;
    }

    /**
     * This is used to build the view data array
     *
     * @param $tx_data
     * @param $tx_setting
     *
     * @return array
     */
    public function buildViewData($tx_data, $tx_setting)
    {
        //get list data
        $data = $this->getListData($this->tx_direction);

        //set the rest of the data
        $data['main_data'] = $tx_data;
        $data['tx_title'] = $this->tx_title;
        $data['tx_setting'] = collect($tx_setting);

        return $data;
    }

    /**
     * This is meant to be used for ajax calls to get the current product list for the chosen warehouse & client
     *
     * @return JSON
     */
    public function getUserProductList()
    {
        $product_model = new Product();
        return $product_model->getUserProductList();
    }

    /**
     * This is meant to be used for ajax calls to check if the po number is a duplicate
     *
     * @return JSON - True if not a duplicate
     */
    public function checkPoNumber($tx_type, Request $request)
    {
        $result['valid_po_number'] = $this->transaction_model->checkPoNumber($tx_type, $request->json('warehouse_id'),
            $request->json('client_id'), $request->json('po_number'));

        return $result;
    }
}
