SELECT 'INSERT INTO "inventory_migrate" (bin_id, quantity, receive_date, variant1_id)
        VALUES (' + CAST( bl.binId AS varchar(50) ) + ', ' +
       CAST( bld.quantity AS varchar(50) ) + ', ''' +
       CAST( bld.ReceiveDate AS varchar(100) ) + ''', ' +
       CASE WHEN dl.id IS NOT NULL THEN CAST(dl.id AS varchar(50)) ELSE 'NULL' END + ');'
FROM tblBinLot bl
  INNER JOIN tblBinLotDate bld
    ON bl.id = bld.binlotId
  LEFT OUTER JOIN tblDlot dl
    ON bl.dlotId = dl.id

INSERT INTO inventory (bin_id, quantity, receive_date, variant1_id, created_at, updated_at)
  (
    SELECT
      bin_id,
      quantity,
      CAST(receive_date AS TIMESTAMP),
      variant1_id,
      date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP)
    FROM inventory_migrate im
      INNER JOIN bin b
        ON im.bin_id = b.id
      INNER JOIN product p
        ON b.product_id = p.id
    WHERE p.active = TRUE
          AND   b.active = TRUE
          AND   im.quantity > 0
  );
