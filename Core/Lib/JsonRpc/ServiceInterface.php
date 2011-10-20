<?php

/**
 * Lib_JsonRpc_ServiceInterface
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
 * Lib_JsonRpc_ServiceInterface
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
interface Lib_JsonRpc_ServiceInterface
{
   
    /**
     * NOTICE: You must exclude that method from remote calls     * 
     * @return Lib_JsonRpc_ContextInterface
     */
   function getContext();
   /**
    * NOTICE: You must exclude that method from remote calls
    * @param Lib_JsonRpc_ContextInterface $context
    */
   function setContext(Lib_JsonRpc_ContextInterface $context);
    
}


