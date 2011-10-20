<?php

/**
 * Lib_Io_JsonRpc_ServiceInterface
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
 * Lib_Io_JsonRpc_ServiceInterface
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
interface Lib_Io_JsonRpc_ServiceInterface
{
   
    /**
     * NOTICE: You must exclude that method from remote calls     * 
     * @return Lib_Io_JsonRpc_ContextInterface
     */
   function getContext();
   /**
    * NOTICE: You must exclude that method from remote calls
    * @param Lib_Io_JsonRpc_ContextInterface $context
    */
   function setContext(Lib_Io_JsonRpc_ContextInterface $context);
    
}


