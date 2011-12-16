<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 12/14/11
 * Time: 11:53 AM
 * To change this template use File | Settings | File Templates.
 */

#!/usr/bin/php -d memory_limit=536870912 -f
require dirname(__FILE__)."/../src/Bootstrap.php";
Bootstrap::init(Bootstrap::MODE_TASK, Bootstrap::SYSTEM_SVZ);

Lib_Task_Runner::run();
