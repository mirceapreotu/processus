<?php

/**
 * App_JsonRpc_V1_Fb_Gateway
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
 * App_JsonRpc_V1_Fb_Gateway
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

class App_JsonRpc_V1_Fb_Gateway extends Lib_JsonRpc_Gateway
{


    // +++++++++++++ config +++++++++++++++++++++++++

    
    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(

        "enabled" => true,

        "batch" => array(
            "maxItems" => 100,
        ),
        "server" => array(
            "class" => "App_JsonRpc_V1_Fb_Server",
        ),

        "response" => array(
            "headers" => array(
                "Content-Type: application/json; charset=utf-8",
            ),
        ),

        "destination" => array(
            "whitelist" => array(
                "Fb.*"
            ),
            "blacklist" => array(
            ),
        ),

        "command" => array(
            "whitelist" => array(
                "*",//e.g. "describeApi"
            ),
            "blacklist" => array(

            ),
        ),

        "crypt" => array(
            "key" => "W3CXFpQHXLD2VZfMofKj", 
            "destination" => array(
                // which destinations require crypted requests?
                "requirelist" => array(
                    //e.g. "*"
                    //"*",
                    //"*",
                ),
                // which destinations do not require crypted requests?
                "ignorelist" => array(
                ),
            ),
        ),



    );




}

