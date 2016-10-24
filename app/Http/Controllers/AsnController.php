<?php
namespace App\Http\Controllers;

class AsnController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'receive';
        $this->tx_type      = 'asn';
        $this->tx_title     = 'ASN - Advanced Shipping Notice';
        $this->tx_view      = 'pages.asn';
    }
}