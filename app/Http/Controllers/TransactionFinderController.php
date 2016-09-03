<?php
namespace App\Http\Controllers;

use App\User;
use App\Enum\TxStatus;

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
        $tx_type = ( !empty(auth()->user()->tx_finder_tx_type) ) ? auth()->user()->tx_finder_tx_type : 'asn';

        //set the dates and statuses flags
        $show_all_dates = auth()->user()->tx_finder_show_all_dates;
        $show_all_status = auth()->user()->tx_finder_show_all_status;

        //set flags as array so it can be converted into json on the front end
        $show_flag = collect(['dates' => $show_all_dates, 'status' => $show_all_status]);

        return response()->view('pages.transaction-finder', ['main_data' => $this->getTxFinderData($tx_type, $show_all_dates, $show_all_status),
                                                             'url' => url('/transaction'),
                                                             'tx_type' => $tx_type,
                                                             'show_flag' => $show_flag,
                                                             'my_name' => 'transaction_finder']);
    }

    /**
     * Get tx finder data
     *
     */
    public function getTxFinderData($tx_type, $show_all_dates, $show_all_status)
    {
        //main query
        $data = DB::table($tx_type)
                  ->select(DB::raw("'" . $tx_type . "' AS tx_type"), 'tx_status.name AS tx_status', $tx_type . '.*')
                  ->join('tx_status', $tx_type . '.tx_status_id', '=', 'tx_status.id')
                  ->where('warehouse_id', '=', auth()->user()->current_warehouse_id)
                  ->where('client_id', '=', auth()->user()->current_client_id)
                  ->orderBy('tx_date', 'desc');

        //date filter - show 2 year worth of data unless all dates are selected
        if( filter_var($show_all_dates, FILTER_VALIDATE_BOOLEAN) === false )
        {
            $time = strtotime("-2 year", time());
            $date = date("Y-m-d", $time);
            $data->where('tx_date', '>=', $date);
        }

        //status filter.  Only show active unless all statuses are set to true
        if( filter_var($show_all_status, FILTER_VALIDATE_BOOLEAN) === false )
        {
            $data->where('tx_status_id', '=', TxStatus::active);
        }

        //update the selections
        $user = auth()->user();
        $user->tx_finder_tx_type = $tx_type;
        $user->tx_finder_show_all_dates = $show_all_dates;
        $user->tx_finder_show_all_status = $show_all_status;
        $user->save();

        //return data
        return collect($data->get());
    }
}
