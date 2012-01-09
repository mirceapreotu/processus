#!/bin/bash

TIMEOUT_LOOP=10
TIMEOUT_RESTART=50
TIMEOUT_FAILURE=300

BIN_PATH=`dirname $0`

echo "`date`;Starting worker shell loop wrapper"

while true
do
    echo "`date` Starting PHP worker instance"
    $BIN_PATH/run_task.php Queue_Worker -q default_svz -v 2>&1
    RC="$?"
    case "$RC" in
        0)
            echo "`date`;Worker loop success [code: 0], respawning"
            usleep 1
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