<?php

    namespace Application\JsonRpc\V1\Admin
    {
        use Processus\Abstracts\JsonRpc\AbstractJsonRpcServer;

        /**
         *
         */
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