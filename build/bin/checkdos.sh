#!/bin/bash
#
# Detects files with CRLF endlings in the specified path (first argument)
# fix detected files via:
#     sed --in-place 's/^M$//'
# (press CTRL-v CTRL-m to get the special "^M" character!)

if test -z "$1"
then
    echo "Usage: $0 PATH_TO_SCAN"
    exit 1
fi

echo "Checking files in $1 with suffixes: css|js|php|html|xml ..."

FILES=`find "$1"  -type f \( -name '*.css'  -o -name '*.js'   -o -name '*.php'  -o -name '*.html' -o -name '*.xml' \) -exec echo -n {} \; -exec perl -p -e 'm/\r/ and print " X-CRLF-FOUND\n" and exit 1' {} \; |grep 'X-CRLF-FOUND' | sed -r -e 's/^(.*) .*$/\1 [CRLF]/'`
if test -n "$FILES"
then
    echo $FILES | xargs -n 2 echo
	echo "ERROR: Files with DOS line endings [CRLF] instead of Unix [LF] detected!"
	exit 1
else
	echo "OK - All files have correct LF Unix line endings."
	exit 0
fi

#eof
