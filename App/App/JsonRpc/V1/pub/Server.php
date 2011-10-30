<?php

namespace App\JsonRpc\V1\Pub
{
    use Core\Abstracts\AbstractJsonRpcServer;

    /**
     *
     */
    class Server extends AbstractJsonRpcServer
    {
        protected $_config = array(
            'validMethods' => array(
                'listing'
            )
        );
    }
}

?>