<?php
namespace App\Enum;

class InventoryLogType
{
    const MANUAL_EDIT        = 'Manual Edit'; //manual edit to the database
    const INVENTORY_EDIT     = 'Inventory Edit'; //the inventory screen
    const BIN_TRANSFER       = 'Bin Transfer';
    const ASN_RECEIVE        = 'ASN Receiving';
    const ASN_SHIP           = 'ASN Shipping';
    const RECEIVE            = 'Receiving';
    const SHIP               = 'Shipping';
    const WAREHOUSE_TRANSFER = 'Warehouse Transfer';

    public static function lists()
    {
        return [
            self::MANUAL_EDIT        => self::MANUAL_EDIT,
            self::INVENTORY_EDIT     => self::INVENTORY_EDIT,
            self::BIN_TRANSFER       => self::BIN_TRANSFER,
            self::ASN_RECEIVE        => self::ASN_RECEIVE,
            self::ASN_SHIP           => self::ASN_SHIP,
            self::RECEIVE            => self::RECEIVE,
            self::SHIP               => self::SHIP,
            self::WAREHOUSE_TRANSFER => self::WAREHOUSE_TRANSFER
        ];
    }
}