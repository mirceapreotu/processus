<?php

/**
 * Lib_Io_JsonRpc_Service
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Io_JsonRpc_Service
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Io_JsonRpc_Service implements Lib_Io_JsonRpc_ServiceInterface
{
   
    /**
     *
     * @var Lib_Io_jsonRpc_Context
     */
    protected $_context;
    
    /**
     *
     * @param Lib_Io_JsonRpc_ContextInterface $context 
     */
    public function __construct(Lib_Io_JsonRpc_ContextInterface $context)
    {
        $this->setContext($context);
    }
    /**
     *
     * @return Lib_Io_jsonRpc_Context
     */
    public function getContext()
    {
        return $this->_context;
    }
    /**
     *
     * @param Lib_Io_JsonRpc_ContextInterface $context 
     */
    public function setContext(Lib_Io_JsonRpc_ContextInterface $context)
    {
        $this->_context = $context;
    }
}


