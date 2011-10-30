<?php

use Core\Bootstrap;
use App\JsonRpc\V1\Pub\Gateway;

// #########################################################


define('PATH_ROOT', '/Users/fightbulc/www/crowdpark/rpc-new');
define('PATH_CORE', PATH_ROOT . '/Core');
define('PATH_APP', PATH_ROOT . '/App/App');
define('PATH_PUBLIC', PATH_ROOT . '/App/Public');

require_once (PATH_CORE . '/Bootstrap.php');
Bootstrap::init();

// #########################################################


$gateway = new Gateway();
$gateway->run();

?>