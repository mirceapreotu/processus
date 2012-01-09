<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 12/20/11
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */

print_r("=== Init Bootstrap ===" . PHP_EOL);

$processusCorePath = dirname(__FILE__) . "/../library/Processus/core/";
$applicationPath   = dirname(__FILE__) . '/../application/php/Application/';

print_r("=== After define paths ===" . PHP_EOL);

//############################################################################################
// Processus Dependecies
//############################################################################################

print_r("=== Includes ===" . PHP_EOL);

include_once($processusCorePath . 'Interfaces/InterfaceBootstrap.php');
include_once($processusCorePath . 'Interfaces/InterfaceApplicationContext.php');
include_once($processusCorePath . 'ProcessusBootstrap.php');
include_once($processusCorePath . 'ProcessusContext.php');

print_r("=== Before require ApplicationBootstrap ===" . PHP_EOL);

require_once($applicationPath . 'ApplicationBootstrap.php');

print_r("=== After require ApplicationBootstrap ===" . PHP_EOL);

\Application\ApplicationBootstrap::getInstance()->init();

print_r("=== After init ApplicationBootstrap ===" . PHP_EOL);

//############################################################################################
// Processus Dependecies
//############################################################################################

?>