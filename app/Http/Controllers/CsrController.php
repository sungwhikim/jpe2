<?php
namespace App\Http\Controllers;

class CsrController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'ship';
        $this->tx_type      = 'csr';
        $this->tx_title     = 'CSR - Client Stock Release';
        $this->tx_view      = 'pages.csr';
    }
}
?>