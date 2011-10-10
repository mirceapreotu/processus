#!/bin/bash
######################################################################
#
# Alters php comments / phpdoc tags in source files
#
# The tags @copyright, @category and @license will be changed in
# all files with a ".php" suffix in the given path. Files will
# not be modified/touched, if they already contain the specified
# values.
#
# Usage:
#
#   setcopyright.sh PATH CATEGORY COPYRIGHT LICENSE
#
# Example:
#
#   setcopyright.sh src/application                                     \
#           "meetidaaa"                                                 \
#           "Copyright (c) 2010 meetidaaa GmbH (http://meetidaaa.de)"   \
#           "http://meetidaaa.de/license/default"
#
######################################################################

if test -z "$4"
then
    echo "Usage: SRCPATH CATEGORY COPYRIGHT LICENSE"
    exit 1
fi

SRCPATH="$1"
CATEGORY="$2"
COPYRIGHT="$3"
LICENSE="$4"

echo "SRCPATH=$SRCPATH"
echo "CATEGORY=$CATEGORY"
echo "COPYRIGHT=$COPYRIGHT"
echo "LICENSE=$LICENSE"


for FILE in `find "$1" -type f -name '*.php'`
do

    TMPFILE="/tmp/`basename $FILE`.$$"

    sed                                                                      \
        -r                                                                   \
        -e "s|^ \* @copyright[ \t]+.*\$| * @copyright\t$COPYRIGHT|"          \
        -e "s|^ \* @category[ \t]+.*\$| * @category\t$CATEGORY|"             \
        -e "s|^ \* @license[ \t]+.*\$| * @license\t\t$LICENSE|"              \
        "$FILE" >"$TMPFILE"

    if ! diff "$FILE" "$TMPFILE" 2>&1 >/dev/null
    then
        cp "$TMPFILE" "$FILE"
        echo "  Fixed $FILE"
    fi

    rm "$TMPFILE"
    
done


#eof
