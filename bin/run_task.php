#!/usr/bin/php -d memory_limit=536870912 -f
<?php

require dirname(__FILE__)."/../src/Bootstrap.php";
Bootstrap::init();

Lib_Task_Runner::run();