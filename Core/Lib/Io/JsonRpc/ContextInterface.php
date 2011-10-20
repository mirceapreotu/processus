<?php

/**
 * Lib_Io_JsonRpc_ContextInterface
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
 * Lib_Io_JsonRpc_ContextInterface
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
interface Lib_Io_JsonRpc_ContextInterface
{
    /**
     * @abstract
     * @param Lib_Io_JsonRpc_Server $server
     * @return void
     */
    function setServer(Lib_Io_JsonRpc_Server $server);

    /**
     * @abstract
     * @return void
     */
    function getServer();
   
}


