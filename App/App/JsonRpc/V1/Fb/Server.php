<?php

/**
 * App_JsonRpc_V1_Fb_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_JsonRpc_V1_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_JsonRpc_V1_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

class App_JsonRpc_V1_Fb_Server extends Lib_JsonRpc_Server
{

    protected $_configDefault = array(

        "enabled" => true,
        "apiVersion" => array(
            "default" => 1,
        ),

        
        
        "context" => array(
            "class" => "App_JsonRpc_V1_Fb_Context",
        ),
        "classes" => array(
            // NO WILDCARDS HERE! SINCE WE NEED ServiceNames FOR describe Api

            // v1
            "App_JsonRpc_V1_Fb_Service_Reflection",
            "App_JsonRpc_V1_Fb_Service_Canvas",

            "App_JsonRpc_V1_Fb_Service_Facebook_Graph",
            "App_JsonRpc_V1_Fb_Service_Facebook_Application",

            "App_JsonRpc_V1_Fb_Service_Facebook_Pages_Events",
            "App_JsonRpc_V1_Fb_Service_Facebook_Pages_Admins",

            "App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Permissions",
            "App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Events",






            //"App_JsonRpc_V1_Fb_Service_User",
            // v2
        ),

        "destination" => array(
            "whitelist" => array(
                "Fb.*",
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

