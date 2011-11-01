<?php

    use Processus\Bootstrap;
    use Application\JsonRpc\V1\Pub\Gateway;

    // #########################################################

    require_once ('../library/Processus/core/Bootstrap.php');
    Bootstrap::init();

    // #########################################################

    $fooController = new FooController();
    $fooController->index();

?>