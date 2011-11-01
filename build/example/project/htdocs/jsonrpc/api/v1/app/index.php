<?php

    use Processus\Bootstrap;
    use Application\JsonRpc\V1\Admin\Gateway;

    // #########################################################

    require_once ('../../../../../library/Processus/core/Bootstrap.php');
    Bootstrap::init();

    // #########################################################

    $gateway = new Gateway();
    $gateway->run();
?>