#!/bin/bash
#
# removes BOM from Utf8 files

sed -i '1 s/^\xef\xbb\xbf//' $1

#eof
