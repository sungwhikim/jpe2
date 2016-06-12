<?php
namespace App\Http\Controllers;

class ShipController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'ship';
        $this->tx_type      = 'ship';
        $this->tx_title     = 'Shipping';
        $this->tx_view      = 'pages.ship';
    }
}
?>