<?php

include dirname(__FILE__) . "/../../../src/Bootstrap.php";
Bootstrap::init();

//$gateway = new App_JsonRpc_Gateway_Cms();
$gateway = new App_JsonRpc_V1_Fb_Gateway();
$gateway->run();




exit();

