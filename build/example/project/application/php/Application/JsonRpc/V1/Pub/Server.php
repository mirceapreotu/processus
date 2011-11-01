<?php

    namespace Application\JsonRpc\V1\Pub
    {
        use Processus\Abstracts\JsonRpc\AbstractJsonRpcServer;

        class Server extends AbstractJsonRpcServer
        {
            protected $_config = array(
                'validClasses' => array(
                    'User',
                )
            );
        }
    }

?>