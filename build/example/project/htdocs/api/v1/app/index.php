<?php

    use Processus\ProcessusBootstrap;
    use Application\JsonRpc\V1\App\Gateway;
    
    require_once ('../../../../library/Processus/core/Bootstrap.php');
    ProcessusBootstrap::init();
    
    $gtw = new Gateway();
    $gtw->run();

 ?>
    