<?php

    use Processus\Bootstrap;
    use Application\JsonRpc\V1\App\Gateway;
    
    require_once ('../../../../library/Processus/core/Bootstrap.php');
    Bootstrap::init();
    
    $gtw = new Gateway();
    $gtw->run();

 ?>
    