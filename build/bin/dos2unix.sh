#!/bin/bash
#
# Change CRLF (dos) to Unix (LF)

sed -i 's/\r//' $1

#eof
