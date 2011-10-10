<?php

include dirname(__FILE__) . "/../../../src/Bootstrap.php";
Bootstrap::init();

$application = App_Facebook_Application::getInstance();

$application->getManagerImageUpload()
        ->getServer()
        ->showPublicImageByHttpRequest((array)$_GET);

exit();

