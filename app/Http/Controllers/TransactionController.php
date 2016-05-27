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
