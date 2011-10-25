<?php

/**
 * Lib_JsonRpc_Context
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
 * Lib_JsonRpc_Context
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
class Lib_JsonRpc_Context implements Lib_JsonRpc_ContextInterface
{
    
    
    /**
     *
     * @var Lib_JsonRpc_Server
     */
    protected $_server;

    

    /**
     *
     * @param Lib_JsonRpc_Server $server
     */
    public function setServer(Lib_JsonRpc_Server $server)
    {
        $this->_server = $server;
    }
    
    /**
     *
     * @return Lib_JsonRpc_Server
     */
    public function getServer()
    {
        return $this->_server;
    }


    /**
     * 
     * @return void
     */
    public function prepare()
    {

    }
    
    /**
     * @param  $result
     * @return void
     */
    public function onResult($result)
    {
        
    }
    /**
     * @param Exception $error
     * @return void
     */
    public function onError(Exception $error)
    {

    }
    
}


