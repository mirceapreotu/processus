<?php

/**
 * Lib_Io_JsonRpc_GatewayInterface
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
 * Lib_Io_JsonRpc_GatewayInterface
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
interface Lib_Io_JsonRpc_GatewayInterface
{


    /**
     * @return boolean
     */
    function isDebugMode();


    /**
     * @abstract
     * @return void
     */
    function run();


}


