<?php

namespace App\JsonRpc\V1\Pub
{
    use Core\Abstracts\AbstractJsonRpcGateway;

    /**
     *
     */
    class Gateway extends AbstractJsonRpcGateway
    {

        protected $_config = array('enabled' => TRUE, 
        
        'namespace' => __NAMESPACE__, 
        
        'validDomains' => array('Pub'));
    }
}

?>