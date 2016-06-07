<?php
namespace App\Http\Controllers;

class AsnReceiveController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'receive';
        $this->tx_type      = 'asn_receive';
        $this->tx_title     = 'ASN - Receiving';
        $this->tx_view      = 'pages.asn-receive';
    }
}
?>