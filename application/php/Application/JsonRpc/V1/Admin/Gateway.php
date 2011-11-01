<?php

    namespace Application\JsonRpc\V1\Admin
    {
        use Processus\Abstracts\JsonRpc\AbstractJsonRpcGateway;

        class Gateway extends AbstractJsonRpcGateway
        {
            protected $_config = array(
                'enabled' => TRUE,
                'namespace' => __NAMESPACE__,
                'validDomains' => array(
                    'Admin'
                ),
            );

        }
    }

?>