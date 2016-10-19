<?php
namespace App\Enum;

class TransactionTypeName
{
    /* THESE CONSTANTS MUST MATCH THE VALUES IN THE TX_STATUS TABLE */
    const asn     = 'ASN - Advanced Shipping Notice';
    const receive = 'Receiving';
    const csr     = 'CSR - Client Stock Release';
    const ship    = 'Shipping';

    public static function lists()
    {
        return [
            self::asn     => self::asn,
            self::receive => self::receive,
            self::csr     => self::csr,
            self::ship    => self::ship
        ];
    }
}