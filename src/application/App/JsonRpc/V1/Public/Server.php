<?php

/**
 * App_JsonRpc_V1_App_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_JsonRpc_V1_App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_JsonRpc_V1_App_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_JsonRpc_V1_App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

class App_JsonRpc_V1_Public_Server extends Lib_JsonRpc_Server
{

    protected $_configDefault = array(

        "enabled" => true,
        "apiVersion" => array(
            "default" => 1,
        ),

        
        
        "context" => array(
            "class" => "App_JsonRpc_V1_Public_Context",
        ),
        "classes" => array(
            // NO WILDCARDS HERE! SINCE WE NEED ServiceNames FOR describe Api

            // v1
            "App_JsonRpc_V1_Public_Service_Reflection",
            "App_JsonRpc_V1_Public_Service_Cities",
            "App_JsonRpc_V1_Public_Service_Events",
            "App_JsonRpc_V1_Public_Service_Venues",
            "App_JsonRpc_V1_Public_Service_Filter",
            // v2
        ),

        "destination" => array(
            "whitelist" => array(
                "Public.*",
            ),
            "blacklist" => array(
            ),
        ),

        "command" => array(
            "whitelist" => array(
                "describeApi",
            ),
            "blacklist" => array(
                "getServer",
                "setServer",
            ),
        ),


        "crypt" => array(
            
            "destination" => array(
               // which destinations require crypted requests?
                "requirelist" => array(
                    //e.g. "*"
                ),
                // which destinations do not require crypted requests?
                "ignorelist" => array(
                ),
            ),
        ),





        "interfaces" => array(
            "Lib_JsonRpc_ServiceInterface",
        ),

        "methodQualifiedName" => array(
            "whitelist" => array(
                "*"
            ),
            "blacklist" => array(
                    "*::getContext",
                    "*::setContext",
            ),
        )


    );


    

}

