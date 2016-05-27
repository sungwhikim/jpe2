<?php
namespace App\Http\Controllers;

use App\User;

use DB;

class TransactionFinderController extends Controller
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
        //use the last tx_type used.  If none set, then use ASN receive.
        $tx_type = ( !empty(auth()->user()->current_tx_finder_type) ) ? auth()->user()->current_tx_finder_type : 'asn_receive';

        return response()->view('pages.transaction-finder', ['main_data' => $this->getTxFinderData($tx_type),
                                                             'url' => url('/transaction'),
                                                             'tx_type' => $tx_type,
                                                             'my_name' => 'transaction_finder']);
    }

    /**
     * Get tx finder data
     *
     */
    public function getTxFinderData($type)
    {
        //main query
        $data = DB::table($type)
                  ->select(DB::raw("'" . $type . "' AS tx_type"), 'tx_status.name AS tx_status', $type . '.*')
                  ->join('tx_status', $type . '.tx_status_id', '=', 'tx_status.id')
                  ->where('warehouse_id', '=', auth()->user()->current_warehouse_id)
                  ->where('client_id', '=', auth()->user()->current_client_id)
                  ->orderBy('tx_date', 'desc');

        //date filter
        /* SET TO TWO YEARS FOR NOW - CHANGE TO ALLOW DATE RANGES LATER */
        $time = strtotime("-2 year", time());
        $date = date("Y-m-d", $time);
        $data->where('tx_date', '>=', $date);

        //update type
        $user = auth()->user();
        $user->current_tx_finder_type = $type;
        $user->save();

        //return data
        return collect($data->get());
    }
}
