
SELECT 'INSERT INTO "bin_migrate" (id, product_id, bin, default_bin, active)
        VALUES (' + CAST( b.id AS varchar(50) ) + ', ' +
       CAST( b.productId AS varchar(50) ) + ', ''' +
       b.bin + ''',' +
       CASE WHEN b.IsDefaultBin = 1 THEN 'true' ELSE 'false' END + ',' +
       CASE WHEN b.IsActive = 1 THEN 'true' ELSE 'false' END + ');'
FROM tblBin b

-----------------------------------

TRUNCATE TABLE bin CASCADE

SELECT *
FROM bin_migrate
WHERE TRIM( LEADING 'O' FROM split_part(bin, ';', 2)) = 'ND'
      OR    TRIM( LEADING 'O' FROM split_part(bin, ';', 3)) = 'ND'
      OR    TRIM( LEADING 'O' FROM split_part(bin, ';', 4)) = 'ND';

SELECT *
FROM bin
     SUBSTR(bin, STRPOS(bin, ';'))
WHERE LEFT(bin, 6) = 'ND;ND;';

UPDATE bin_migrate
SET bin = SUBSTR(bin, STRPOS(bin, ';') + 1)
WHERE LEFT(bin, 6) = 'ND;ND;';

UPDATE bin_migrate
SET bin = TRIM(LEADING ';' FROM bin);

INSERT INTO "bin" (id, product_id, aisle, section, tier, position, default_bin, active, created_at, updated_at)
     (
          SELECT
               bm.id,
               product_id,
               LEFT(split_part(bin, ';', 1), 2),
               CASE WHEN LENGTH(split_part(bin, ';', 2)) > 1 THEN CAST(TRIM( LEADING 'O' FROM split_part(bin, ';', 2)) AS INTEGER)
               ELSE CAST(split_part(bin, ';', 2) AS INTEGER) END,
               CASE WHEN LENGTH(split_part(bin, ';', 3)) > 1 THEN CAST(TRIM( LEADING 'O' FROM split_part(bin, ';', 3)) AS INTEGER)
               ELSE CAST(split_part(bin, ';', 3) AS INTEGER) END,
               CASE WHEN LENGTH(split_part(bin, ';', 4)) > 1 THEN CAST(TRIM( LEADING 'O' FROM split_part(bin, ';', 4)) AS INTEGER)
               ELSE CAST(split_part(bin, ';', 4) AS INTEGER) END,
               default_bin,
               bm.active,
               date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP)
          FROM bin_migrate bm
               INNER JOIN product p
                    ON bm.product_id = p.id
     );

SELECT setval('product_variant1_id_seq', (SELECT MAX(id) FROM bin));