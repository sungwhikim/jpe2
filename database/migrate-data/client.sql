select * from tblClient

SELECT 'INSERT INTO "client_migrate" (id, short_name, "name", WebSite, address1, address2, city, province, country,
                                        postal_code, contact, phone, fax, email, active, billing_contact, billing_phone,
                                        billing_email, billing_currency, taxable, company_id, terms, invoice_attachment_type)
        VALUES (' + CAST( c.id AS varchar(50) ) + ',
                    ''' + Replace([ClientIDName], '''', '''''') + ''',
                    ''' + Replace(c.name, '''', '''''') + ''',
                    ''' + CASE WHEN Website IS NOT NULL THEN Replace(Website, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN address1 IS NOT NULL THEN Replace(address1, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN address2 IS NOT NULL THEN Replace(address2, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN city IS NOT NULL THEN Replace(city, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN province IS NOT NULL THEN Replace(province, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN country IS NOT NULL THEN Replace(country, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN PostalCode IS NOT NULL THEN Replace(PostalCode, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN contact IS NOT NULL THEN Replace(contact, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN phone IS NOT NULL THEN Replace(phone, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN fax IS NOT NULL THEN Replace(fax, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN email IS NOT NULL THEN Replace(email, '''', '''''')  ELSE '' END + ''',
                    ' + CASE WHEN IsActive = 1 THEN 'true' ELSE 'false' END + ',
                    ''' + CASE WHEN BillingContact IS NOT NULL THEN Replace(BillingContact, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN BillingPhone IS NOT NULL THEN Replace(BillingPhone, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN BillingEmail IS NOT NULL THEN Replace(BillingEmail, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN BillingCurrency IS NOT NULL THEN Replace(BillingCurrency, '''', '''''')  ELSE '' END + ''',
                    ' + CASE WHEN TaxFlag = 1 THEN 'true' ELSE 'false' END + ',
                    ' + CAST( CompanyID AS varchar(10) ) + ',
                    ''' + CASE WHEN Terms IS NOT NULL THEN Replace(Terms, '''', '''''')  ELSE '' END + ''',
                    ''' + LOWER(iat.name) + ''');'
FROM tblClient c
LEFT OUTER JOIN tblInvoiceAttachmentType iat
ON c.InvoiceAttachmentTypeID = iat.id

SELECT 'INSERT INTO "client_warehouse_migrate" (client_id, warehouse_id)
        VALUES (' + CAST( clientId AS varchar(50) ) + ',' +
       CAST( warehouseId AS varchar(50) ) +  + ');'
FROM tblClientWarehouse

INSERT INTO client_warehouse ( client_id, warehouse_id, created_at, updated_at)
(
  SELECT client_id,
  warehouse_id,
  date_trunc('second', LOCALTIMESTAMP),
  date_trunc('second', LOCALTIMESTAMP)
  FROM client_warehouse_migrate
);


/*---------------- POSTGRES ----------------------*/

     TRUNCATE TABLE client

INSERT INTO "client" (id, short_name, "name", WebSite, address1, address2, city, province_id, country_id,
                      postal_code, contact, phone, fax, email, active, billing_contact,
                      billing_email, billing_country_id, taxable, company_id, terms, invoice_attachment_type,
                      created_at, updated_at)
  (
    SELECT cm.id, short_name, cm.name, WebSite, address1, address2, city, p.id, c.id,
      postal_code, contact, phone, fax, email, active, billing_contact,
      billing_email,
      CASE WHEN c2.id IS NOT NULL THEN c2.id ELSE 1 END,
      taxable, company_id, terms, invoice_attachment_type,
      date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP)
    FROM client_migrate cm
      LEFT OUTER JOIN province p
        ON cm.province = p.code
      LEFT OUTER JOIN country c
        ON cm.country = c.code
      LEFT OUTER JOIN country c2
        ON cm.billing_currency = c2.currency_name
  );

select * from client

UPDATE client_migrate SET invoice_attachment_type = 'Excel'
WHERE invoice_attachment_type = 'EXCEL';

SELECT id, name, province, country FROM client_migrate WHERE char_length(province) > 2;

UPDATE client_migrate SET province = 'NY'
WHERE province ILIKE 'New York';

UPDATE client_migrate SET province = 'ON'
WHERE province ILIKE 'ontario';

UPDATE client_migrate SET province = 'QB'
WHERE province ILIKE 'Quebec%';

UPDATE client_migrate SET province = 'BC'
WHERE province ILIKE 'B.C.'
      OR province ILIKE 'British Columbia';

UPDATE client_migrate SET province = 'CA'
WHERE province ILIKE 'CA%'
      OR province ILIKE 'California';

UPDATE client_migrate SET province = 'IL'
WHERE province ILIKE 'Illinois%';

UPDATE client_migrate SET province = 'AZ'
WHERE province ILIKE 'ARIZONA%';

UPDATE client_migrate SET province = 'TX'
WHERE province ILIKE 'Texas%';

UPDATE client_migrate SET province = 'MB'
WHERE province ILIKE 'manitoba%';

UPDATE client_migrate SET province = 'MI'
WHERE province ILIKE 'MICHIGAN%';

SELECT DISTINCT country FROM client_migrate;

UPDATE client_migrate SET country = 'CA'
WHERE country ILIKE 'canada%'
      OR country ILIKE 'CA%';

UPDATE client_migrate SET country = 'US'
WHERE country ILIKE 'U%'
      OR  country ILIKE 'USA%'
      OR country ILIKE 'U.S.A%';