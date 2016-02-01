SELECT * FROM "user"


/*SELECT 'INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
        VALUES (' + CAST( id AS varchar(50) ) + ', ''' + username + ''',
                    ''' + [name] + ''',
                    ''' + email + ''', date_trunc(''second'', LOCALTIMESTAMP), date_trunc(''second'', LOCALTIMESTAMP),
                    ' + CASE WHEN IsActive = 1 THEN 'true' ELSE 'false' END + ', ''placeholder'');'
FROM tblUser


----------------------*/

INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (11, 'dculbert',
        'Dave Culbert',
        'dculbert@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (7, 'Bay',
        'Master Client',
        'jim@larkinweb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (10, 'newuser',
        'New User',
        'jim@larkinweb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (12, 'cmathis',
        'Colette Mathis',
        'cmathis@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (15, 'joneill',
        'John ONeill',
        'jponeill@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (17, 'rmathis',
        'Rachel Mathis',
        'rmathis@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (19, 'dkerr',
        'Dave Kerr',
        'dkerr@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (22, 'lshafer',
        'Luke Shafer',
        'luke@flowercitytissue.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (23, 'ebaker',
        'Elizabeth Baker',
        'elizabeth@geandb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (24, 'cbishop',
        'Carmen Bishop',
        'carmen@superseal.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (28, 'jyu',
        'Joseph Yu',
        'joseph.yu@hbc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (29, 'kchu',
        'Kam Joi Chu',
        'kamjoi.chu@hbc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (31, 'cyan',
        'Claire Yan',
        'claire.yan@hbc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (32, 'lmorse',
        'Laura Morse',
        'lmorse@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (34, 'ikhan',
        'Imran Khan',
        'supplyline@istop.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (35, 'rob',
        'Rob',
        'jassupply@cogeco.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (37, 'mlash',
        'Marty Lash',
        'mlash@transco.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (38, 'glopackinc',
        'GLOPACKINC',
        'STse@glopackinc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (39, 'rlian',
        'Rena Lian',
        'RLian@glopackinc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (40, 'mlee',
        'Mary Lee',
        'mlee@bagempackaging.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (43, 'aporter',
        'Allison Porter',
        'aporter@sutus.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (44, 'smoy',
        'Stephanie Moy',
        'smoy@recochem.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (48, 'mbillings',
        'Michael Billings',
        'mbillings@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (49, 'bpajnigara',
        'Burzin Pajnigara',
        'pajnigara@hotmail.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (50, 'lstephenson',
        'Linda Stephenson',
        'linda@mereadesso.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (52, 'larkinjp',
        'Jim Larkin',
        'jim@larkinweb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (53, 'mhnuta',
        'Michael Hnuta',
        'mhnuta@bcimporters.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (54, 'jeliashevsky',
        'John Eliashevsky',
        'mungdahl@magma.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (55, 'vchan',
        'Vivian Chan',
        'vchan@bcimporters.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (57, 'charles',
        'Charles',
        'expressgreenpackaging@rogers.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (58, 'lisa',
        'Lisa Dell',
        'lisa.janmar@sympatico.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (64, 'rmiller',
        'Renee Miller',
        'rmiller@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (67, 'mps',
        'MPS',
        'mps@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (68, 'ldalgard',
        'Linda Dalgard',
        'linda@geandb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (69, 'jwildoner',
        'Jamie Wildoner',
        'jwildoner@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (70, 'swalker',
        'Stephanie Walker',
        'swalker@sierrahomeproducts.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (71, 'sbrown',
        'Stacie Brown',
        'sbrown@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (72, 'rparsons',
        'Robin Parsons',
        'rparsons@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (76, 'ghahn',
        'Greg Hahn',
        'greg.hahn@seastone.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (77, 'lgriep',
        'Lisa Griep',
        'lgriep@seastone.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (79, 'lgilman',
        'Lynne Gilman',
        'lgilman@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (80, 'kfilippone',
        'Kim Filippone',
        'kfilippone@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (81, 'malshaweesh',
        'Martha Alshaweesh',
        'ca_fulfillment@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (82, 'melqabbany',
        'Moustafa Elqabbany',
        'moustafa@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (84, 'nabushmais',
        'Nirmeen Abushmais',
        'nirmeen@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (86, 'sensient',
        'Sensient',
        'sdf.canada@sensient-tech.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (87, 'mrappaport',
        'Matthew Rappaport',
        'mrappaport@transco.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (88, 'larkin123',
        'Jim Larkin',
        'jim@larkinweb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (92, 'pgoodwin',
        'Paul Goodwin',
        'mg.sales@sympatico.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (94, 'rpaterson',
        'Rod Paterson',
        'info@aspaterson.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (95, 'ablake',
        'Arlene Blake',
        'ablake@aspaterson.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (98, 'ssharieha',
        'Safa Abu Sharieha',
        'ssharieha@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (99, 'cyoung',
        'Cody Young',
        'cody@carmensvacuum.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (100, 'ddurbin',
        'Drew Durbin',
        'drew@carmensvacuum.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (103, 'plim',
        'Pauline Lim',
        'pyuping@dongshengfoodsusa.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (106, 'mpsdist',
        'MPS Distributor',
        'mps@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (111, 'aalrabie',
        'Amina Alrabie',
        'amina@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (113, 'rmacphee',
        'Rob MacPhee',
        'csr@htgriffin.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (120, 'ccrews',
        'Chris Crews',
        'chrisc@eventivemarketing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (121, 'valerie',
        'Valerie Burke',
        'valerie.burke@organiccolorsystems.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (123, 'rleguillou',
        'Robert Leguillou',
        'robert.leguillou@sensient.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (125, 'turlock',
        'turlock',
        'sally.wiley@sensient.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (127, 'jcolburn',
        'James Colburn',
        'orders@pbwc.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (130, 'spadidar',
        'Sia Padidar',
        'cwm@intergate.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (131, 'jwhite',
        'Jessica White',
        'Jessica@carmensvacuum.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (132, 'dkamien',
        'Debbie Kamien',
        'dkamien@transco.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (137, 'jmartindill',
        'Jennifer Martindill',
        'services@sunraygroup.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (138, 'pkoon',
        'Prakash Koon',
        'prakash_koon_koon@bradycorp.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (139, 'seton',
        'Seton',
        'DMNAPurchasing@bradycorp.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (142, 'rgrossman',
        'Ryan Grossman',
        'ryan@maracaibodistribution.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (144, 'maxine',
        'Maxine',
        'max@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (145, 'pyung',
        'Pamela Yung',
        'pamela.yung1@pepsico.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (151, 'Laura',
        'Laura',
        'laura@organiccolorsystems.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (152, 'lflorent',
        'Louise Florent',
        'louise@florent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (153, 'cflorent',
        'Claude Florent',
        'claude@florent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (154, 'bkerr',
        'Brad Kerr',
        'brad@tactical-inc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (157, 'schippa',
        'Sabir Chippa',
        'impexinternational@sympatico.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (159, 'jpent',
        'Giselle Palumbo',
        'gpalumbo@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (161, 'nmeyer',
        'Nate Meyer',
        'nmeyer@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (162, 'eshneur',
        'Elana Shneur',
        'eshneur@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (163, 'dblack',
        'Danielle Black',
        'dblack@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (164, 'abrown',
        'Anne Brown',
        'abrown@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (165, 'ipc',
        'Ipc Orders',
        'ipcorders@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (166, 'food',
        'Scoular Food',
        'food@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (168, 'arahmey',
        'Albert Rahmey',
        'arahmey@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (169, 'kstoyer',
        'Keri Stoyer',
        'kstoyer@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (170, 'ysmith',
        'Yvonne Smith',
        'ysmith@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (171, 'kmooney',
        'Kris Mooney',
        'kmoone@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (172, 'mclemente',
        'Mark Clemente',
        'mclemente@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (173, 'avalencia',
        'Alta Valencia',
        'avalen@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (174, 'hluo',
        'Haijun Luo',
        'hluo@axiombrands.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (175, 'ykorner',
        'YellowKorner',
        'gc@yellowkorner.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (176, 'rkerr',
        'Robert Kerr',
        'rjkerr@sympatico.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (177, 'sharvest',
        'Season Harvest',
        'denise@seasonharvestfoods.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (178, 'marisol',
        'Marisol',
        'marisol@yellowkorner.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (179, 'psikorski',
        'Patti Sikorski',
        'patti.sikorski@hbc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (180, 'dalbarq',
        'Dalia Albarq',
        'dalia@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (181, 'mnaas',
        'Mehdi Naas',
        'mnaas@axiombrands.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (182, 'elisha',
        'Elisha',
        'cs@aspaterson.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (184, 'shussien',
        'Sumaya Hussien',
        'sumaya@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (185, 'mdoucette',
        'Melinda Doucette',
        'mdoucette@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (186, 'Amanda',
        'Amanda',
        'Amanda@organiccolorsystems.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (188, 'edweck',
        'Ezra Dweck',
        'ezra@daicompany.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (189, 'kmclaughlin',
        'Kevin Mclaughlin',
        'Kevin@golifeworks.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (191, 'emcgrath',
        'Euan McGrath',
        'euan.mcgrath@sympatico.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (192, 'gtawil',
        'George Tawil',
        'GeorgeT@gmaaccessories.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (193, 'mng',
        'Maggie Ng',
        'maggie@accutimewatch.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (194, 'mshama',
        'Marc Shama',
        'mshama@accutimewatch.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (195, 'rjenetti',
        'Robert Jenetti',
        'robert@gourmettrading.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (196, 'rstaple',
        'Roz Staple',
        'roz@smartrink.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (197, 'mthoson',
        'Malena Thoson',
        'mthoson@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (198, 'ealinas',
        'Erick Alinas',
        'npsisuppliesmgmt.external@hbc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (202, 'vgualtieri',
        'Victoria Gualtieri',
        'Victoria@avaises.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (203, 'dvilleda',
        'Daniel Villeda',
        'dvilleda@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (205, 'lvann',
        'Lee-Ann Vann',
        'leeannv@superiorfinishesinc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (206, 'jphillips',
        'Joann Phillips',
        'jphillips@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (207, 'rhansbrar',
        'Rani Hans-Brar',
        'rhansbrar@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (208, 'fshukayr',
        'Farah Shukayr',
        'farah.sh@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (209, 'ayeung',
        'Adam Yeung',
        'adam@giantairsupplies.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (210, 'dtatoy',
        'Denver Tatoy',
        'Denver.Tatoy@bionime.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (211, 'kpark',
        'Kim Park',
        'order@superiorfinishesinc.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (213, 'malejo',
        'Mary Lou Alejo',
        'marylou@jwlamerica.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (215, 'llosada',
        'Luis Losada',
        'llosada@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (216, 'gsuleiman',
        'Ghassan Suleiman',
        'ghassan@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (217, 'tdula',
        'Tiffany Dula',
        'tdula@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (218, 'jlee',
        'Justine Lee',
        'iconna@netvigator.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (219, 'aharrigan',
        'Amy Harrigan',
        'aharrigan@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (221, 'jramirez',
        'Jose Ramirez',
        'jramirez@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (222, 'ashgour',
        'Aliaa Shgour',
        'aliaa.sh@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (223, 'aburridge',
        'Andrea Burridge',
        'andrea@mereadesso.com', now(), now(),
        false, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (224, 'bellery',
        'Barbro Ellery',
        'barbro@mereadesso.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (228, 'gstratakos',
        'Gary Stratakos',
        'gary@neo-image.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (229, 'SUNGWHIKIM',
        'Sung Whi Kim',
        'sungwhikim@gmail.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (231, 'djaser',
        'Dania Jaser',
        'dania@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (232, 'aanandani',
        'Ash Anandani',
        'ash@jwlamerica.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (233, 'rdemko',
        'Rich Demko',
        'rdemko@jwlamerica.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (234, 'jrodriguez',
        'Juan Rodriguez',
        'Juanr@jwlamerica.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (235, 'ferani',
        'Fred Erani',
        'FErani@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (237, 'abrown2',
        'Ann Brown',
        'abrown@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (238, 'Ecom1',
        'Rino',
        'spadafina@rogers.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (241, 'thomson',
        'Thomson Group',
        'sshoemaker@thomsongroup.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (242, 'klai',
        'Kristi Lai',
        'chankristi@yahoo.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (243, 'SUNGWHIKIM1',
        'sung whi client',
        'sungwhikim@gmail.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (244, 'enauth',
        'Elisha Nauth',
        'cs@aspaterson.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (245, 'drailey',
        'Dominique Railey',
        'DRailey@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (246, 'shashash',
        'Sarah Hashash',
        'sarah.ha@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (247, 'msousa',
        'Margaret Sousa',
        'msousa@classicpkg.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (248, 'hobeidat',
        'Haneen Obeidat',
        'haneen@shukrclothing.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (249, 'kbarbour',
        'Kevin Barbour',
        'kbarbour@jpent.com', now(), now(),
        false, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (250, 'isasson',
        'Israel Sasson',
        'isasson@accutimewatch.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (251, 'nkarr',
        'Nicole Karr',
        'Accounts.receivable@rivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (252, 'mzaret',
        'Michele Zaret',
        'Michele.zaret@rivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (253, 'jstrnad',
        'Jim Strnad',
        'jstrnad@rivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (254, 'jmooney',
        'Jay Mooney',
        'jay@mooneysales.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (255, 'rhernandez',
        'Rosa Hernandez',
        'rosa@rainguard.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (256, 'mlessa',
        'Mike Lessa',
        'Customer.Service@rivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (257, 'kboudaeva',
        'Kristina Boudaeva',
        'kristina@kristinsgifts.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (258, 'kanderson',
        'Kyle Anderson',
        'kyle@mereadesso.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (260, 'bman',
        'Bliss Man',
        'bliss@monsterfactory.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (262, 'hreider',
        'Hayley Reider',
        'hreider@scoular.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (263, 'client',
        'Test Login',
        'test@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (264, 'vsoni',
        'Vridula Soni',
        'vsoni@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (265, 'jdriehorst',
        'Jack Driehorst',
        'jdriehorst@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (266, 'lshama',
        'Leon Shama',
        'lshama@accutimewatch.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (268, 'mimperato',
        'Mellissa Imperato',
        'mellissa.imperato@yahoo.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (269, 'ibbusa',
        'IBB User',
        'ibbwal@ibbusa.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (270, 'IndRivet',
        'IndRivet',
        'indrivet@indrivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (271, 'pgaskin',
        'Peter Gaskin',
        'peter@golifeworks.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (273, 'AceRiv',
        'Ace Rivet',
        'acerivet@acerivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (274, 'StepRiv',
        'Stephens Rivet',
        'stephens@rivet.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (278, 'tmyers',
        'Tonya Myers',
        'tmyers@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (279, 'jwalker',
        'Jayde Walker',
        'jwalker@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (280, 'GTranz',
        'Global Tranz',
        'scm@globaltranz.com', now(), now(),
        false, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (281, 'dparker',
        'Dee Parker',
        'dparker@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (282, '3pl',
        '3 PL Links',
        'dispatch@3pllinks.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (283, 'wrdisplay',
        'WR Display',
        'mattwalford@wrdisplay.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (284, 'nlau',
        'Natalie Lau',
        'natalielau@dongshengfoodsusa.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (285, 'mmissry',
        'Michelle Missry',
        'mmissry@accutimewatch.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (286, 'JPprod',
        'JP Enterprises',
        'estachulak@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (287, 'kristin',
        'Kristins Gifts',
        'info@kristinsgifts.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (288, 'dsomerville',
        'David Somerville',
        'dsomerville@sterlingmed.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (290, 'gburicea',
        'Gabby Buricea',
        'gburicea@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (291, 'kburton',
        'Kim Burton',
        'kimb@roneymk.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (292, 'lhuang',
        'Lulu Huang',
        'luluhuang@sakoura.net', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (293, 'ccampbell',
        'Christian Campbell',
        'christian@mereadesso.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (295, 'nwu',
        'Ning Wu',
        'ning.wu@tycheinternationalgroup.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (296, 'mking',
        'Mei King',
        'Mei.King@c-lifegroup.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (297, 'mcohen',
        'Morris Cohen',
        'morris.cohen@c-lifegroup.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (298, 'msalguero',
        'Manuela Salguero',
        'Manuela.SalgueroGarcia@xt-t.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (299, 'modani',
        'Modani',
        'iva@modani.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (301, 'jessica',
        'Jessica',
        'jessica@modani.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (302, 'dpellegrin',
        'David Pellegrin',
        'davidpellegrin@modani.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (303, 'snapav',
        'snapav',
        'admin@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (304, 'alfredo',
        'Alfredo',
        'Edi@golifeworks.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (305, 'ekoh',
        'Evon Koh',
        'evon.cpg@gmail.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (306, 'jcohen',
        'Joey Cohen',
        'joey@creativepetgroup.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (307, 'dpellegrin2',
        'David Pellegrin 2',
        'purchasing@modani.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (308, 'mkemp',
        'Mark Kemp',
        'mark.kemp@advpackaging.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (309, 'bmay',
        'Brian May',
        'brian.may@advpackaging.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (312, 'jhuang',
        'John Huang',
        'johnhuang@dongshengfoodsusa.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (313, 'aliu',
        'Ansel Liu',
        'ansel.liu@spaceways.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (314, 'ssondheimer',
        'Sebastian Sondheimer',
        'sebastian.sondheimer@spaceways.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (315, 'dfuchs',
        'David Fuchs',
        'david.fuchs@spaceways.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (316, 'hnjones',
        'Hal Neville-Jones',
        'hal.neville-jones@spaceways.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (318, 'dwilliams',
        'Donna Williams',
        'Donna.Williams@advpackaging.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (320, 'anita',
        'Anita Giri',
        'agiri@jpent.com', now(), now(),
        false, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (321, 'lpetrenko',
        'Lyuba Petrenko',
        'lyuba@neo-image.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (322, 'lle',
        'Lien Le',
        'cs@dongshengfoodsusa.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (323, 'dbisaccio',
        'Doreen Bisaccio',
        'doreen@us-group.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (324, 'rmcgiffin',
        'Rebecca McGiffin',
        'rebecca1@us-group.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (325, 'jquillen',
        'John Quillen',
        'jquillen@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (326, 'jgarcia',
        'Jocelynne Garcia',
        'whse@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (327, 'jmcginnis',
        'John  McGinnis',
        'jmcginnis@morbern.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (328, 'cwhitesell',
        'Chris Whitsell',
        'cwhitesell@morbern.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (329, 'shargett',
        'Susie Hargett',
        'shargett@morbern.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (331, 'reebok',
        'Reebok',
        'SBrown@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (332, 'dadams',
        'Diane Adams',
        'Diane.Adams@advpackaging.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (333, 'mchristie',
        'Meghan Christie',
        'mchristie@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (334, 'kmithcell',
        'Karen Mitchell',
        'sales@flowercitytissue.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (335, 'nfidler',
        'Nancy Fidler',
        'nancyf@hailiangusa.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (336, 'test',
        'test',
        '', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (337, 'dbuie',
        'Denise Buie',
        'denise@simplyorganicbeauty.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (338, 'kdonovan',
        'Kelly Donovan',
        'kelly@organiccolorsystems.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (339, 'mshaikh',
        'Mubbashir Shaikh',
        'magnus.inco@gmail.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (340, 'cparris',
        'Catherine Parris',
        'cparris@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (341, 'rb1',
        'Reebok Manger 1',
        'jim@larkinweb.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (342, 'lrobinson',
        'Lynn Robinson',
        'lrobinson@wrgtexas.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (343, 'cblouin',
        'Christina Blouin',
        'cblouin@jpent.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (344, 'bvanjahnke',
        'Bill Van Jahnke',
        'bill@goliathgames.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (345, 'cmugisha',
        'Christian Mugisha',
        'christian.mugisha@modani.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (346, 'Jwong',
        'Jean Wong',
        'jean@mereadesso.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (347, 'skane',
        'Shelly Kane',
        'skane@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (348, 'BVolk',
        'Brian Volk',
        'bill@mooneysales.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (349, 'rjohnson',
        'Ron Johnson',
        'ronjohnson@prestonwoodchristian.org', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (350, 'dtharpe',
        'Dan Tharpe',
        'dtharpe@prestonwoodchristian.org', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (351, 'Path',
        'Path Impact',
        'bill@mooneysales.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (352, 'ahawkins',
        'Amy Hawkins',
        'Amy.Hawkins@mg-dev.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (353, 'dspozarski',
        'Danielle Spozarski',
        'Danielle.Spozarski@mg-dev.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (354, 'boltsplus',
        'Bolts Plus',
        'bill@mooneysales.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (355, 'jpdrec',
        'Jacmel Rec',
        'JPDREC@jacmel.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (356, 'aalighazi',
        'Ashraf Alighazi',
        'info@dimesproducts.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (357, 'malighazi',
        'Maqsud Alighazi',
        'MA@dimesproducts.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (358, 'ameltz',
        'Adam Meltz',
        'adam@cantrio.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (359, 'advir',
        'Assaf Dvir',
        'assaf@cantrio.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (360, 'tsukraj',
        'Tracy Sukraj',
        'tracy@cantrio.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (362, 'fnestel',
        'Fred Nestel',
        'fred.nestel@scouting.org', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (363, 'smirkovic',
        'Sandra Mirkovic',
        'Sandra@bravoeducation.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (364, 'rmirkovic',
        'Rob Mirkovic',
        'Rob@bravoeducation.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (365, 'cosmic',
        'Cosmic Box',
        'cosmicbox@rogers.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (366, 'kinksology',
        'Kinksology',
        'kinkshairstudioandspa@gmail.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (367, 'mgillum',
        'Michelle Gillum',
        'MGillum@pak2000.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (368, 'Sagotoys',
        'Samson James Lee',
        'slee@sagosago.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (369, 'Sago1',
        'Nicole Sago',
        'sales@sagosago.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (370, 'tharrison',
        'Tray Harrison',
        'TrayHarr@aol.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (371, 'dsigouin',
        'Darren Sigouin',
        'darren@cupro.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (372, 'bryan',
        'Bryan Cantrio',
        'bryan@cantrio.ca', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (373, 'Sensient2',
        'Sensient 2',
        'sni.inventory@sensient.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (374, 'dmabee',
        'Dan Mabee',
        'dan.mabee@bcfoods.com', now(), now(),
        true, 'placeholder');
INSERT INTO "user" (id, username, "name", email, created_at, updated_at, active, password )
VALUES (375, 'dtrigiani',
        'David Trigiani',
        'davidtrigiani@sbcglobal.net', now(), now(),
        true, 'placeholder');


