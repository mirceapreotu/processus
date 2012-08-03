#!/usr/bin/php -d memory_limit=536870912 -f

<?php
$processusCorePath = '../core/';
$applicationPath   = '../../../application/php/Application/';

require_once($processusCorePath . 'Interfaces/InterfaceBootstrap.php');
require_once($processusCorePath . 'ProcessusBootstrap.php');
require_once($applicationPath . 'ApplicationBootstrap.php');

\Application\ApplicationBootstrap::getInstance()->init("TASK");
\Processus\Task\Runner::run();
