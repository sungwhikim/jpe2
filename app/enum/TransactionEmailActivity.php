<?php
namespace App\Enum;

class TransactionEmailActivity
{
    const created   = 'created';
    const updated   = 'updated';
    const converted = 'converted';
    const voided    = 'voided';

    public static function lists()
    {
        return [
            self::created   => self::created,
            self::updated   => self::updated,
            self::converted => self::converted,
            self::voided    => self::voided
        ];
    }
}