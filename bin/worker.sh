#!/bin/bash
#
# worker.sh
#
# a shell script that keeps looping until an exit code is given
# if it's does an exit(0), restart after a second - or if it's a declared error
# if we've restarted in a planned fashion, we don't bother with any pause
# and for one particular code, exit the script entirely.
# The numbers 0, 1, 2 must match what is returned from the PHP script

TIMEOUT_LOOP=1s
TIMEOUT_RESTART=5s
TIMEOUT_FAILURE=30s

BIN_PATH=`dirname $0`

echo "`date`;Starting worker shell loop wrapper"

while true
do
    echo "`date` Starting PHP worker instance"
    $BIN_PATH/run_task.php Queue_Worker -v 2>&1
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