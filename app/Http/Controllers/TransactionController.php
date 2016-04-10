<?php
namespace App\Http\Controllers;

use App\Models\Product;
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
}
