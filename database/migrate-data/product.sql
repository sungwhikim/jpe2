/* -!- ADD default_uom -!- */

SELECT 'INSERT INTO "product" (id, client_id, warehouse_id, product_type_id, sku, sku_client, "name", uom1, uom2, uom3, oversized_pallet, created_at, updated_at, active )
        VALUES (' + CAST( p.id AS varchar(50) ) + ', ' +
       CAST( p.clientId AS varchar(50) ) + ', ' +
       CAST( p.warehouseId AS varchar(50) ) + ', ' +
       CASE WHEN c.hasDlot = 1 THEN '2' ELSE '1' END + ',''' +
       Replace(p.ProductName, '''', '''''') + ''',''' +
       CASE WHEN p.SKU IS NOT NULL THEN Replace(p.SKU, '''', '''''')  ELSE '' END + ''',''' +
       CASE WHEN description IS NOT NULL THEN Replace(description, '''', '''''')  ELSE '' END + ''',' +
       '1,' + CAST(QtyPerCase AS varchar(50)) + ',' + CAST(CasePerPallet AS varchar(50)) + ',' +
       CASE WHEN p.IsOversizedPallet = 1 THEN 'true' ELSE 'false' END +
       ', ''' + CAST(CURRENT_TIMESTAMP AS varchar(100)) + ''',''' + CAST(CURRENT_TIMESTAMP AS varchar(100)) + ''',' +
       CASE WHEN p.IsActive = 1 THEN 'true' ELSE 'false' END + ');'
FROM tblProduct p
  INNER JOIN tblCLient c
    ON p.clientId = c.id