<?php

/**
 * Lib_JsonRpc_CommandHandler
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
 * Lib_JsonRpc_CommandHandler
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
class Lib_JsonRpc_CommandHandler
{
/*
    const COMMAND_TYPE_RUN = "run";
    const COMMAND_TYPE_DESCRIBE_API = "describeApi";
*/
    /**
     * @return array
     */
    /*
    public function getCommandsAvailable()
    {
        return array(
            self::COMMAND_TYPE_DESCRIBE_API,
        );
    }
*/
    /**
     * @var Lib_JsonRpc_Server
     */
    protected $_server;

    public function setServer(Lib_JsonRpc_Server $server)
    {
        $this->_server = $server;
    }

    /**
     * @return Lib_JsonRpc_Server
     */
    public function getServer()
    {
        return $this->_server;
    }


    /**
     * @return array
     */
    public function describeApi($type=null, $apiVersion=null)
    {
       
        $server = $this->getServer();
        $reflector = new Lib_JsonRpc_Reflector();
        $apiVersion = null; // autodetect

        $result = $reflector->describeApi($server, $type, $apiVersion);
        return $result;
    }




}


