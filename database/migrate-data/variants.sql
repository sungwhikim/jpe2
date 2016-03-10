
SELECT 'INSERT INTO "variant1_migrate" (id, product_id, "name", active)
        VALUES (' + CAST( dl.id AS varchar(50) ) + ', ' +
       CAST( dl.productId AS varchar(50) ) + ', ''' +
       dl.Dlot + ''',' +
       CASE WHEN dl.IsActive = 1 THEN 'true' ELSE 'false' END + ');'
FROM tblDLot dl

INSERT INTO product_variant1 (id, product_id, "name", "value", active, created_at, updated_at)
  (
      SELECT
        vm.id,
        vm.product_id,
        'DLot',
        vm.name,
        vm.active,
        date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP)
      FROM variant1_migrate vm
        INNER JOIN product p
         ON vm.product_id = p.id
      WHERE vm.active = true
      AND   p.active = true
  )
