<?php
namespace App\Http\Controllers;

class AsnShipController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'ship';
        $this->tx_type      = 'asn_ship';
        $this->tx_title     = 'ASN - Shipping';
        $this->tx_view      = 'pages.asn-ship';
    }
}
?>