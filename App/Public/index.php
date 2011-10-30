<?php

use Core\Bootstrap;
use App\Controller\FooController;

// #########################################################


define('PATH_ROOT', '/Users/fightbulc/www/crowdpark/rpc-new');
define('PATH_CORE', PATH_ROOT . '/Core');
define('PATH_APP', PATH_ROOT . '/App/App');
define('PATH_PUBLIC', PATH_ROOT . '/App/Public');

require_once (PATH_CORE . '/Bootstrap.php');
Bootstrap::init();

// #########################################################


$fooController = new FooController();
$fooController->index();

?>