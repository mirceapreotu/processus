#!/bin/bash
#
# Purpose: Strip out all require_once statements from Zend for improving
#          autoloader performance
#

cd `dirname $0`/../../src/contrib/Zend

find . -name '*.php' -not -wholename '*/Loader/Autoloader.php'          \
    -not -wholename '*/Application.php' -print0                         |
    xargs -0 sed --regexp-extended --in-place 's/(require_once)/\/\/ \1/g'

# eof    