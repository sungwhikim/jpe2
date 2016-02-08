SELECT 'INSERT INTO "province" (id, code, "name", country_id, created_at, updated_at)
        VALUES (' + CAST( id AS varchar(50) ) + ', ''' + code + ''',
                    ''' + [name] + ''',
                    1, date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP));'
FROM tblState
WHERE id < 14