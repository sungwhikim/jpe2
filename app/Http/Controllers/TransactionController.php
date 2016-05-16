<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\AsnReceive;
use App\Models\Receive;

use DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * This is the initial view for transaction finder
     *
     * @return View
     */
    public function getTransactionFinder()
    {
        //we need to send the url to do Ajax queries back here
        $url = url('/transaction');

        return response()->view('pages.transaction-finder', ['main_data' => $this->getTxFinderData('asn_receive'),
                                                             'url' => $url,
                                                             'tx_type' => 'asn_receive',
                                                             'my_name' => 'transaction_finder']);
    }

    /**
     * Temp function to get data for receiving and asn
     *
     */
    public function getTxFinderData($type)
    {
        if( $type == 'asn_receive' )
        {
            $asn_receive_data = AsnReceive::select(DB::raw("'asn_receive' AS tx_type"), 'tx_status.name AS tx_status', 'asn_receive.*')
                                          ->join('tx_status', 'asn_receive.tx_status_id', '=', 'tx_status.id')
                                          ->where('warehouse_id', '=', auth()->user()->current_warehouse_id)
                                          ->where('client_id', '=', auth()->user()->current_client_id)
                                          ->orderBy('tx_date', 'desc')->get();
            return $asn_receive_data;
        }

        if( $type == 'receive' )
        {
            $receive_data = Receive::select(DB::raw("'receive' AS tx_type"), 'tx_status.name AS tx_status', 'receive.*')
                                   ->join('tx_status', 'receive.tx_status_id', '=', 'tx_status.id')
                                   ->where('warehouse_id', '=', auth()->user()->current_warehouse_id)
                                   ->where('client_id', '=', auth()->user()->current_client_id)
                                   ->orderBy('tx_date', 'desc')->get();
            return $receive_data;
        }

        return false;
    }

    /**
     * Used in transaction finder to get transactions
     *
     * @return JSON
     */
    public function getFindByType(Request $request)
    {

    }

    /**
     * This is meant to be used for ajax calls to get the current product list for the chosen warehouse & client
     *
     * @return JSON
     */
    public function getUserProductList()
    {
        $product = new Product();
        return $product->getUserProductList();
    }

    /**
     * This is meant to be used for ajax calls to check if the po number is a duplicate
     *
     * @return JSON - True if not a duplicate
     */
    public function checkPoNumber($transaction_type, Request $request)
    {
        $transaction = new Transaction();
        $result['valid_po_number'] = $transaction->checkPoNumber($transaction_type, $request->json('warehouse_id'), $request->json('client_id'), $request->json('po_number'));

        return $result;
    }
}
