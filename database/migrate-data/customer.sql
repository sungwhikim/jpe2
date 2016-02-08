/* POSTGRESQL */
SELECT * FROM customer;

SELECT * FROM customer_migrate WHERE char_length(address2) > 100;

TRUNCATE TABLE customer;

INSERT INTO "customer" (id, "name", address1, address2, city, province_id, country_id,
                        email, contact, fax, phone, postal_code,
                        active, created_at, updated_at)
  (SELECT cm.id,
     cm.name,
     cm.address1,
     cm.address2,
     cm.city,
     p.id,
     p.country_id,
     cm.email,
     cm.contact,
     cm.fax,
     cm.phone,
     cm.postal_code,
     cm.active,
     date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP)
   FROM customer_migrate cm
     JOIN province p
       ON cm.province = p.code );



/* ----------------------------------- */
/*  SQL SERVER BELOW */
select * from tblCustomer

select distinct(clientid) from tblCustomer

select * from tblCustomer where clientid IS NULL

select * from tblCustomer WHERE len(city) > 50

select * from tblCustomer WHERE len(address2) > 100

SELECT 'INSERT INTO "customer_migrate" (id, "name", address1, address2, city, province,
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
FROM tblCustomer


SELECT * FROM tblCustomer WHERE LEN(province) > 2

SELECT DISTINCT(province) FROM tblCustomer WHERE LEN(province) > 2 ORDER BY province

SELECT * FROM tblState

SELECT tblCustomer.province, tblCustomer.name, s.name, s.code
FROM tblCustomer
  JOIN tblState s
    ON tblCustomer.province = s.name

UPDATE cust
SET cust.province = s.Code
FROM tblCustomer cust
  JOIN tblState s
    ON cust.province = s.name

UPDATE tblCustomer
SET City = 'Richmond'
WHERE id = 4636

UPDATE cust
SET cust.province = RTRIM(LTRIM(province))
FROM tblCustomer cust

UPDATE tblCustomer
SET province = 'AR'
WHERE province = 'AR TX'

UPDATE tblCustomer
SET province = 'BC'
WHERE province = 'BC, Canada'
      OR province = 'Birtish Colombia'
      OR province = 'British Colombia'
      OR province = 'Britsh Columbia'

UPDATE tblCustomer
SET province = 'CA'
WHERE province = 'CA 95363-8876'
      OR province = 'Callifornia'

UPDATE tblCustomer
SET province = 'CT'
WHERE province = 'CONNETICUT'

UPDATE tblCustomer
SET province = 'DC'
WHERE province = 'DISTRICT OF COLUMBIA'
      OR province = 'Washingon DC'
      OR province = 'Washington Dc'
      OR province = 'Washington, DC'

UPDATE tblCustomer
SET province = 'MA'
WHERE province = 'Maasachusetts'
      OR province = 'Massuchusettes'

UPDATE tblCustomer
SET province = 'NL'
WHERE province = 'Newfoundland'
      OR province = 'NFLD'

UPDATE tblCustomer
SET province = 'ON'
WHERE province = 'ONt'
      OR province = 'ONTARIO CANADA'
      OR province = 'Ontaro'

UPDATE tblCustomer
SET province = 'PE'
WHERE province = 'PEI'

UPDATE tblCustomer
SET province = 'QC'
WHERE province = 'Quebac'

UPDATE tblCustomer
SET province = 'SK'
WHERE province = 'Sask'
      OR province = 'Saskatoon'
      OR province = 'SK Canada'

UPDATE tblCustomer
SET province = 'TN'
WHERE province = 'Tennissee'
