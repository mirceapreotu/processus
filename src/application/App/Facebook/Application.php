<?php
/**
 * App_Facebook_Application
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

/**
 * App_Facebook_Application
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

class App_Facebook_Application extends App_Application
{


    /**
     * @var App_Facebook_Application
     */
    private static $_instance;


    /**
     * @var App_Facebook_Mock
     */
    protected $_mock;


   
    /**
     * @var App_Facebook_Facebook
     */
    protected $_facebook;



    /**
     * @var App_Model_Bo_Fb_User
     */
    protected $_viewerBO;

    /**
     * @var array
     */
    protected $_facebookMe;


    /**
     * @var App_Facebook_Dispatcher_Canvas
     */
    protected $_dispatcherCanvas;



/**
     * @static
     * @return App_Facebook_Application
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            $instance = new self();
            self::$_instance = $instance;
            $instance->init();
        }
        return self::$_instance;
    }

    // +++++++++++++ social env ++++++++++++++++++++
    /**
     * @override
     * @return string
     */
    public function getSocialEnvProvider()
    {
        return "fb";
    }


   

    // ++++++++++ url +++++++++++++++++++++++++++++++++++


    /**
     * @override
     * @param  string $url
     * @return string
     */
    public function getUrl($url)
    {
        $url = trim("".$url);
        $prefix = "fb";

        if (Lib_Utils_String::startsWith($url, $prefix."/", false)) {

            return $this->getUrlGlobal($url);
        }
        if (Lib_Utils_String::startsWith($url, "/".$prefix."/", false)) {

            return $this->getUrlGlobal($url);
        }

        return $this->getUrlLocal($url);
    }


    /**
     * @override
     * @param  string $url
     * @return string
     */
    public function getUrlLocal($url)
    {
        $prefix = "fb";

        $url = trim("".$url);
        if (Lib_Utils_String::startsWith($url,"/",true)) {
            $url = $prefix."".$url;
        } else {
            $url = $prefix."/".$url;
        }

        return $this->getUrlGlobal($url);

/*
        $protocol = "http";
  		$host = Bootstrap::getRegistry()->getServerStage()->host;

        $url = trim("".$url);
        if (Lib_Utils_String::startsWith($url,"/",true)) {
            $url = $protocol."://".$host."/".$prefix."".$url;
        } else {
            $url = $protocol."://".$host."/".$prefix."/".$url;
        }

        return $url;
*/
    }




    // ++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @return App_Model_Bo_Fb_User
     */
    public function getViewerBO()
    {
        if (($this->_viewerBO instanceof App_Model_Bo_Fb_User)!==true) {
            $this->_viewerBO = new App_Model_Bo_Fb_User();
        }
        return $this->_viewerBO;
    }

    /**
     * @throws Exception
     * @param  App_Model_Bo_Fb_User|null $viewerBO
     * @return 
     */
    public function setViewerBO($viewerBO)
    {
        if ($viewerBO === null) {
            $this->_viewerBO = null;
            return;
        }

        if (($viewerBO instanceof App_Model_Bo_Fb_User)!==true) {
            throw new Exception(
                "Parameter viewerBO must be null or App_Model_Bo_Fb_User at "
                . __METHOD__
            );
        }

        $this->_viewerBO = $viewerBO;
    }

    /**
     * @return App_Model_Bo_Fb_User
     */
    public function newViewerBO()
    {
        return $this->newUserBO();
    }

    /**
     * @return
     */
    public function destroyViewerBO()
    {
        $this->_viewerBO = null;
    }

    /**
     * @return App_Model_Bo_Fb_User
     */
    public function newUserBO()
    {
        return new App_Model_Bo_Fb_User();
    }


    /**
     * @return App_Facebook_Mock
     */
    public function getMock()
    {
        if (($this->_mock instanceof App_Facebook_Mock)!==true) {
            $this->_mock = new App_Facebook_Mock();
        }
        return $this->_mock;
    }



    // ++++++++++++ session +++++++++++++++++++++++++++++++

    /**
     * @return App_Facebook_Session
     */
    public function getSession()
    {
        return App_Manager_Core_Session::getInstance()->getSessionFb();
    }


    // ++++++++++++++ db +++++++++++++++++++++++++++++

    /**
     * @return App_Facebook_Db_Xdb_Client
     */
    public function getDbClient()
    {
        return App_Manager_Core_Xdb::getInstance()->getDbClientFb();
    }


    // +++++++++++++ manager +++++++++++++++++++++++


    /**
     * @return App_Manager_Fb_AppConfig
     */
    public function getManagerAppConfig()
    {
        return App_Manager_Fb_AppConfig::getInstance();
    }

    /**
     * @return App_Manager_Fb_Person
     */
    public function getManagerPerson()
    {
        return App_Manager_Fb_Person::getInstance();
    }

    /**
     * @return App_Manager_Fb_DeepLink
     */
    public function getManagerDeeplink()
    {
        return App_Manager_Fb_DeepLink::getInstance();
    }

    /**
     * @return App_Manager_Fb_ImageUpload
     */
    public function getManagerImageUpload()
    {
        return App_Manager_Fb_ImageUpload::getInstance();
    }



    // ++++++++++ api client +++++++++++++++++++++++++

    

    /**
     * @return App_Facebook_Facebook
     */
    public function getFacebook()
    {

        if (($this->_facebook instanceof App_Facebook_Facebook)!==true) {

            $config = new App_Facebook_Config();

            $this->_facebook = new App_Facebook_Facebook($config);
        }

        return $this->_facebook;

    }


    /**
     * @return bool
     */
    public function hasFacebookMe()
    {
        $result = false;

        $meId = $this->getFacebookMeId();
        /*
        if (is_array($this->_facebookMe)!==true) {
            return $result;
        }
        $meId = Lib_Utils_Array::getProperty($this->_facebookMe, "id");
        */
        if ($this->getFacebook()->isValidUserId($meId) !== true) {
            return $result;
        }
        return true;
    }

    /**
     * @return string|null
     */
    public function getFacebookMeId()
    {
        $result = null;
        if (is_array($this->_facebookMe)!==true) {
            return $result;
        }
        $meId = Lib_Utils_Array::getProperty($this->_facebookMe, "id");
        if ($meId ===  null) {
            return $meId;
        } else {
            return "".$meId;
        }
    }

    /**
     * @throws Exception
     * @param  array|null $me
     * @return void
     */
    public function setFacebookMe($me)
    {
        if (is_array($me)||($me===null)) {
            $this->_facebookMe = $me;
        } else {
            throw new Exception("Invalid parameter 'me' at ".__METHOD__);
        }
    }

    /**
     * @return array|null
     */
    public function getFacebookMe()
    {
        return $this->_facebookMe;
    }

    /**
     * @return void
     */
    public function destroyFacebookMe()
    {
        $this->_facebookMe = null;
    }


    /**
     * @return App_Manager_Fb_FacebookPermissions
     */
    public function getManagerFacebookPermissions()
    {
        return App_Manager_Fb_FacebookPermissions::getInstance();
    }




    /**
     * @return int|mixed|null|string
     */

    public function mockFacebookSignedRequestIfMockingEnabled()
    {
        $application = $this;

        $facebook = $application->getFacebook();

        // NOTICE:
        // YOU MUST NOT access facebook.getUser()
        // before applying mockData!!!!
       //$userId = $facebook->getUser();
        $mock = $application->getMock();
        $mockEnabled = $mock->isEnabled();
        if ($mockEnabled === true) {

            $mock->applySessionData();
        }

        // NOTICE:
        // YOU MUST NOT access facebook.getUser()
        // before applying mockData!!!!
        $userId = $facebook->getUserIdFromAvailableData();

        return $userId;
    }





    /**
     * @throws Lib_Application_Exception
     * @return void
     */
    public function requireFacebookMe()
    {
        $application = $this;
        $application->destroyFacebookMe();

        $facebook = $application->getFacebook();

        // NOTICE:
        // YOU MUST NOT access facebook.getUser()
        // before applying mockData!!!!
       //$userId = $facebook->getUser();


        $mock = $application->getMock();
        $mockEnabled = $mock->isEnabled();
        if ($mockEnabled === true) {

            $mock->applySessionData();
        }

        // NOTICE:
        // YOU MUST NOT access facebook.getUser()
        // before applying mockData!!!!
        $userId = $facebook->getUserIdFromAvailableData();

        $fault = array();
        if ($mockEnabled === true) {
            $fault = array(
                "mock" => array(
                    "enabled" => $mock->isEnabled(),
                    "sessionData" => $mock->getSessionData(),
                ),
                "facebook" => array(
                    "userId" => $userId,
                    "signedRequest" => $facebook->getSignedRequest(),
                    "getAccessToken" => $facebook->getAccessToken(),
                    "getUserAccessToken" => $facebook->getUserAccessToken(),
                ),
            );
        } else {
            $fault = array(
                "mock" => array(
                    "enabled" => $mock->isEnabled(),
                    //"sessionData" => $mock->getSessionData(),
                ),
                "facebook" => array(
                    "userId" => $userId,
                    /*
                    "signedRequest" => $facebook->getSignedRequest(),
                    "getAccessToken" => $facebook->getAccessToken(),
                    "getUserAccessToken" => $facebook->getUserAccessToken(),
                    */
                ),
            );
        }

         //$me = $facebook->api("/me");
        $me = null;
        try {
            //$me = $facebook->fetchMe();
            $me = $facebook->api('/me');
        } catch(FacebookApiException $error) {



            $e = new Lib_Application_Exception("LOGIN");
            $e->setMethod(__METHOD__);
            $e->setMethodLine(__LINE__);
            $fault["message"] = "facebook.fetchMe() failed. [ "
                                .$error->getMessage()." ]";
            $e->setFault($fault);
            throw $e;
        }

        $application->setFacebookMe($me);

        if ($application->hasFacebookMe() !== true) {

            $e = new Lib_Application_Exception("LOGIN");
            $e->setMethod(__METHOD__);
            $e->setMethodLine(__LINE__);
            $fault["message"] = "invalid application.hasFacebookMe()";
            $fault["facebookMeId"] = (string)$application->getFacebookMeId();
            $fault["facebookUserId"] = (string)$userId;
            $fault["facebookMe"] = $application->getFacebookMe();
            $e->setFault($fault);
            throw $e;

        }
        $meId = $application->getFacebookMeId();
        if ( ((string)$meId) !== ((string)$userId) ) {

            $e = new Lib_Application_Exception("LOGIN");
            $e->setMethod(__METHOD__);
            $e->setMethodLine(__LINE__);
            $fault["message"] = "invalid facebookMeId <>facebookUserId";
            $fault["facebookMeId"] = (string)$meId;
            $fault["facebookUserId"] = (string)$userId;
            $e->setFault($fault);
            throw $e;

        }

    }


    /**
     * @return string|null
     */
    public function getFacebookSignedRequestText()
    {
        $result = null;

        $application = $this;
        $mock = $application->getMock();
        $mockEnabled = $mock->isEnabled();


        $request = array(
            "signed_request" => null,
        );

        if ($mockEnabled === true) {
            $request = $mock->getSessionData();
        } else {
            $request = (array)$_REQUEST;
        }

        $signedRequestText = Lib_Utils_Array::getProperty(
            $request, "signed_request"
        );

        if (is_string($signedRequestText)) {
            return $signedRequestText;
        }
        return $result;
    }


    /**
     * @param  string $signedRequestText
     * @return array
     */
    public function decodeFacebookSignedRequestText($signedRequestText)
    {
        $application = $this;
        $result = $application->getFacebook()
                ->decodeSignedRequest($signedRequestText);
        return $result;
    }

    /**
     * @return array
     */
    public function getFacebookSignedRequestDecoded()
    {
        $text = $this->getFacebookSignedRequestText();
        $data = $this->decodeFacebookSignedRequestText($text);
        return $data;
    }


    /**
     * @return array|null
     */
    public function getFacebookSignedRequestDecodedForFrontend()
    {
        
        $result = null;
        $requestDecoded = $this->getFacebookSignedRequestDecoded();
        if (is_array($requestDecoded)!==true) {
            return $result;
        }
        $result = array(
            "app_data" => Lib_Utils_Array::getProperty(
                $requestDecoded, "app_data"
            ),
            "page" => Lib_Utils_Array::getProperty(
                $requestDecoded, "page"
            ),
            "user" => Lib_Utils_Array::getProperty(
                $requestDecoded, "user"
            ),
        );

        $app_data = $result["app_data"];
        if (Lib_Utils_String::isEmpty($app_data)!==true) {
            $app_data = json_decode($app_data, true);
            if (is_array($app_data)) {
                $result["app_data"] = $app_data;
            }
        }


        return $result;
    }




    /**
     * @return int|string|null
     */
    public function getFacebookFanpageIdFromSignedRequestDecoded()
    {
        /*
        $result = array(


            "app_data" => null,
            "user" => array(
                "id" => null,
            ),
            "page" => array(
                "id" => null,
                "liked" => null,
                "admin" => null,
            ),
            "error" => null,
            "data" => null,
        );
        */

        $data = $this->getFacebookSignedRequestDecoded();
        $page = Lib_Utils_Array::getProperty($data, "page");
        $pageId = Lib_Utils_Array::getProperty($page, "id");
        if (is_string($pageId)) {
            return $pageId;
        }
        if (is_int($pageId)) {
            return $pageId;
        }

        return null;
    }


    /**
     * @return string|mixed|null
     */
    public function getFacebookFanpageAppDataFromSignedRequestDecoded()
    {
        /*
        $result = array(


            "app_data" => null,
            "user" => array(
                "id" => null,
            ),
            "page" => array(
                "id" => null,
                "liked" => null,
                "admin" => null,
            ),
            "error" => null,
            "data" => null,
        );
        */

        $data = $this->getFacebookSignedRequestDecoded();

        $appData = Lib_Utils_Array::getProperty($data, "app_data");

        return $appData;
    }


    /**
     * @return bool|null
     */
    public function getFacebookFanpageLikedByUserFromSignedRequestDecoded()
    {
        /*
        $result = array(


            "app_data" => null,
            "user" => array(
                "id" => null,
            ),
            "page" => array(
                "id" => null,
                "liked" => null,
                "admin" => null,
            ),
            "error" => null,
            "data" => null,
        );
        */

        $data = $this->getFacebookSignedRequestDecoded();
        $page = Lib_Utils_Array::getProperty($data, "page");
        $pageLiked =  Lib_Utils_Array::getProperty($page, "liked");

        if ($pageLiked===null) {
            return null;
        }

        return (bool)($pageLiked===true);
    }

    // +++++++++++++++++++++ Dispatcher ++++++++++++++++++++++++++

    // we need that one for deeplinked fanpage login urls

    public function getDispatcherCanvas()
    {
        if (($this->_dispatcherCanvas instanceof App_Facebook_Dispatcher_Canvas)!==true) {
            $this->_dispatcherCanvas = new App_Facebook_Dispatcher_Canvas();
        }
        return $this->_dispatcherCanvas;
    }

     /**
     * @param  int|string|null $fanpageId
     * @param  null|string|mixed $appData
     * @return string
     */
    public function newFanpageLoginUrlRedirectUri($fanpageId, $appData)
    {
        return $this->getDispatcherCanvas()->newFanpageLoginUrlRedirectUri(
            $fanpageId, $appData
        );
    }



    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    /**
     * @param  int|string $id
     * @return bool
     */
    public function isValidId($id)
    {
        $id = Lib_Utils_TypeCast_String::asUnsignedBigIntString(
            $id, null
        );

        if ($id!==null) {
            return true;
        }

        return false;
    }




        /**
     * @throws Exception
     * @param  int|string|null $fanpageId
     * @param  string|mixed|null $appData
     * @return string
     */
    public function newUrlToFanpage($fanpageId, $appData)
    {

        $appId = $this->getFacebook()->getConfig()->getAppId();
        if ($this->isValidId($appId)!==true) {
            throw new Exception("Invalid config facebook appId at ".__METHOD__);
        }
        $pageId = null;

        if ($this->isValidId($fanpageId)) {
            $pageId = $fanpageId;
        } else {

            // try to get from config

            $configFanpageId = $this->getFacebook()
                    ->getConfig()->getFanPageId();
            if ($this->isValidId($configFanpageId)!==true) {
                throw new Exception(
                    "Invalid config facebook fanpageId at ".__METHOD__
                );
            }

            $pageId = $configFanpageId;
        }



        $zendUri = new Lib_Url_Uri("http://www.facebook.com/pages/".$pageId);
        $zendUri->setQueryParameter("sk","app_".$appId);

        $appDataJson = null;
        if ($appData === "") {
            $appData = null;
        }
        if ($appData !== null) {
            if (is_string($appData)) {
                $appDataJson = $appData;
            } else {
                $appDataJson = json_encode($appData);
            }
        }

        if (Lib_Utils_String::isEmpty($appDataJson)!==true) {
            $zendUri->setQueryParameter("app_data", $appDataJson);
        }


        $url = $zendUri->toString(null);
        return $url;
    }


    // ++++++++++++++++++++++++++++++++++++++++++++++
        /**
     * @throws Exception
     * @param  bool $forceFbAuthIfNotExists
     * @param  bool $updatePersonRecord
     * @return
     */
    public function tryAutoRegisterFbUser(
        $forceFbAuthIfNotExists, $updatePersonRecord)
    {
        if (is_bool($forceFbAuthIfNotExists)!==true) {
            throw new Exception(
                "Invalid parameter 'forceFbAuthIfNotExists' at ".__METHOD__
            );
        }
        if (is_bool($updatePersonRecord)!==true) {
            throw new Exception(
                "Invalid parameter 'updatePersonRecord' at ".__METHOD__
            );
        }

        $application = $this;
        $viewerBO = $this->getViewerBO();

        if ($viewerBO->getPersonRecord()->exists()===true) {


            $fbPersonRecord = $viewerBO->getFbPersonRecord();
            if ($fbPersonRecord->exists()!==true) {
                // that case is basically impossible to happen ...
                throw new Exception(
                    "Can't find fb person record for existing viewer at "
                    .__METHOD__
                );
            }


            if ($updatePersonRecord!==true) {
                return;
            }

        }

        // no user, check session, force fb auh, fetch fbme, user to db


            try {
                $application->requireFacebookMe();

            } catch(Exception $e) {
                if ($forceFbAuthIfNotExists === true) {
                    // delegate the LOGIN-Command-Exception
                    throw $e;
                }
            }

            $application->destroyViewerBO();
            $hasMe = $application->hasFacebookMe();
            if ($hasMe !== true) {
                return;
            }

            //
            $me = $application->getFacebookMe();
            $meId = $application->getFacebookMeId();

            $viewerBO->getManagerPerson()
                    ->autoRegisterFacebookUserByFacebookMe($me);


            $viewerBO = new App_Model_Bo_Fb_User();
            $application->setViewerBO($viewerBO);
            $viewerBO->setExternalKey($meId);

            if ($viewerBO->getPersonRecord()->exists()!==true) {
                throw new Exception("save user failed. ".__METHOD__);
            }
            return;

    }

}
