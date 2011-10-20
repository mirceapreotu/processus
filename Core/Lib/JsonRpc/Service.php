<?php

/**
 * Lib_JsonRpc_Service
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_JsonRpc_Service
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_JsonRpc_Service extends App_GaintS_Core_AbstractJsonRPCService implements Lib_JsonRpc_ServiceInterface
{
   
    /**
     *
     * @var Lib_JsonRpc_ContextInterface
     */
    protected $_context;
    
    /**
     *
     * @param Lib_JsonRpc_ContextInterface $context
     */
    public function __construct(Lib_JsonRpc_ContextInterface $context)
    {
        $this->setContext($context);
    }
    /**
     *
     * @return Lib_JsonRpc_ContextInterface
     */
    public function getContext()
    {
        return $this->_context;
    }
    /**
     *
     * @param Lib_JsonRpc_ContextInterface $context
     */
    public function setContext(Lib_JsonRpc_ContextInterface $context)
    {
        $this->_context = $context;
    }
}


