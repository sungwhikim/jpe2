
SELECT 'INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
        VALUES (' + CAST( id AS varchar(50) ) + ', ''' + username + ''',
                    ''' + [name] + ''',
                    ''' + email + ''', date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP),
                    ' + CASE WHEN IsActive = 1 THEN 'true' ELSE 'false' END + ', ''placeholder'');'
FROM tblUser

SELECT 'INSERT INTO "customer" (id, name, "name", email, active, created_at, updated_at)
        VALUES (' + CAST( id AS varchar(50) ) + ', ''' + name + ''',
                    ''' + [name] + ''',
                    ''' + email + ''', date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP),
                    ' + CASE WHEN IsActive = 1 THEN 'true' ELSE 'false' END + ', ''placeholder'');'
FROM tblCustomer
