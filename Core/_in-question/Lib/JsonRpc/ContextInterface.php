<?php

/**
 * Lib_JsonRpc_ContextInterface
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
 * Lib_JsonRpc_ContextInterface
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
interface Lib_JsonRpc_ContextInterface
{
    /**
     * @abstract
     * @param Lib_JsonRpc_Server $server
     * @return void
     */
    function setServer(Lib_JsonRpc_Server $server);

    /**
     * @abstract
     * @return void
     */
    function getServer();



    /**
     * @abstract
     * @return void
     */
    function prepare();


    /**
     * @abstract
     * @param  mixed $result
     * @return void
     */
    function onResult($result);

    /**
     * @abstract
     * @param Exception $error
     * @return void
     */
    function onError(Exception $error);
}


