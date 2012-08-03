<?php

$processusCorePath = '../../../../library/Processus/core/';
$applicationPath   = '../../../../application/php/Application/';

require_once($processusCorePath . 'Interfaces/InterfaceBootstrap.php');
require_once($processusCorePath . 'Interfaces/InterfaceApplicationContext.php');
require_once ($processusCorePath . 'ProcessusBootstrap.php');
require_once($applicationPath . 'ApplicationBootstrap.php');

$bootstrap = \Application\ApplicationBootstrap::getInstance();
$bootstrap->init();
$gtw = new Application\JsonRpc\V1\App\Gateway();
$bootstrap->setGateway($gtw);
$gtw->run();


?>
