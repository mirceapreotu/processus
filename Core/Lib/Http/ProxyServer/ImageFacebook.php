<?php
/**
 * Lib_Http_ProxyServer_ImageFacebook
 *
 * @package		Lib_Http_ProxyServer
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_Http_ProxyServer_ImageFacebook
 *
 * @package Lib_Http_ProxyServer
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Http_ProxyServer_ImageFacebook
    extends Lib_Http_ProxyServer_Image
{


    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(
        "httpClient" => array(
            "maxredirects" => 5,
            "strictredirects" => false,
            "useragent" => null,
            "timeout" => 10,
            "httpversion" => "1.1",
            "keepalive" => false,
        ),
        "domain" => array(
            "whitelist" => array(

                "*.facebook.com",
                "*.fbcdn.net",
            ),
            "blacklist" => array(

            ),
        ),
        "mimeType" => array(
            "whitelist" => array(
                "image/*",
            ),
            "blacklist" => array(

            ),
        ),
        "clientIP" => array(
            "whitelist" => array(

            ),
            "blacklist" => array(

            ),

        ),

    );




}
