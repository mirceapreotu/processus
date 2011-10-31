<?php

    use Processus\Bootstrap;
    use Application\JsonRpc\V1\Pub\Gateway;

    // #########################################################

    define('PATH_ROOT', '../../../../..');
    define('PATH_CORE', PATH_ROOT . '/library/Processus/core');
    define('PATH_APP', PATH_ROOT . '/application/php');
    define('PATH_PUBLIC', PATH_ROOT . '/htdocs');

    require_once (PATH_CORE . '/Bootstrap.php');
    Bootstrap::init();

    // #########################################################

    $gateway = new Gateway();
    $gateway->run();
?>