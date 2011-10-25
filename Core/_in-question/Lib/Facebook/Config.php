<?php
/**
 * Lib_Facebook_Config Class
 *
 * @package Lib_Facebook
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Config
 *
 *
 * @package Lib_Facebook
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Config
{

    // FB ENV TYPE: WHAT KIND OF APP IS OUR CURRENT CONTEXT?

    const ENVIRONMENT_TYPE_CANVAS = "canvas";
    const ENVIRONMENT_TYPE_CONNECT = "connect";
    const ENVIRONMENT_TYPE_TAB = "tab";


     /**
     * @var Lib_Facebook_Mock
     */
    protected $_mock;


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
    * @var Zend_Config
    */
    protected $_config;
    /**
    * override in subclass or inject using Zend_Config
    * @var array
    */
    protected $_configDefault = array(

        "app" => array(
                    "id" => null,
                    "name" => null,
                    "url" => null,
                    "profilePageUrl" => null,
                    "siteName" => null,
                    "siteUrl" => null,
                ),

       "api" => array(

            "apiKey" => null,
            "apiSecret" => null,
            "baseDomain" => null,
            "cookieSupportEnabled" => null,
            "fileUploadSupportEnabled" => null,
       ),
        "og" => array(
            "siteAuthor" => "OG_SITE_AUTHOR",
            "siteDescription" => "OG_SITE_DESCRIPTION",
            "siteFavicon" => "OG_SITE_FAVICON",
            "siteImage" => "OG_SITE_IMAGE",
            "siteKeywords" => "OG_SITE_KEYWORDS",
            "sitePublisher" => "OG_SITE_PUBLISHER_URL",
            "siteTitle" => "OG_SITE_TITLE",
            "siteType" => "OG_SITE_TYPE",
            "siteUrl" => "OG_SITE_URL",
        ),

        "fanpage" => array(
            "id" => null,
            "url" => null,
            "tabUrl" => null,
            "likeRequired" => null,
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
                "enabled" => null,//true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page)
                "isAuthRequired" => null, // true
            ),
            "tab" => array(
                "type" => "tab",//Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB,
                "contentUrl" => null, //$this->getSiteUrl().'canvas/tab.php',
                // the nice url for external access
                "url" => null, //$this->getFanPageTabUrl(),
                "enabled" => null, //true,
                "isTeaser" => null, //false
                "teaserTarget" => null, // e.g. "canvas",
                "isAuthRequired" => null, // true
            ),
            "connect" => array(
                "type" => "connect", //Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
                "contentUrl" => null, //$this->getSiteUrl().'canvas/connect.php',
                // the nice url for external access
                "url" => null, //$this->getSiteUrl().'canvas/connect.php',
                "enabled" => null, //true,
                "isTeaser" => null, //false
                "teaserTarget" => null, // e.g. "canvas",
                "isAuthRequired" => null, // true
            ),

        ),


    );


    /**
    * @return Zend_Config
    */
    public function getConfig()
    {
       if (($this->_config instanceof Zend_Config) !== true) {
           $this->_parseConfig();
           if ($this->_config instanceof Zend_Config) {
               $this->_onConfigParsed();
           } else {
               throw new Exception(
                   "Method returns invalid result at ".__METHOD__
               );
           }
       }
       return $this->_config;
    }


    public function setConfig(Zend_Config $config)
    {
       $this->_config = $config;

    }



    /**
    * @return Zend_Config
    */
    public function getConfigDefault()
    {
       return new Zend_Config((array)$this->_configDefault, true);
    }



    /**
     * @return void
     */
    protected function _parseConfig()
    {
       $configDefault = $this->getConfigDefault();
    /*
       $zendConfig = Lib_Utils_Config_Parser::loadByClassName(
           get_class($this)
       );
    */
       //loadByClassOrSuperClasses($instance)
       $zendConfig = Lib_Utils_Config_Parser::loadByClassOrSuperClasses(
           $this
       );
       $config = Lib_Utils_Config_Parser::merge(
           array(
                $configDefault,
                $zendConfig
           )
       );

       $this->_config = $config;

       $this->_onConfigParsed();

    }


    /**
    * your hooks here
    * @return void
    */
    protected function _onConfigParsed()
    {


    }



    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @return Zend_Config
     */
    public function getApiConfig()
    {
        $config = $this->getConfig();
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config at ".__METHOD__);
        }

        $config = $config->api;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.api at ".__METHOD__);
        }

        return $config;
    }

    /**
    * @throws Exception
    * @return Zend_Config
    */
    public function getOgConfig()
    {
       $config = $this->getConfig();
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config at ".__METHOD__);
       }

       $config = $config->og;
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config.og at ".__METHOD__);
       }

       return $config;
    }

    /**
    * @throws Exception
    * @return Zend_Config
    */
    public function getFanpageConfig()
    {
       $config = $this->getConfig();
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config at ".__METHOD__);
       }

       $config = $config->fanpage;
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config.fanpage at ".__METHOD__);
       }

       return $config;
    }


    /**
    * @throws Exception
    * @return Zend_Config
    */
    public function getAppConfig()
    {
       $config = $this->getConfig();
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config at ".__METHOD__);
       }

       $config = $config->app;
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config.app at ".__METHOD__);
       }

       return $config;
    }

    /**
    * @throws Exception
    * @return Zend_Config
    */
    public function getLoginConfig()
    {
       $config = $this->getConfig();
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config at ".__METHOD__);
       }

       $config = $config->login;
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config.login at ".__METHOD__);
       }

       return $config;
    }

    /**
    * @throws Exception
    * @return Zend_Config
    */
    public function getMvcConfig()
    {
       $config = $this->getConfig();
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config at ".__METHOD__);
       }

       $config = $config->mvc;
       if (($config instanceof Zend_Config)!==true) {
           throw new Exception("Invalid config.mvc at ".__METHOD__);
       }

       return $config;
    }





    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @return Lib_Facebook_Mock
     */
    public function getMock()
    {
        if (($this->_mock instanceof Lib_Facebook_Mock) !== true) {
            $this->_mock = new Lib_Facebook_Mock();
        }
        return $this->_mock;
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    

	 // APP CONFIG, BE CAREFUL - YOU MAY FUCK UP EVERYTHING !
    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->getAppConfig()->id;
        //throw new Exception("Override in subclass ".__METHOD__);
        //return '113630448712169'; //see: FB Developer App Settings
    }
    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getApiConfig()->apiKey;
        //throw new Exception("Override in subclass ".__METHOD__);
        //return '004d1dfa22a32e97149eaa0438d33e94';
        //see: FB Developer App Settings
    }
    /**
     * @return string
     */
    public function getApiSecret()
    {
        return $this->getApiConfig()->apiSecret;
        //throw new Exception("Override in subclass ".__METHOD__);
        //return '60a12eacf2a0a32247177f6e08ddcef8';
        //see: FB Developer App Settings
    }


    /**
     * @return bool|null
     */
    public function getApiClientCookieSupportEnabled()
    {
        $result = $this->getApiConfig()->cookieSupportEnabled;
        if (($result === null)||(is_bool($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.api.cookieSupportEnabled at ".__METHOD__
        );
    }

    /**
     * @return bool|null
     */
    public function getApiClientFileUploadSupportEnabled()
    {
        $result = $this->getApiConfig()->fileUploadSupportEnabled;
        if (($result === null)||(is_bool($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.api.fileUploadSupportEnabled at ".__METHOD__
        );
    }

    /**
     * @return string|null
     */
    public function getApiClientBaseDomain()
    {
        $result = $this->getApiConfig()->fileUploadSupportEnabled;
        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.api.baseDomain at ".__METHOD__
        );
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    // OG META TAGS: THATS THE STUFF FOR header.php (opengraph meta tags)
    /**
     * @return string
     */
    public function getOgSiteTitle()
    {
        $result = $this->getOgConfig()->siteTitle;
        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteTitle at ".__METHOD__
        );
    }
    /**
     * @return string
     */
    public function getOgSiteUrl()
    {
        $result = $this->getOgConfig()->siteUrl;

        if (($result === null)||(is_string($result))) {


            if ($result === null) {
                $result = $this->getAppUrl();
            }
            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteUrl at ".__METHOD__
        );
    }
    /**
     * @return string
     */
    public function getOgSiteKeywords()
    {
        $result = $this->getOgConfig()->siteTitle;

        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteKeywords at ".__METHOD__
        );
    }
    /**
     * @return string
     */
    public function getOgSitePublisher()
    {
        $result = $this->getOgConfig()->sitePublisher;

        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.og.sitePublisher at ".__METHOD__
        );
    }
    /**
     * @return string
     */
    public function getOgSiteAuthor()
    {
        $result = $this->getOgConfig()->siteAuthor;

        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteAuthor at ".__METHOD__
        );
    }
    /**
     * @return string
     */
    public function getOgSiteDescription()
    {
        $result = $this->getOgConfig()->siteDescription;

        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteDescription at ".__METHOD__
        );
    }
    /**
     * @return string
     */
    public function getOgSiteType()
    {
        $result = $this->getOgConfig()->siteType;

        if (($result === null)||(is_string($result))) {
            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteType at ".__METHOD__
        );
        //return 'game';
    }
    /**
     * @return string
     */
    public function getOgSiteImage()
    {
        $result = $this->getOgConfig()->siteImage;

        if (($result === null)||(is_string($result))) {

            if ($result === null) {
                return $this->getSiteUrl()."og/assets/siteimage.jpg";
            }

            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteImage at ".__METHOD__
        );

        //return $this->getSiteUrl()."og/assets/siteimage.jpg";
        //return 'assets/sony_xmas_calendar.jpg';
    }
    /**
     * @return string
     */
    public function getOgSiteFavicon()
    {
        $result = $this->getOgConfig()->siteImage;

        if (($result === null)||(is_string($result))) {

            if ($result === null) {
                return $this->getSiteUrl()."og/assets/favicon.ico";
            }

            return $result;
        }
        throw new Exception(
            "Invalid config.og.siteImage at ".__METHOD__
        );

    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // FANPAGE CONFIG
    /**
     * @return string|null
     */
    public function getFanPageId()
    {
        $result = $this->getFanpageConfig()->id;

        if (($result === null)||(is_string($result))||is_int($result)) {
            if (is_int($result)) {
                $result = "".$result;
            }
            return $result;
        }
        throw new Exception(
            "Invalid config.fanpage.id at ".__METHOD__
        );


       // throw new Exception("Override in subclass ".__METHOD__);
       // return '152208658142315';
    }
    /**
     * @return string
     */
    public function getFanPageUrl()
    {
        $result = $this->getFanpageConfig()->url;

        if (($result === null)||(is_string($result))) {

           return $result;
        }
        throw new Exception(
           "Invalid config.fanpage.url at ".__METHOD__
        );

        //throw new Exception("Override in subclass ".__METHOD__);
        //return 'http://www.facebook.com/pages/MyTestPage/'.$this->getFanPageId();
    }
    /**
     * @return string
     */
    public function getFanPageTabUrl()
    {
        $result = $this->getFanpageConfig()->tabUrl;

        if (($result === null)||(is_string($result))) {

           if ($result === null) {
                return $this->getFanPageUrl()."?sk=app_".$this->getAppId();
           }

           return $result;
        }
        throw new Exception(
           "Invalid config.fanpage.tabUrl at ".__METHOD__
        );

    }

    /**
     * override
     * @return bool|null
     */
    public function isFanpageLikedRequired()
    {
        $result = $this->getFanpageConfig()->likeRequired;

        if (($result === null)||(is_bool($result))) {
           return $result;
        }
        throw new Exception(
           "Invalid config.fanpage.likeRequired at ".__METHOD__
        );
        
    }








    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++




    /**
     * @return string
     */
    public function getAppName()
    {
        $result = $this->getAppConfig()->name;

        if (($result === null)||(is_string($result))) {
           return $result;
        }
        throw new Exception(
           "Invalid config.app.name at ".__METHOD__
        );

        //return 'Sony Aktion';
    }
    /**
     * @return string
     */
    public function getSiteName()
    {
        $result = $this->getAppConfig()->siteName;

        if (($result === null)||(is_string($result))) {

           if ($result === null) {
               return $this->getAppName();
           }

           return $result;
        }
        throw new Exception(
           "Invalid config.app.siteName at ".__METHOD__
        );

        //return 'Sony Aktion';
    }

    /**
     * @return string
     */
    public function getAppUrl()
    {
        $result = $this->getAppConfig()->url;

        if (($result === null)||(is_string($result))) {
           return $result;
        }
        throw new Exception(
           "Invalid config.app.url at ".__METHOD__
        );
        //throw new Exception("Override in subclass ".__METHOD__);
        //return 'http://apps.facebook.com/sony-gewinnspiel/';
        //see: FB Developer App Settings "canvas App Url"
    }

    /**
     * @return string
     */
    public function getAppProfilePageUrl()
    {
        $result = $this->getAppConfig()->profilePageUrl;

        if (($result === null)||(is_string($result))) {

           if ($result === null) {
               return "http://www.facebook.com/apps/application.php?id="
                . $this->getAppId();
           }

           return $result;
        }
        throw new Exception(
           "Invalid config.app.url at ".__METHOD__
        );
      
    }


    /**
     * @return string
     */
    public function getSiteUrl()
    {
        return Bootstrap::getRegistry()->getUrl("fb/");
        //see: FB Developer App Settings "Site Url"
    }





    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @return string|null
     */
    public function getLoginScope()
    {

        $config = $this->getLoginConfig();

        $result = $config->scope;
        if (($result === null)||(is_string($result))) {
            if ($result === null) {
                $result ="";
            }
           return $result;
        }
        throw new Exception(
           "Invalid config.login.url at ".__METHOD__
        );                
    }



    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    // FRIEND SELECTOR
    /**
     * @return string
     */
    public function getInviteRootUrl()
    {
        return $this->getSiteUrl()."invite/";
        // location of  invite-scripts
    }

    /**
     * @return string
     */
    public function getEventsRootUrl()
    {
        return $this->getSiteUrl()."events/";
        // location of  invite-scripts
    }




    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @return Zend_Config
     */
    public function getMvcCanvasConfig()
    {
        $config = $this->getMvcConfig()->canvas;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.mvc.canvas at ".__METHOD__);
        }

        if ($config->type === null) {
            $config->type = self::ENVIRONMENT_TYPE_CANVAS;
        }
        if ($config->contentUrl === null) {
            $config->contentUrl = $this->getSiteUrl().'canvas/index.php';
        }
        if ($config->url === null) {
            $config->url = $this->getAppUrl();
        }

        return $config;
    }

    /**
     * @throws Exception
     * @return Zend_Config
     */
    public function getMvcTabConfig()
    {
        $config = $this->getMvcConfig()->tab;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.mvc.tab at ".__METHOD__);
        }

        if ($config->type === null) {
            $config->type = self::ENVIRONMENT_TYPE_TAB;
        }
        if ($config->contentUrl === null) {
            $config->contentUrl = $this->getSiteUrl().'canvas/tab.php';
        }
        if ($config->url === null) {
            $config->url = $this->getFanPageTabUrl();
        }

        return $config;


    }

    /**
     * @throws Exception
     * @return Zend_Config
     */
    public function getMvcConnectConfig()
    {
        $config = $this->getMvcConfig()->connect;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.mvc.connect at ".__METHOD__);
        }

        if ($config->type === null) {
            $config->type = self::ENVIRONMENT_TYPE_CONNECT;
        }
        if ($config->contentUrl === null) {
            $config->contentUrl = $this->getSiteUrl().'canvas/connect.php';
        }
        if ($config->url === null) {
            $config->url = $this->getSiteUrl().'canvas/connect.php';
        }
               
        return $config;
    }


    /**
     * @throws Exception
     * @param  string $pageType
     * @return Zend_Config
     */
    public function getMvcConfigByPageType($pageType)
    {

        switch($pageType)
        {

            case self::ENVIRONMENT_TYPE_CANVAS: {
                return $this->getMvcCanvasConfig();
                break;
            }
            case self::ENVIRONMENT_TYPE_TAB: {
                return $this->getMvcTabConfig();
                break;
            }
            case self::ENVIRONMENT_TYPE_CONNECT: {
                return $this->getMvcConnectConfig();
                break;
            }

            default: {
                throw new Exception(
                    "Invalid parameter 'pageType' at ".__METHOD__
                );
                break;
            }
        }

    }

    /**
     * @param null|string $url
     * @return Lib_Url_Uri
     */
    public function newUri($url = null)
    {
        return new Lib_Url_Uri($url);
    }

    /**
     * @throws Exception|Lib_Application_Exception
     * @param  string $pageType
     * @return Lib_Url_Uri
     */
    public function newUriByPageType($pageType)
    {
        try {
            $mvcConfig = $this->getMvcConfigByPageType($pageType);
            $pageUrl = $mvcConfig->url;
            $pageUri = $this->newUri(null);
            if ($pageUri->isValidUri($pageUrl)!==true) {
                throw new Exception("Invalid pageConfig.url at ".__METHOD__);
            }
            $pageUri->setUri($pageUrl);

            $pageUri->requireValidUri(__METHOD__, null);
            // add trailing "/" to urlPath if not ends with ".php"
            if (Lib_Utils_String::endsWith($pageUri->getPath(),"/") !== true) {
                if (Lib_Utils_String::endsWith(
                        $pageUri->getPath(),".php"
                    ) !== true) {
                    $pageUri->setPath($pageUri->getPath()."/");
                }
            }

            return $pageUri;

        } catch (Exception $error) {

            $e = new Lib_Application_Exception("Invalid uri by pageType");
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                             "pageType" => $pageType,
                             "details" => $e->getMessage(),
                         ));
            throw $e;
        }
    }

    /**
     * @return Lib_Url_Uri
     */
    public function newUriPageCanvas()
    {
        $pageType = self::ENVIRONMENT_TYPE_CANVAS;
        $uri = $this->newUriByPageType($pageType);
        return $uri;
    }

    /**
     * @return Lib_Url_Uri
     */
    public function newUriPageTab()
    {
        $pageType = self::ENVIRONMENT_TYPE_TAB;
        $uri = $this->newUriByPageType($pageType);
        return $uri;
    }

    /**
     * @return Lib_Url_Uri
     */
    public function newUriPageConnect()
    {
        $pageType = self::ENVIRONMENT_TYPE_CONNECT;
        $uri = $this->newUriByPageType($pageType);
        return $uri;
    }


    /**
     * @throws Exception
     * @param  string $pageType
     * @return string|null
     */
    public function getMvcContentUrlByPageType($pageType)
    {

        $config = $this->getMvcConfigByPageType($pageType);

        $result = $config->contentUrl;
        if ((($result === null)||(is_string($result)))) {
            return $result;
        }

        throw new Exception(
            "Invalid config.mvc.".$pageType.".contentUrl at ".__METHOD__
        );
    }


    /**
     * @OBSOLETE
     * @param  string $environmentType
     * @return array
     */
    public function getEnvironmentConfigByType(
        //OBSOLETE !!!
        $environmentType
    )
    {
        throw new Exception("Obsolete ".__METHOD__);
/*
        $config = $this->getEnvironmentsConfig();
        if (
                (isset($config[$environmentType]))
                && (is_array($config[$environmentType]))
        ) {

            $env = $config[$environmentType];

            return $env;
        }

        return array(
            "type" => $environmentType,
            "contentUrl" => null,
        );
*/
    }



    /**
     * @throws Exception
     * @param  string $pageType
     * @return string|null
     */
    public function getContentUrlByPageType($pageType)
    {
        throw new Exception("obsolete at" .__METHOD__);
        /*
        if (Lib_Utils_String::isEmpty($pageType)) {
            throw new Exception(
                "Invalid pageType=".$pageType
                        ." at ".__METHOD__." for ".get_class($this)
            );
        }
        $pageConfig = $this->getEnvironmentConfigByType($pageType);

        $contentUrl = Lib_Utils_Array::getProperty($pageConfig, "contentUrl");
        if ((($contentUrl === null)||(is_string($contentUrl))) !== true) {
            throw new Exception(
                "Method returns invalid result pageType=".$pageType
                        ." at ".__METHOD__." for ".get_class($this)

            );
        }
        return $contentUrl;
        */
    }


    /**
     * OVERRIDE IN SUBCLASS
     * @return array
     */
    public function getEnvironmentsConfig()
    {

        $this->getMvcConfig();

        /*
        $isOverrideRequired = true;
        $config = array(

            "canvas" => array(
                "type" => Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS,
                "contentUrl" => $this->getSiteUrl().'canvas/index.php',
                // the nice url for external access
                "url" => $this->getAppUrl(),
                "enabled" => true,
            ),
            "tab" => array(
                "type" => Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB,
                "contentUrl" => $this->getSiteUrl().'canvas/tab.php',
                // the nice url for external access
                "url" => $this->getFanPageTabUrl(),
                "enabled" => true,
            ),
            "connect" => array(
                "type" => Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
                "contentUrl" => $this->getSiteUrl().'canvas/connect.php',
                // the nice url for external access
                "url" => $this->getSiteUrl().'canvas/connect.php',
                "enabled" => true,
            ),

        );

        if ($isOverrideRequired === true) {
            throw new Exception(
                "Override in Subclass ".__METHOD__." for ".get_class($this)
            );
        }
        return $config;
        */
    }




    /**
     * use for quick debugging
     * @return array
     */
    public function toArray()
    {
       $result = array(
            "appId" => $this->getAppId(),
            "apiKey" => $this->getApiKey(),
            "apiSecret" => $this->getApiSecret(),
            "loginScope" => $this->getLoginScope(),
            "appName" => $this->getAppName(),
            "appUrl" => $this->getAppUrl(),
            "siteName" => $this->getSiteName(),
            "siteUrl" => $this->getSiteUrl(),

            "fanPageId" => $this->getFanPageId(),
            "fanPageUrl" => $this->getFanPageUrl(),
            "fanPageTabUrl" => $this->getFanPageTabUrl(),

            "eventsRootUrl" => $this->getEventsRootUrl(),
            "inviteRootUrl" => $this->getInviteRootUrl(),

            "mvcConfig" => $this->getMvcConfig(),


            "ogSiteAuthor" => $this->getOgSiteAuthor(),
            "ogSiteDescription" => $this->getOgSiteDescription(),
            "ogSiteFavicon" => $this->getOgSiteFavicon(),
            "ogSiteImage" => $this->getOgSiteImage(),
            "ogSiteKeywords" => $this->getOgSiteKeywords(),
            "ogSitePublisher" => $this->getOgSitePublisher(),
            "ogSiteTitle" => $this->getOgSiteTitle(),
            "ogSiteType" => $this->getOgSiteType(),
            "ogSiteUrl" => $this->getOgSiteUrl(),

        );


        $result["mvcConfig"] = array(
            "canvas" => $this->getMvcCanvasConfig()->toArray(),
            "tab" => $this->getMvcTabConfig()->toArray(),
            "connect" => $this->getMvcConnectConfig()->toArray(),
        );



       return $result;
    }




	
}
