<?php

/**
 * Lib_Io_JsonRpc_Gateway
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
 * Lib_Io_JsonRpc_Gateway
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
class Lib_Io_JsonRpc_Gateway implements Lib_Io_JsonRpc_GatewayInterface
{


    /**
     * if result is an array, you restrict access the the destinations defined
     * @return array|null
     */
    public function getAllowedDestinations()
    {
        $result = null;
        $debug = Bootstrap::getRegistry()->getDebug();

        if (
                ($debug->isDebugMode())
                || (($debug->isEnabled() && ($debug->isDeveloper())))
        ) {
            // allow access to all destinations
            return $result;
        }

        // restrict access to selected destinations

        $result = array(
           //e.g. "Canvas.getInitialData",
        );
         

        return $result;
    }

    /**
     * @return Lib_Io_JsonRpc_Server
     */
    public function newServer()
    {
        $server = new Lib_Io_JsonRpc_Server();
        $server->setGateway($this);
        return $server;
    }

    /**
     *
     * @return boolean
     */
    public function isDebugMode()
    {
        return false;
    }

    /**
     * @throws Exception
     * @return void
     */
    public function run()
    {
        throw new Exception("Implement in subclasses! " . __METHOD__);
    }


}


