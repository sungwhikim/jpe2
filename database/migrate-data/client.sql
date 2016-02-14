select * from tblClient


select * from tblClient where clientid IS NULL

select * from tblClient WHERE len(city) > 50

select * from tblClient WHERE len(address2) > 100

SELECT 'INSERT INTO "client_migrate" (id, "name", address1, address2, city, province,
                                        email, contact, fax, phone, postal_code, client_name, client_id,
                                        active, created_at, updated_at)
        VALUES (' + CAST( id AS varchar(50) ) + ',
                    ''' + Replace([name], '''', '''''') + ''',
                    ''' + CASE WHEN address1 IS NOT NULL THEN Replace(address1, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN address2 IS NOT NULL THEN Replace(address2, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN city IS NOT NULL THEN Replace(city, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN province IS NOT NULL THEN Replace(province, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN email IS NOT NULL THEN Replace(email, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN contact IS NOT NULL THEN Replace(contact, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN fax IS NOT NULL THEN Replace(fax, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN phone IS NOT NULL THEN Replace(phone, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN PostalCode IS NOT NULL THEN Replace(PostalCode, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN clientname IS NOT NULL THEN Replace(clientname, '''', '''''')  ELSE '' END + ''',
                    ' + CAST( clientid AS varchar(50) ) + ',
                    ' + CASE WHEN IsActive = 1 THEN 'true' ELSE 'false' END + ',
                    date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP));' AS sql_text
FROM tblClient