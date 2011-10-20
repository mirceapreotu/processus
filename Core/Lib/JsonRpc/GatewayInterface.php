<?php

/**
 * Lib_JsonRpc_GatewayInterface
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
 * Lib_JsonRpc_GatewayInterface
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
interface Lib_JsonRpc_GatewayInterface
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

    /**
     * @abstract
     * @param  string $destination
     * @return bool
     */
    function isDestinationAllowed($destination);


}


