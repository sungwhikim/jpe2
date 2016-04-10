<?php
namespace App\Models\Services;

interface TransactionInterface
{
    public function verifyPoNumber($type, $po_number);
}