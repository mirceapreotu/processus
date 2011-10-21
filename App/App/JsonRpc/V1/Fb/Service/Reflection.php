<?php
/**
 * App_JsonRpc_V1_Fb_Service_Reflection
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Service_Reflection
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */
class App_JsonRpc_V1_Fb_Service_Reflection
    extends Lib_JsonRpc_Service_Reflection
{


     /**
     * @throws Exception
     * @param  $destination
     * @param null $type
     * @param null $apiVersion
     * @return object
     */
    public function getReflector($type=null, $apiVersion=null)
    {
        $destination = "Fb.Reflection.describe";
        $result = $this->_describeDestination($destination, $type, $apiVersion);
        return (object)$result;
    }
    
}