<?php
namespace App\Enum;

class TransactionLogActivityType
{
    /* THESE CONSTANTS MUST MATCH THE VALUES IN THE TRANSACTION_LOG_ACTIVITY_TYPE TABLE */
    const create  = 1;
    const update  = 2;
    const convert = 3;
    const void    = 4;
    const close   = 5;

    public static function lists()
    {
        return [
            self::create  => self::create,
            self::update  => self::update,
            self::convert => self::convert,
            self::void    => self::void,
            self::close   => self::close
        ];
    }
}