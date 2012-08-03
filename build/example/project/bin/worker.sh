#!/bin/bash

TIMEOUT_LOOP=1s
TIMEOUT_RESTART=5s
TIMEOUT_FAILURE=30s

BIN_PATH=`dirname $0`

echo "`date`;Starting worker shell loop wrapper"

while true
do
    echo "`date` Starting PHP worker instance"
    $BIN_PATH/speedy.php SimpleWorker -v 2>&1
    RC="$?"
    case "$RC" in
        0)
            echo "`date`;Worker loop success [code: 0], respawning"
            sleep $TIMEOUT_LOOP
            ;;
        1)
            echo "`date`;Worker stopped, RESTART requested [code: 1]"
            sleep $TIMEOUT_RESTART
            ;;
        2)
            echo "`date`;Worker stopped, QUIT requested [code: 2]"
            exit
            ;;
        *)
            echo "`date`;Worker stopped with errors [code: $RC], sleep & respawn"
            sleep $TIMEOUT_FAILURE
            ;;
    esac

done