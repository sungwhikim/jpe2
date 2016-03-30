SELECT 'INSERT INTO "carrier" (id, "name", active, created_at, updated_at)
    VALUES (' + CAST( id AS varchar(50) ) + ',''' + Replace(CarrierName, '''', '''''') + ''',true,
    date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP));'
FROM tblCarrier

SELECT 'INSERT INTO "carrier_client_warehouse" (carrier_id, client_id, warehouse_id, receive, ship, created_at, updated_at)
        VALUES (' + CAST( carrierId AS varchar(50) ) + ','
        + CAST( clientID AS varchar(50) ) + ','
        + CAST( warehouseID AS varchar(50) ) + ','
        + CASE WHEN isReceiving = 1 THEN 'true' ELSE 'false' END + ','
        + CASE WHEN isShipping = 1 THEN 'true' ELSE 'false' END +
        ', date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP));'
FROM tblClientCarrierWarehouse
WHERE warehouseId IS NOT NULL AND clientId IS NOT NULL