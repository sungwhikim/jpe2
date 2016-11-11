#!/bin/sh

pg_dump -d jpent_master -f jpent.dmp --clean --quote-all-identifiers