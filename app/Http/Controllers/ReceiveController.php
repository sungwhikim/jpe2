<?php
namespace App\Http\Controllers;

class ReceiveController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'receive';
        $this->tx_type      = 'receive';
        $this->tx_title     = 'Receiving';
        $this->tx_view      = 'pages.receive';
    }
}
?>