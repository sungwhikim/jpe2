<?php
namespace App\Enum;

/**
 * Class InventoryLogType
 *
 * @package App\Enum
 *
 * NOTE: This is duplicated in the inventory_activity_type table.  We are using the enum as a way to
 * encapsulate it so the constants are more easily available, rather than using a normal constants include.
 */
class InventoryActivityType
{
    const MANUAL_EDIT         = 1; //manual edit to the database
    const INVENTORY_EDIT      = 2; //the inventory screen
    const BIN_TRANSFER        = 3;
    const ASN                 = 4;
    const CSR                 = 5;
    const RECEIVE             = 6;
    const SHIP                = 7;
    const WAREHOUSE_TRANSFER  = 8;
    const INITIAL_DATA_IMPORT = 9;

    public static function lists()
    {
        return [
            self::MANUAL_EDIT         => self::MANUAL_EDIT,
            self::INVENTORY_EDIT      => self::INVENTORY_EDIT,
            self::BIN_TRANSFER        => self::BIN_TRANSFER,
            self::ASN                 => self::ASN,
            self::CSR                 => self::CSR,
            self::RECEIVE             => self::RECEIVE,
            self::SHIP                => self::SHIP,
            self::WAREHOUSE_TRANSFER  => self::WAREHOUSE_TRANSFER,
            self::INITIAL_DATA_IMPORT => self::INITIAL_DATA_IMPORT
        ];
    }
}