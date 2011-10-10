<?php
/**
 * App_Facebook_Config
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Facebook_Config
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class App_Facebook_Config extends Lib_Facebook_Config
{

    protected $_configDefault = array(

        
        "app" => array(
            "id" => "APP_ID",//"229695050374540",
            "name" => "APP_NAME",
            "url" => "APP_URL",//"http://apps.facebook.com/sebiframetest",
            "profilePageUrl" => null,
            "siteName" => null,
            "siteUrl" => null,
        ),


       "api" => array(
            "apiKey" => "API_KEY",//"229695050374540",
            "apiSecret" => "API_SECRET",//"1b871f9f954b179a54353a67f6ebe754",
            "baseDomain" => null,
            "cookieSupportEnabled" => true,
            "fileUploadSupportEnabled" => null,
       ),

        "og" => array(

            "siteAuthor" => "meetidaaa",
            "siteDescription" => "meetidaaa",
            "siteFavicon" => null,//"OG_SITE_FAVICON",
            "siteImage" => null,//"OG_SITE_IMAGE",
            "siteKeywords" => "meetidaaa",
            "sitePublisher" => "http://www.meetidaaa.com",
            "siteTitle" => "meetidaaa",
            "siteType" => "game",
            "siteUrl" => "http://www.meetidaaa.com",

            
        ),

        "fanpage" => array(
            "id" => "FANPAGE_ID",
            "url" => "FANPAGE_URL",
            "tabUrl" => null,
            "likeRequired" => true,
        ),



        "login" => array(
            "scope" => null,
        ),


        "mvc" => array(
            "canvas" => array(

                "type" => "canvas",
                "contentUrl" => null,//$this->getSiteUrl().'canvas/index.php',
                // the nice url for external access
                "url" => null, //$this->getAppUrl(),
                "enabled" => true,//true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page.
                //  you must implement that in your view or controller)
                "isAuthRequired" => true, // true
            ),
            "tab" => array(

                "type" => "tab",//Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB,
                "contentUrl" => null, //$this->getSiteUrl().'canvas/tab.php',
                // the nice url for external access
                "url" => null, //$this->getFanPageTabUrl(),
                "enabled" => true, //true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page)
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page.
                //  you must implement that in your view or controller)
                "isAuthRequired" => true, // true
            ),
            "connect" => array(
                
                "type" => "connect", //Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
                "contentUrl" => null, //$this->getSiteUrl().'canvas/connect.php',
                // the nice url for external access
                "url" => null, //$this->getSiteUrl().'canvas/connect.php',
                "enabled" => null, //true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page.
                //  you must implement that in your view or controller)
                "isAuthRequired" => true, // true
            ),

        ),


    );





     /**
     * @return App_Facebook_Mock
     */
    public function getMock()
    {
        if (($this->_mock instanceof App_Facebook_Mock) !== true) {
            $this->_mock = new App_Facebook_Mock();
        }
        return $this->_mock;
    }



}
