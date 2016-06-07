<?php
namespace App\Enum;

class TxStatus
{
    /* THESE CONSTANTS MUST MATCH THE VALUES IN THE TX_STATUS TABLE */
    const active    = 1;
    const converted = 2;
    const voided    = 3;
    const closed    = 4;

    public static function lists()
    {
        return [
            self::active    => self::active,
            self::converted => self::converted,
            self::voided    => self::voided,
            self::closed    => self::closed
        ];
    }
}