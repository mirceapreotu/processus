<?php

/**
 * Lib_Io_JsonRpc_Context
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
 * Lib_Io_JsonRpc_Context
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
class Lib_Io_JsonRpc_Context implements Lib_Io_JsonRpc_ContextInterface
{
    
    
    /**
     *
     * @var Lib_Io_JsonRpc_Server 
     */
    protected $_server;

    

    /**
     *
     * @param Lib_Io_JsonRpc_Server $server 
     */
    public function setServer(Lib_Io_JsonRpc_Server $server)
    {
        $this->_server = $server;
    }
    
    /**
     *
     * @return Lib_Io_JsonRpc_Server 
     */
    public function getServer()
    {
        return $this->_server;
    }


    
    
    
    
    
}


