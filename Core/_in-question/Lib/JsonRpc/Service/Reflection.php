<?php

/**
 * Lib_JsonRpc_Service_Reflection
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc_Service
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_JsonRpc_Service_Reflection
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc_Service
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_JsonRpc_Service_Reflection extends Lib_JsonRpc_Service
{



    /**
     * @throws Exception
     * @param null $type
     * @param null $apiVersion
     * @return array
     */
    public function describe($type = null, $apiVersion=null)
    {
        $result = $this->_describe($type, $apiVersion);
        return $result;
    }


    /**
     * @throws Exception
     * @param  $destination
     * @param null $type
     * @param null $apiVersion
     * @return array
     */
    public function describeDestination($destination, $type=null, $apiVersion=null)
    {
        $result = $this->_describeDestination($destination, $type, $apiVersion);
        return $result;
    }


    /**
     * @param null $type
     * @param null $apiVersion
     * @return array
     */
    protected function _describe($type=null, $apiVersion=null)
    {
        $server = $this->getContext()->getServer();
        $reflector = new Lib_JsonRpc_Reflector();
        $result = $reflector->describeApi($server, $type, $apiVersion);

        return $result;
    }


    /**
     * @param  string $destination
     * @param string $type
     * @param int $apiVersion
     * @return array
     */
    protected function _describeDestination(
        $destination,
        $type=null,
        $apiVersion=null
    )
    {
        $server = $this->getContext()->getServer();
        $reflector = new Lib_JsonRpc_Reflector();
        $result = $reflector->describeDestination(
            $server, $destination, $type, $apiVersion
        );

        return $result;
    }


   
}


