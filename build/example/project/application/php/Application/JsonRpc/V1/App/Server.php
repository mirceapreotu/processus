<?php

namespace Application\JsonRpc\V1\App
{
    class Server extends \Processus\Abstracts\JsonRpc\AbstractJsonRpcServer
    {
        protected $_config = array(
            'namespace'    => __NAMESPACE__,
            'validClasses' => array(
                'User'
            )
        );
    }
}

?>