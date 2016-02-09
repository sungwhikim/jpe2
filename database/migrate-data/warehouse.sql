/* POSTGRESQL */

INSERT INTO warehouse (id, "name", care_of, address1, address2, city, province_id, country_id, postal_code,
                                                        active, created_at, updated_at)
  (SELECT wm.id,
     wm.name,
     wm.care_of,
     wm.address1,
     wm.address2,
     wm.city,
     p.id,
     p.country_id,
     wm.postal_code,
     wm.active,
     date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP)
   FROM warehouse_migrate wm
     JOIN province p
       ON wm.province = p.code );


CREATE TABLE "public"."warehouse_migrate" (
	"id" Integer NOT NULL,
	"name" Character Varying( 50 ) COLLATE "pg_catalog"."default" NOT NULL,
	"address1" Character Varying( 100 ) COLLATE "pg_catalog"."default" ,
	"address2" Character Varying( 100 ) COLLATE "pg_catalog"."default",
	"city" Character Varying( 50 ) COLLATE "pg_catalog"."default" ,
	"province" Character Varying( 50 ) COLLATE "pg_catalog"."default" ,
	"country_id" Integer DEFAULT '1' NOT NULL,
	"created_at" Timestamp Without Time Zone NOT NULL,
	"updated_at" Timestamp Without Time Zone,
	"postal_code" Character Varying( 2044 ) COLLATE "pg_catalog"."default" NOT NULL,
	"active" Boolean DEFAULT 'true' NOT NULL,
	PRIMARY KEY ( "id" ));

INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (46,
  'ADK',
  'c/o ADK',
  '15 North Queen St',
  '',
  'Etobicoke',
  'ON',
  'M8Z 6C1',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (48,
  'Chicago',
  'c/o Lagrou Distribution',
  '4124 South Racine Ave.',
  '',
  'Chicago',
  'IL',
  '60609',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (49,
  'Dallas',
  '',
  '1216 Trend Drive',
  '',
  'Carrolton',
  'TX',
  '75006',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (50,
  'LA',
  'c/o Physical Distribution Service',
  '2034 East 27th Street',
  '',
  'Vernon',
  'CA',
  '90058',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (51,
  'Legendary',
  '',
  '',
  '',
  'Mississauga',
  'ON',
  '',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (52,
  '1290',
  '',
  '1290 Blundell Rd.',
  '',
  'Mississauga',
  'ON',
  'L4Y 1M5',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (53,
  'Arrow',
  '',
  '77 Akron Rd',
  '',
  'Toronto',
  'ON',
  'M8W 1T3',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (55,
  'ADK2',
  '',
  '77 Akron Rd',
  '',
  'Toronto',
  'ON',
  'M8W 1T3',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (61,
  'NJ-ADI',
  '',
  '',
  '',
  'NJ',
  'NJ',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (63,
  'NJ EMP',
  '',
  '',
  '',
  'New Jersey',
  'NJ',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (64,
  'BC Delta',
  'c/o McKenna Logistics',
  '1645 Cliveden Ave.',
  '',
  'Delta',
  'BC',
  'V3M 6V5',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (65,
  'LA-ONeill',
  '',
  '',
  '',
  'La Mirada',
  'CA',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (66,
  'NJ Empire',
  'c/o Empire / Capelli New York',
  '250 Carter Drive',
  '',
  'Edison',
  'NJ',
  '08817',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (67,
  'LMD Oakland',
  '',
  '',
  '',
  'Oakland',
  'CA',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (68,
  'BC Classic',
  'c/o Classic Packaging',
  'Unit 100 1580 Brigantine Dr.',
  '',
  'Coquitlam',
  'BC',
  'V3K 7C1',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (69,
  'Boston',
  'c/o Tighe Logistics Group',
  '481 Wildwood Ave',
  '',
  'Woburn',
  'MA',
  '01801',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (70,
  'LMD NJ',
  '',
  '',
  '',
  '',
  'NJ',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (71,
  'NJ GMA',
  'GMA Accessories',
  '401 Washington Ave',
  '',
  'Carlstadt',
  'NJ',
  '07072',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (72,
  'Thompson Terminal',
  'JP Ent',
  '6690 Goreway Dr.',
  '',
  'Etobicoke',
  'ON',
  '',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (73,
  'CTT-CALGARY',
  'CTT - CALGARY',
  '',
  '',
  'Calgary',
  'AB',
  '',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (78,
  'TC-Dallas',
  '',
  '2828 Trade Center Dr',
  'Suite 100',
  'Carrollton',
  'TX',
  '75007',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (79,
  'COM-Dallas',
  '',
  '2840 Commodore Dr',
  'Suite 120',
  'Carrollton',
  'TX',
  '75007',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (80,
  'MTL',
  'JP Enterprises Brofort',
  '88 Boulevard Industriel',
  '',
  'Terrebonne',
  'QC',
  'J6Y 1V7',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (81,
  'LA GlobeCon',
  '',
  '',
  '',
  '',
  'CA',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (82,
  'Seattle EBT',
  '',
  '',
  '',
  '',
  'WA',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (83,
  'Memphis - UWT',
  '',
  '',
  '',
  'Memphis',
  'TN',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (84,
  'BC Airgroup',
  'Airgroup',
  '',
  '',
  '',
  'BC',
  '',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (85,
  'Chicago Airgroup',
  '',
  '',
  '',
  '',
  'IL',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (86,
  'DDS PA',
  '',
  'Chambersburg, PA',
  '',
  'Chambersburg',
  'PA',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (87,
  'Edm Portside',
  '',
  '',
  '',
  '',
  'AB',
  '',
  1,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));
INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                 postal_code, country_id,
                                 created_at, updated_at)
VALUES (88,
  'Houston Whse',
  'Distibution By Air',
  '3898 Distribution Blvd',
  '',
  'Houston',
  'TX',
  '',
  2,
  date_trunc('second', LOCALTIMESTAMP), date_trunc('second', LOCALTIMESTAMP));



 /* SQL SERVER */
 SELECT 'INSERT INTO "warehouse_migrate" (id, "name", care_of, address1, address2, city, province,
                                        postal_code, country_id,
                                        created_at, updated_at)
        VALUES (' + CAST( id AS varchar(50) ) + ',
                    ''' + Replace(whFullName, '''', '''''') + ''',
                    ''' + CASE WHEN whCO IS NOT NULL THEN Replace(whCO, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN whAddress1 IS NOT NULL THEN Replace(whAddress1, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN whAddress2 IS NOT NULL THEN Replace(whAddress2, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN whCity IS NOT NULL THEN Replace(whCity, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN whState IS NOT NULL THEN Replace(whState, '''', '''''')  ELSE '' END + ''',
                    ''' + CASE WHEN whZip IS NOT NULL THEN Replace(whZip, '''', '''''')  ELSE '' END + ''',
                    ' + CASE WHEN whCountryID = 1 THEN '2' ELSE '1' END + ',
                    date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP));' AS sql_text
FROM tblWarehouse