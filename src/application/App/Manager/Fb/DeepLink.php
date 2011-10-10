<?php
/**
 * App_Manager_Fb_DeepLink
 *
 * @category	meetidaaa.com
 * @package        App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */

/**
 * App_Manager_Fb_DeepLink
 *
 *
 * @category	meetidaaa.com
 * @package        App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */
class App_Manager_Fb_DeepLink extends App_Manager_AbstractManager
{

    const CRYPT_SALT = "DeepLink2564825";
    const URI_QUERY_PARAMETER_PREFIX = "meetidaaa_";
    const URI_QUERY_PARAMETER_NAME = "viewParams";


    // actions
     // actions
    const ACTION_FOO = 'FOO';
    const ACTION_SHOW_RESTAURANT_MENU = 'SHOWRESTAURANTMENU';

    public function getActionsAvailable()
    {
        return array(
            self::ACTION_FOO,
            self::ACTION_SHOW_RESTAURANT_MENU,
        );
    }

    /**
     * @param  $action
     * @return bool
     */
    public function isActionAvailable($action)
    {
        $actions = $this->getActionsAvailable();
        return in_array($action, $actions, true);
    }


    


     /**
     * @var App_Manager_Fb_DeepLink
     */
    private static $_instance;

    /**
     * @var Lib_Utils_Crypt
     */
    private $_cryptUtil;

    /**
     * @static
     * @return App_Manager_Fb_DeepLink
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }


    /**
     * @override
     * @return App_Facebook_Db_Xdb_Client
     */
	public function getDbClient()
	{
        return $this->getApplication()->getDbClient();
	}

    /**
     * @return null|string
     */
    public function getCurrentDate()
    {
        return $this->getApplication()->getCurrentDate();
    }


    /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++





    /**
     * @return Lib_Utils_Crypt
     */
    public function getCryptUtil()
    {
        if (($this->_cryptUtil instanceof Lib_Utils_Crypt)!==true) {
            $this->_cryptUtil = new Lib_Utils_Crypt();
        }
        return $this->_cryptUtil;
    }

    /**
     * @param  mixed $value
     * @param  string|null $salt
     * @return null|string
     */
    public function encryptValue($value, $salt)
    {
        $cryptUtil = $this->getCryptUtil();
        $cryptUtil->setCryptSalt($salt);
        $result = $cryptUtil->encryptValue($value);
        $result = Lib_Utils_Base64::encodeUrlSafe($result);
        return $result;
    }

    /**
     * @param  mixed $value
     * @param  string|mixed $salt
     * @param bool $jsonAssoc
     * @return mixed|null
     */
    public function decryptValue($value, $salt)
    {
        $cryptUtil = $this->getCryptUtil();
        $cryptUtil->setCryptSalt($salt);

        $jsonAssoc = true;
        $strict = false;
        $result = $value;
        $result = Lib_Utils_Base64::decodeUrlSafe($result, $strict);
        $result = $cryptUtil->decryptValue($result, $jsonAssoc);
        return $result;
    }


    /**
     * @param  $action
     * @param  $data
     * @return null|string
     */
    public function encodeViewParams($action, $data)
    {
        $salt = self::CRYPT_SALT;
        $value = array(
            "a" => $action,
            "d" => $data,
            "t" => strtotime($this->getCurrentDate()),
        );


        $result = $this->encryptValue($value, $salt);
        return $result;
    }

    /**
     * @param  string $viewParams
     * @return array
     */
    public function decodeViewParams($viewParams)
    {
        $salt = self::CRYPT_SALT;

        $viewParams = $this->decryptValue($viewParams, $salt);

        $result = array(
            "action" => Lib_Utils_Array::getProperty($viewParams, "a", true),
            "data" => Lib_Utils_Array::getProperty($viewParams, "d", true),
            "timestamp" => Lib_Utils_Array::getProperty($viewParams, "t", true),
            "date" => null
        );
        if (is_int($result["timestamp"])!==true) {
            $result["timestamp"] = null;
        }
        if (is_int($result["timestamp"])===true) {
            $result["date"] = date("Y-m-d H:i:s", $result["timestamp"]);
        }

        return $result;
    }



    /**
     * @return array
     */
    public function decodeDeepLink(Lib_Url_Uri $pageUri)
    {
        $viewParams = null;

        try {
            $pageUri->requireValidUri(__METHOD__, null);
            $viewParams = $pageUri->getQueryParameter(
                self::URI_QUERY_PARAMETER_PREFIX.self::URI_QUERY_PARAMETER_NAME
            );
        } catch (Exception $error) {
            //NOP
        }
        $viewParamsDecoded = $this->decodeViewParams($viewParams);

        return $viewParamsDecoded;

    }


    /**
     * @return array
     */
    public function decodeDeepLinkFromCurrentUrl()
    {
        $viewParams = null;
        $viewParamsDecoded = null;
        try {
            $pageUri = new Lib_Url_Uri();
            $currentUrl = $pageUri->getCurrentUrl();
            $pageUri->setUri($currentUrl);
            $viewParamsDecoded = $this->decodeDeepLink($pageUri);
        } catch(Exception $error) {
            $viewParamsDecoded = $this->decodeViewParams($viewParams);
        }

        return $viewParamsDecoded;
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
     * @throws Lib_Application_Exception
     * @return Lib_Url_Uri
     */
    public function newUriPageCanvas()
    {
        try {
            return $this->getFacebook()->getConfig()->newUriPageCanvas();
        } catch(Exception $error) {
            $e = new Lib_Application_Exception("Invalid uri");
            $e->setMethod(__METHOD__);
            $e->setFault($error);
            throw $e;
        }
    }
    /**
     * @throws Lib_Application_Exception
     * @return Lib_Url_Uri
     */
    public function newUriPageTab()
    {
        try {
            return $this->getFacebook()->getConfig()->newUriPageTab();
        } catch(Exception $error) {
            $e = new Lib_Application_Exception("Invalid uri");
            $e->setMethod(__METHOD__);
            $e->setFault($error);
            throw $e;
        }
    }

    /**
     * @throws Lib_Application_Exception
     * @return Lib_Url_Uri
     */
    public function newUriPageConnect()
    {
        try {
            return $this->getFacebook()->getConfig()->newUriPageConnect();
        } catch(Exception $error) {
            $e = new Lib_Application_Exception("Invalid uri");
            $e->setMethod(__METHOD__);
            $e->setFault($error);
            throw $e;
        }
    }


    /**
     * @param Lib_Url_Uri $sourceUri
     * @param Lib_Url_Uri $targetUri
     * @return void
     */
    public function copyViewParamsToUri(
        Lib_Url_Uri $sourceUri,
        Lib_Url_Uri $targetUri
    )
    {
        $sourceUri->requireValidUri(__METHOD__, null);
        $targetUri->requireValidUri(__METHOD__, null);

        $paramKey = self::URI_QUERY_PARAMETER_PREFIX
                    . self::URI_QUERY_PARAMETER_NAME;


        $viewParams = $sourceUri->getQueryParameter(
            $paramKey
        );

        if ($viewParams === null) {
            $targetUri->unsetQueryParameter(
                $paramKey
            );
        } else {
            $targetUri->setQueryParameter(
                $paramKey,
                $viewParams
            );
        }

    }

    /**
     * @throws Lib_Application_Exception
     * @param  string $action
     * @param  string $errorMethod
     * @return void
     */
    public function requireIsDeeplinkActionAvailable($action, $errorMethod)
    {
        if (is_string($errorMethod)!==true) {
            $errorMethod = __METHOD__;
        }

        if ($this->isActionAvailable($action)!==true) {
            $e = new Lib_Application_Exception(
                "Invalid deeplink action not available at ".__METHOD__
            );
            $e->setFault(array(
                            "action" => $action
                         ));
            $e->setMethod($errorMethod);
            throw $e;
        }
    }

    // ++++++++++++++++++++++ ACTIONS ++++++++++++++++++++++++++++

    /**
     * @param Lib_Url_Uri $pageUri
     * @return string
     */
    public function createDeepLinkWithoutViewParams(
            Lib_Url_Uri $pageUri
    )
    {

        

        $deepLinkUrl = $pageUri->toString(Lib_Url_Uri::SCHEME_HTTP);

        return $deepLinkUrl;

    }




    /**
     * @param Lib_Url_Uri $pageUri
     * @param  $barId
     * @return string
     */
    public function createDeepLinkActionFoo(
            Lib_Url_Uri $pageUri,
            $barId
    )
    {

        $action = self::ACTION_FOO;
        $data = array(
            'barId' => $barId,
        );


        $this->requireIsDeeplinkActionAvailable($action, __METHOD__);



        $viewParams = $this->encodeViewParams($action, $data);

        $pageUri->requireValidUri(__METHOD__, null);
        // add trailing "/" to urlPath if not ends with ".php"
        if (Lib_Utils_String::endsWith($pageUri->getPath(),"/") !== true) {
            if (Lib_Utils_String::endsWith(
                    $pageUri->getPath(),".php"
                ) !== true) {
                $pageUri->setPath($pageUri->getPath()."/");
            }
        }

        $pageUri->setQueryParameter(
            self::URI_QUERY_PARAMETER_PREFIX.self::URI_QUERY_PARAMETER_NAME,
            $viewParams
        );

        $deepLinkUrl = $pageUri->toString(Lib_Url_Uri::SCHEME_HTTP);

        return $deepLinkUrl;

    }


    /**
     * @param Lib_Url_Uri $pageUri
     * @param  $barId
     * @return string
     */
    public function createDeepLinkActionShowRestaurantMenu(
            Lib_Url_Uri $pageUri,
            $restaurantId
    )
    {

        $action = self::ACTION_SHOW_RESTAURANT_MENU;
        $data = array(
            'id' => (int)$restaurantId,
        );

        $this->requireIsDeeplinkActionAvailable($action, __METHOD__);



        $viewParams = $this->encodeViewParams($action, $data);

        $pageUri->requireValidUri(__METHOD__, null);
        // add trailing "/" to urlPath if not ends with ".php"
        if (Lib_Utils_String::endsWith($pageUri->getPath(),"/") !== true) {
            if (Lib_Utils_String::endsWith(
                    $pageUri->getPath(),".php"
                ) !== true) {
                $pageUri->setPath($pageUri->getPath()."/");
            }
        }

        $pageUri->setQueryParameter(
            self::URI_QUERY_PARAMETER_PREFIX.self::URI_QUERY_PARAMETER_NAME,
            $viewParams
        );

        $deepLinkUrl = $pageUri->toString(Lib_Url_Uri::SCHEME_HTTP);

        return $deepLinkUrl;

    }

}
