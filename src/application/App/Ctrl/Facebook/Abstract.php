<?php
/**
 * App_Ctrl_Facebook_Abstract
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Ctrl_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Ctrl_Facebook_Abstract
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Ctrl_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class App_Ctrl_Facebook_Abstract
{

    const ERROR_LOGIN = "LOGIN";


     /**
    * @var bool
    */
    protected $_isRunning;
    /**
     * @var App_View_Facebook_Abstract
     */
    protected $_view;

    /**
     * @var string
     */
    protected $_pageType; //e.g. Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS;
/*
    protected $_applicationContext;

    protected $_facebook;
*/

/*
    protected $_facebookUserId;
    protected $_facebookMe;

    protected $_facebookLoginUrl;

    protected $_facebookSession;


    protected $_facebookSignedRequestDecoded;
*/

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }

    /**
     * @return App_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }

     /**
	 * @return App_Registry
	 */
	public function getRegistry()
	{
		return Bootstrap::getRegistry();
	}

	/**
     * @return App_Facebook_Db_Xdb_Client
     */
	public function getDbClient()
	{
		return $this->getApplication()->getDbClient();
	}


    /**
     * @return App_Facebook_Session
     */
    public function getSession()
    {
        return $this->getApplication()->getSession();
    }

    /**
     * @return string
     */
    public function getCurrentDate()
    {
        return $this->getApplication()->getCurrentDate();
    }

    /**
     * @param null|string $path
     * @return string
     */
    public function getSrcPath($path = null)
    {
        return $this->getApplication()->getSrcPath($path);
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getUrl($url)
    {
        return $this->getApplication()->getUrl($url);
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getUrlLocal($url)
    {
        return $this->getApplication()->getUrlLocal($url);
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getUrlGlobal($url)
    {
        return $this->getApplication()->getUrlGlobal($url);
    }

    /**
     * @return App_Debug
     */
    public function getDebug()
    {
        return $this->getApplication()->getDebug();
    }


    /**
     * @return App_Model_Bo_Fb_User
     */
    public function getViewerBO()
    {
        return $this->getApplication()->getViewerBO();
    }

    /**
     * @param null|string $imageFormat
     * @return App_Model_Dto_Person
     */
    public function getViewerDTO($imageFormat=null)
    {
        return $this->getApplication()->getViewerBO()
                ->newPersonDto($imageFormat);
    }

    /**
     * @param null|string $imageFormat
     * @return App_Model_Dto_Person
     */
    public function getViewerDTOIfExists($imageFormat=null)
    {
        return $this->getApplication()->getViewerBO()
                ->newPersonDtoIfExists($imageFormat);
    }

    /**
     * @return App_Model_Bo_Vz_User
     */
    /*
    public function getViewerBO()
    {
        return $this->getApplication()->getViewerBO();
    }
    */


    // ++++++++++++++++++++++++ rpc config ++++++++++++++++++++++++++++++++
    public function getRpcConfig()
    {
        $serviceNamespace = "Fb";
        $gatewayUrl = $this->getUrl("rpc/json.php");
        $useJavascriptProxy = false;
        /*
        $useJavascriptProxy = $this->getDebug()
                ->isServerStageProduction();
        if ($this->getApplication()->isVzApplication() !== true) {
            $useJavascriptProxy = false;
        }
        */

        $isDebugMode = $this->getDebug()
                ->isDebugMode();


        $currentUrl = null;
        try {
            $currentUri = $this->getFacebook()->getConfig()->newUri(null);
            $currentUri->setUri($currentUri->getCurrentUrl());
            $currentUrl = $currentUri->toString(null);
        } catch (Exception $e) {
            //NOP
        }


        $rpcClientId = md5(
            rand(0, 32000)."_".microtime(true)."_".rand(0, 32000)
        );


        $baseParams = array(
                    "clientId" => $rpcClientId,
                    "page" => array(
                        "type" => $this->getPageType(),
                        "url" => $currentUrl,
                    ),
            //"signed_request" => Lib_Utils_Array::getProperty($_REQUEST, "signed_request"),
            //"_signed_request"=>$this->getFacebook()->getSignedRequest(),
                    /*
                    "session" => $this->getController()
                            ->getFacebookSession(),
                    "userId" => $this->getController()
                            ->getFacebookUserId(),

                     */
                );

        $result = array(
            "js" => array(
                "serviceNamespace" => $serviceNamespace,
                "gatewayUrl" => $gatewayUrl,
                "baseParams" => $baseParams,

                "useProxy" => ($useJavascriptProxy===true),

            ),
            "flash" => array(
                "serviceNamespace" => $serviceNamespace,
                "gatewayUrl" => $gatewayUrl,
                "baseParams" => $baseParams,
                "isDebugMode" => ($isDebugMode===true),
            ),
        );

        return $result;
    }









    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
    * @return void
    */
    public function run()
    {

        $application = $this->getApplication();

        

        try {
            if ($this->_isRunning ===true) {
               throw new Exception("Is already running ".get_class($this));
            }
            $this->_isRunning = true;

            $pageConfig = $this->getPageConfig();
            if ($pageConfig->enabled !== true) {
                throw new Exception("PageType is not enabled at ".__METHOD__);
            }

            // auth required?
            $isAuthRequired = ($pageConfig->isAuthRequired===true);
            $authError = null;
            try {
                if ($isAuthRequired) {
                    $this->requireAuth();
                }
            } catch(Exception $e) {

                $authError = $e;
                if ($authError->getMessage() !== self::ERROR_LOGIN) {
                    throw $e;
                }
            }


            if ($authError instanceof Exception) {
                $this->_onAuthFailed($authError);
                return;
            }


            if ($isAuthRequired === true) {
                $this->_onAuthComplete($application->getFacebookMe());
            }

            $this->renderView();
            return;


        } catch(Exception $e) {
            throw $e; //trigger Bootstrap global error handler
        }

                
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return App_View_Facebook_Abstract
     */
    public function getView()
    {
        if (($this->_view instanceof App_View_Facebook_Abstract) !== true) {
            $this->_view = $this->newView();
        }
        return $this->_view;
    }

    /**
     * @return string
     */
    public function getViewClassName()
    {
        $controllerClass = get_class($this);
        /*
        $viewClass = Lib_Utils_String::removeSuffixByDelimiter(
            $controllerClass,
            "_"
        )."_View";

         */
        $viewClass = str_replace("App_Ctrl_","App_View_", $controllerClass);
        return $viewClass;
    }

    /**
     * @throws Exception
     * @return App_View_Facebook_Abstract
     */


    public function newView()
    {

        $viewClass = $this->getViewClassName();

        if (class_exists($viewClass)!==true) {
            throw new Exception(
                "ViewClass does not exist! viewClass=".$viewClass." at "
                        .__METHOD__
            );
        }
        $view = new $viewClass();

        return $view;
    }

    /**
     * @return void
     */
    public function renderView()
    {
        $view = $this->getView();

        $this->onBeforeRenderView($view);
        $view->render($this);

    }

    /**
    * @param App_View_Facebook_Abstract $view
    * @return void
    */


    public function onBeforeRenderView(App_View_Facebook_Abstract $view)
    {
        // your hooks here

        $application = $this->getApplication();

        //
        if (isset($_GET['request_ids'])){
            //user comes from invitation
            //track them if you need
        }


        $hasFacebookMe = $this->getApplication()->hasFacebookMe();
        $me = $this->getApplication()->getFacebookMe();


/*
        $this->getDebug()->dumpVar(
            __METHOD__,
            array(
                "hasMe" => $hasFacebookMe,
                "me" => $me,
                "signedRequest" =>
                $application->getFacebookSignedRequestDecoded()
            )
        );
*/
        return;



    }


    // ++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return string
     */
    public function getPageType()
    {
        return $this->_pageType;
    }


    /**
     * @return Zend_Config
     */
    public function getPageConfig()
    {
        $pageType = $this->getPageType();
        $pageConfig = $this->getFacebook()
                ->getConfig()
                ->getMvcConfigByPageType($pageType);
        return $pageConfig;
    }
    /**
     * @return Zend_Config
     */
    public function getPagesConfig()
    {
        $result = $this->getFacebook()->getConfig()->getMvcConfig();
        return $result;
    }

    /**
     * @return Zend_Config
     */
    public function getPagesConfigParsed()
    {
        $types = array(
            App_Facebook_Config::ENVIRONMENT_TYPE_CANVAS,
            App_Facebook_Config::ENVIRONMENT_TYPE_TAB,
            App_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
        );


        $result = array(
        );

        foreach($types as $pageType) {
            $result[$pageType] = $this->getFacebook()
                ->getConfig()
                ->getMvcConfigByPageType($pageType)
                ->toArray();
        }


        return new Zend_Config($result);
    }


    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return void
     */
    protected function _requireFacebookMe()
    {
        $application = $this->getApplication();
        $application->requireFacebookMe();
    }

    /**
     * @throws FacebookApiException|Lib_Application_Exception
     * @return void
     */
    public function requireAuth()
    {

        $application = $this->getApplication();

        if ((isset($_GET['code'])) && (!empty($_GET["code"]))) {

            $this->_onFacebookAuthCodeReceived();
        }

        $application->destroyViewerBO();
        $application->destroyFacebookMe();

        $this->_requireFacebookMe();

    }


    /**
     * @throws Lib_Application_Exception
     * @return void
     */
    protected function _onFacebookAuthCodeReceived()
    {
        /*
        * If user first time authenticated the application facebook
        * redirects user to baseUrl, so I checked if any code passed
        * then redirect him to the application url
        * -mahmud
        */
        if ((isset($_GET['code'])) && (!empty($_GET["code"]))) {


            $pageUri = $this->getFacebook()
                    ->getConfig()
                    ->newUriByPageType($this->getPageType());



            $currentUri = new Lib_Url_Uri($pageUri->getCurrentUrl());
            $currentUri->setFragment(null);

            // clone all custom queryParameters (matching "meetidaaa_")
            $pageUri->setQueryParameters($currentUri->getQueryParameters());
            $pageUriQueryParamsFilter = $pageUri->newFilter();
            $pageUriQueryParamsFilter
                    ->setWhitelist(array(
                                    "meetidaaa_*",
                                  ));

            $pageUri->filterQueryParameters($pageUriQueryParamsFilter);
            if ($pageUri->hasValidUri()!==true) {
                $pageUri = new Lib_Url_Uri($pageUri->getCurrentUrl());
            }

            $redirectUrl = $pageUri->toString($currentUri->getScheme());
            echo("<script>
                    try {
                        top.location.href='" . $redirectUrl . "';
                    } catch(e) {
                        throw e;
                    }
                    </script>");

            exit;
        }
    }


    /**
     * @param  array $me
     * @return void
     */
    protected function _onAuthComplete($me)
    {

        $application = $this->getApplication();
        $meId = Lib_Utils_Array::getProperty($me, "id");

        // this case should never happen...
        if (is_array($me)!==true) {
            throw new Exception(
                "Invalid parameter me must be array at ".__METHOD__
            );
        }
        if ($application->getFacebook()->isValidUserId($meId)!==true) {
            throw new Exception(
                "Invalid parameter me.id at ".__METHOD__
            );
        }

        $application->setFacebookMe($me);
        if ($application->hasFacebookMe() !== true) {
            throw new Exception(
                "Invalid application.hasFacebookMe() at ".__METHOD__
            );
        }


        $application->destroyViewerBO();
        $managerPerson = $this->getApplication()->getManagerPerson();
        $managerPerson->autoRegisterFacebookUserByFacebookMe($me);
        $viewerBO = $application->newViewerBO();
        $viewerBO->setExternalKey($meId);
        $application->setViewerBO($viewerBO);



        $me = $this->getApplication()->getFacebookMe();
        $hasFacebookMe = $this->getApplication()->hasFacebookMe();





    }

    /**
     * @return null|string
     */
    public function getLoginScope()
    {
        $facebook = $this->getFacebook();
        $loginScope = $facebook->getConfig()->getLoginScope();
        return $loginScope;
    }
    /**
     * @param  array|null $params
     * @return string
     */
    public function getLoginUrl($params=null)
    {
        return $this->getApplication()->getFacebook()->getLoginUrl($params);
    }

    /**
     * @param null $params
     * @return string
     */
    public function getLogoutUrl($params=null)
    {
        if ($params===null) {
            $params = array();
        }
        return $this->getApplication()->getFacebook()->getLogoutUrl($params);
    }

    /**
     * @param Exception $e
     * @return void
     */
    protected function _onAuthFailed(Exception $e)
    {

        /*
        $this->getApplication()->getDebug()->dumpVar(__METHOD__, $e);

        */
        if ($e->getMessage() === self::ERROR_LOGIN) {
            $this->login();
            return;
        } else {
            throw $e;
        }

    }

    /**
     * @return void
     */
    public function login()
    {
        $facebook = $this->getFacebook();
        /*
        $loginScope = $facebook->getConfig()->getLoginScope();

        $loginUrl   = $facebook->getLoginUrl(
                array(
                    'scope'         => $loginScope,
                )
        );
        */
        $params = null;
        $loginUrl = $this->getLoginUrl($params);

        $target = "top.location.href";
        echo "<script type='text/javascript'>"
             . $target ." = '". $loginUrl. "';</script>";
    }



    /**
     * @param string $type
     * @return bool
     */
    /*
    public function isPageTypeEnabled($type)
    {
        $pageConfig = $this->getFacebook()
                ->getConfig()
                ->getEnvironmentConfigByType($type);
        $enabled = false;
        if ((is_array($pageConfig)) && (isset($pageConfig["enabled"]))) {
            $enabled = $pageConfig["enabled"];
        }

        return (bool)($enabled===true);
    }

    */
    /**
     * @throws Exception
     * @return 
     */
    /*
    public function requirePageTypeIsEnabled()
    {
        if ($this->isPageTypeEnabled($this->getPageType())) {
            return;
        }

        // redirect to a fallback pageType
        $fallbackOrder = array();
        switch($this->getPageType()) {

            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS: {
                // we have canvas, but canvas is not enabled
                $fallbackOrder = array(
                    Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB,
                    Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
                );
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB: {
                // we have canvas, but canvas is not enabled
                $fallbackOrder = array(
                    Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS,
                    Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
                );
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT: {
                $fallbackOrder = array(
                    Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS,
                    Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB,
                );
                break;
            }
            default: {
                throw new Exception(
                    "Invalid pageType at ".__METHOD__." for ".get_class($this)
                );
            }

        }

        if (Lib_Utils_Array::isEmpty($fallbackOrder)) {
            throw new Exception(
                "PageType =".$this->getPageType()." is not enabled at "
                        . __METHOD__." for ".get_class($this)
                        . " and there is no fallback defined. "
            );
        }

        foreach($fallbackOrder as $fallbackPageType) {

            if ($this->isPageTypeEnabled($fallbackPageType)) {
                $this->dumpVar(
                    __METHOD__,
                    "NOTICE pageType ".$this->getPageType()." IS DISABLED HERE"
                    ." at ".__METHOD__." for ".get_class($this)
                    ." TRYING TO REDIRECT TO A FALLBACK PAGE TYPE."
                );

                $fallbackPageConfig = $this->getFacebook()
                    ->getConfig()
                    ->getEnvironmentConfigByType($fallbackPageType);
                $fallbackPageUrl = Lib_Utils_Array::getProperty(
                    $fallbackPageConfig,
                    "url"
                );
                if (Lib_Utils_String::isEmpty($fallbackPageUrl)) {
                    throw new Exception(
                        "fallbackPageUrl for pageType=".$fallbackPageType
                                . " cant be empty at "
                                . __METHOD__." for ".get_class($this));
                }

                switch($fallbackPageType) {
                    case Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS: {
                        $this->redirect(
                               $fallbackPageUrl,
                               "window.parent.location",
                               false
                        );
                        exit;
                        break;
                    }
                    case Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB: {
                        $this->redirect(
                               html_entity_decode(
                                   $fallbackPageUrl
                               ),
                               "window.parent.location",
                               false
                        );
                        exit;
                        break;
                    }

                    case Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT: {
                        $this->redirect(
                               html_entity_decode(
                                   $fallbackPageUrl
                               ),
                               "window.parent.location",
                               false
                        );
                        exit;
                        break;
                    }

                    default: {
                        $this->dumpVar(
                            __METHOD__,
                            "Invalid fallbackPageType=".$fallbackPageType
                        );
                        break;
                    }
                }
            }


        }


        throw new Exception(
            "PageType =".$this->getPageType()." is not enabled at "
                        . __METHOD__."(".__LINE__.")"." for ".get_class($this)
                        . " and there is no valid fallback defined. "
        );

    }
*/



    /**
     * @return string
     */
/*
    public function getFriendSelectorInviteUrl()
    {
        //return "FRIEND_SELECTOR_INVITE_URL";

        switch($this->getPageType()) {

            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS: {
                return $this->getUrl("fb/invite/canvas.php");
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB: {
                return $this->getUrl("fb/invite/tab.php");
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT: {
                return $this->getUrl("fb/invite/connect.php");
                break;
            }
            default: {
                throw new Exception(
                    "Invalid pageType at ".__METHOD__." for ".get_class($this)
                );
            }

        }

        
    }

*/

    /**
     * @return string
     */
    /*
    public function getMvcRoot()
    {
        $mvcRoot = dirname(dirname(__FILE__));
        return $mvcRoot;
    }
    */
    /**
     * @param  mixed $var
     * @param bool $exit
     * @return void
     */
    /*
    public function dumpVar($var, $exit = false)
    {
        $this->getApplicationContext()->dumpVar($var, $exit);
    }
*/



    /**
     * @param  string $url
     * @param string $target
     * @param bool $exit
     * @return void
     */
    /*
    public function redirect(
        $url,
        $target='window.parent.location',
        $exit = false
    )
    {
        $defaultTarget = "window.parent.location";
        if ($target === null) {
            $target = $defaultTarget;
        }
        echo "<script>".$target."=\"".$url."\"</script>";
        if ($exit === true) {
            exit;
        }
    }
    */

    /**
     * @param  string $method
     * @param  mixed $var
     * @param bool $exit
     * @return void
     */
    /*
    public function dumpVar($method, $var, $exit = false)
    {
        return App_Debug::getInstance()->dumpVar($method, $var, $exit);
    }
    */
    /**
     * @return Browser
     */
    /*
    public function getBrowser()
    {
        return new Browser();
    }
    */
    /**
     * @return string
     */

    /*
    public function getBrowserName()
    {
        return $this->getBrowser()->getBrowser();
    }
    */

    /**
     * @param null|string $relativePath
     * @return string
     */
    /*
    public function getSrcPath($relativePath = null)
    {
        $path = SRC_PATH;
        if (is_string($relativePath)) {
            if (Lib_Utils_String::startsWith($relativePath, "/", false)) {
                $path .= $relativePath;
            } else {
                $path .= "/".$relativePath;
            }
        }
        return $path;
    }
    */

    /**
     * @return App_Debug
     */
    /*
    public function getDebug()
    {
        return Bootstrap::getRegistry()->getDebug();
    }
    */
    /**
     * @return bool
     */
    /*
    public function isDebugMode()
    {
        return $this->getDebug()->isDebugMode();
    }

    */
    /**
     * @param  string $url
     * @return string
     */
    /*
    public function getUrl($url)
    {
        return Bootstrap::getRegistry()->getUrl($url);
    }
    */






    /**
     * @return Lib_Facebook_Facebook
     */
    /*
    public function getFacebook()
    {
        return Bootstrap::getRegistry()->getFacebook();
    }
    */

    /**
     * @return string
     */

    /*
    public function getFacebookUserId()
    {
        return $this->_facebookUserId;
    }
    */
    /**
     * @return array|null
     */

    /*
    public function getFacebookMe()
    {
        return $this->_facebookMe;
    }
    */

    /**
     * @return array|null
     */

    /*
    public function getFacebookSession()
    {
        return $this->_facebookSession;
    }
    */
    /**
     * @return array
     */

    /*
    public function getFacebookLoginUrlParams()
    {
        return
            array(
                   'canvas' => 1,
                   'fbconnect' => 0,
                   'req_perms' => $this->getFacebook()
                           ->getConfig()
                           ->getLoginScope(),
                   //'next'=>'http://www.facebook.com/pages/Fanpagedemo/169915089688960?v=app_136010423114944',
                   );
    }

    */

    /**
     * @return string
     */

    /*
    public function getFacebookLoginUrl()
    {
        return $this->_facebookLoginUrl;
    }
    */

    /**
     * @param null|string $target
     * @param null|array $params
     * @return void
     */
    /*
    public function login($target = null, $params = null)
    {
        $loginUrlTarget = "window.parent.location";
        $loginParams = $this->getFacebookLoginUrlParams();
        if ($target === null) {
            $target = $loginUrlTarget;
        }
        if ($params === null) {
            $params = $loginParams;
        }

        $facebook = $this->getFacebook();
        // fb login url
        $loginUrl = $facebook->getLoginUrl(
            $params
        );

        // redirect to auth url
        $this->redirect(
                        html_entity_decode($loginUrl),
                        $target,
                        false
                   );

                   exit;

    }
    */


    /**
     * @return array
     */

    /*
    public function getFacebookSignedRequestDecoded()
    {
        if (is_array($this->_facebookSignedRequestDecoded)!==true) {
            $this->_facebookSignedRequestDecoded =
                    $this->decodeFacebookRequestSigned();
        }
        return $this->_facebookSignedRequestDecoded;
    }
    */

     /**
     * @return array
     */

    /*
    public function decodeFacebookRequestSigned()
    {

        $result = array();
        $signed_request = Lib_Utils_Array::getProperty(
            $_REQUEST,
            "signed_request"
        );
        if (is_string($signed_request)!==true) {
            //var_dump(__LINE__);
            return $result;
        }

        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $payload = base64_decode(strtr($payload, '-_', '+/'));
        $payload = json_decode($payload, true);
        $data = $payload;


        /*
        {
           "algorithm":"HMAC-SHA256",
           "expires":1298376000,
           "issued_at":1298371255,
           "oauth_token":"113630448712169|2.EAOvCZqFK_p6fP_eCFgZMA__.3600.1298376000-1013680688|Xfp4rrNRf4DIGfgeep-Yueu5JUw",
           "user":{
              "country":"de",
              "locale":"en_GB",
              "age":{
                 "min":21
              }
           },
           "user_id":"1013680688"
        }

         */
    /*
        $user = array(
            "id" => null,
        );
        if (isset($data["user"])) {
            if (is_array($data["user"])) {
                $user = $data["user"];
            }
            if (isset($data["user_id"])) {
                $user["id"] = $data["user_id"];
            }
        }

        if (is_array($data) !== true) {
            $data = array();
        }

        return $data;
    }
    */
    /**
     * @return array
     */
    /*
    public function getFacebookPageFromSignedRequest()
    {
        $page = array(
            "id" => null,
            "liked" => null,
            "admin" => null,
        );
        $signedRequest = (array)$this->getFacebookSignedRequestDecoded();
        $_page = Lib_Utils_Array::getProperty($signedRequest, "page");
        if (is_array($_page)) {
            foreach($_page as $key => $value) {
                $page[$key] = $value;
            }
        }
        return $page;
    }
    */

    /**
     * @return int
     */
    /*
    public function getFacebookPageId()
    {
        $page = $this->getFacebookPageFromSignedRequest();
        $id = (int)Lib_Utils_Array::getProperty($page, "id");
        return $id;
    }
    */

    /**
     * @return bool
     */

    /*
    public function isFacebookPageLiked()
    {
        $page = $this->getFacebookPageFromSignedRequest();
        $is = (bool)Lib_Utils_Array::getProperty($page, "liked");
        return $is;
    }

    */
    /**
     * @return bool
     */
    /*
    public function isFacebookPageAdmin()
    {
        $page = $this->getFacebookPageFromSignedRequest();
        $is = (bool)Lib_Utils_Array::getProperty($page, "admin");
        return $is;
    }

    */
    /**
     * @return bool
     */
    /*
    public function isFacebookAppJustInstalled()
    {
        $installed = (int)Lib_Utils_Array::getProperty($_REQUEST,"installed");
        return ($installed===1);
    }
*/


    /**
     * @return array
     */
    /*
    public function getGatewayBaseParams()
    {
        $baseParams = (array)$this->getApplicationContext()
                ->getGatewayBaseParams();
        $baseParams["session"] = $this->getFacebookSession();
        return $baseParams;
    }
*/

    /**
     * @return string
     */
    /*
    public function getGatewayServiceNamespace()
    {
        return $this->getApplicationContext()->getGatewayServiceNamespace();
    }
*/


    /**
     * @var bool
     */
    //protected $_authComplete;
    /**
     * you may want to override in subclasses
     * @return void
     */

    /*

    public function onAuthComplete()
    {
        if ($this->_authComplete === true) {
            throw new Exception(
                "auth already complete at ".__METHOD__." for ".get_class($this)
            );
        }
        $this->_authComplete = true;
        
        $this->renderView();

    }
*/





    /**
     * @var bool
     */
    //protected $_authFailed;
    /**
     * @return void
     */
    /**
    public function onAuthFailed()
    {
        if ($this->_authFailed === true) {
            throw new Exception(
                "auth already failed at ".__METHOD__." for ".get_class($this)
            );
        }
        // we dont have a viewer

        if($this->getBrowserName() == 'Safari'
            and isset($_REQUEST['fb_sig_added'])
                    and $_REQUEST['fb_sig_added'] == 1)
        {
            # go to enable cookies instruction page
            //redirect(APP_URL.'compatibility.php');
            $this->dumpVar(
               __METHOD__,
               "safari compatibility",
               false
            );
        } else {
            $this->login(null, null);
        }
    }
*/

    /**
     * @throws Exception
     * @return
     */
    /*
    public function requireAuth()
    {
        $facebook = $this->getFacebook();


        // fb signed request
        $this->_facebookSignedRequestDecoded =
               $this->decodeFacebookRequestSigned();

        // fb login url
        $loginUrl = $facebook->getLoginUrl(
            $this->getFacebookLoginUrlParams()
        );
        $this->_facebookLoginUrl = $loginUrl;


        // fb session
        $session = $facebook->getSession();
        $this->_facebookSession = $session;

        // fb me
        $me = null;
        $userId = null;
        if($session)
        {
           try
           {
               $userId = $facebook->getUser();
               $me = $facebook->api('/me');
               $this->_facebookMe = $me;

           }
           catch(FacebookApiException $e)
           {
               $this->dumpVar(__METHOD__, $e);
               throw new Exception("LOGIN");
           }
        }
        $this->_facebookUserId = $userId;

        if($me) {

            // ok, we have a viewer and viewer's session

           // tab apps hack: fix facebook bug for tab apps
           // so der hack für das redirect problem beim installieren
           // von tab apps, direct nach dem install
           // (app wird außerhalb der page angezeigt)
           if ($this->getPageType() ===
                   Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB) {

               if ($this->isFacebookAppJustInstalled() === true) {
                    $facebookPageId = (int)$this->getFacebookPageId();
                    if (($facebookPageId>0)!==true) {
                        // so jetzt haben wir den fall (canvas statt page)
                        $this->redirect(
                          html_entity_decode(
                              $this->getFacebook()
                                      ->getConfig()
                                      ->getFanPageTabUrl()
                          ),
                          "window.parent.location",
                          false
                        );

                        exit;
                    }
               }
           }


            return;
        }

        throw new Exception("LOGIN");
    }
    */


    /**
     * @var bool
     */
    //protected $_isFanpageLiked;
    /**
     * NOTICE: FB BUGS? we dont delegate exceptions
     * @throws Exception
     * @return bool
     */
    /*
    public function isFanpageLiked()
    {

        if (is_bool($this->_isFanpageLiked)) {
            return $this->_isFanpageLiked;
        }

        $fanpageId = $this->getFacebook()->getConfig()->getFanPageId();
        if ((((int)($fanpageId))>0) !== true) {
            throw new Exception(
                "Invalid config! fanpageId must be int/str>0 at "
                        . __METHOD__." for ".get_class($this)
            );
        }

        $pageId = $this->getFacebookPageId();
        if ( ((int)$pageId) === ((int)$fanpageId) ) {
            $liked = $this->isFacebookPageLiked();
            if (is_bool($liked)) {
                $this->_isFanpageLiked = $liked;
                return $this->_isFanpageLiked;
            }
        }

        // get by api call
        $userId = $this->getFacebookUserId();
        try {
            $liked = $this->getFacebook()->isUserFanOfPage($fanpageId, $userId);
        } catch(Exception $e) {



            $this->dumpVar(__METHOD__, $e);

            // dirty !!!!!!!!!!!!!!!!!
            $liked = true;
            //throw $e;
        }

        if (is_bool($liked)) {
            $this->_isFanpageLiked = $liked;
        }

        return (bool)$this->_isFanpageLiked;
    }
    */





    /**
     * @throws Exception
     * @return void
     */
    /*
    public function autoRegisterFacebookUser()
    {
        $this->dumpVar(__METHOD__, "");
        $me = $this->getFacebookMe();
        /*

         me = array(9) {
            ["id"]=> string(15) "100001680154141"
            ["name"]=> string(11) "Seb Basinet"
            ["first_name"]=> string(3) "Seb"          
            ["last_name"]=> string(7) "Basinet"
            ["link"]=> string(54) "http://www.facebook.com/profile.php?id=100001680154141"
            ["gender"]=> string(4) "male"
            ["timezone"]=> int(1)
            ["locale"]=> string(5) "en_GB"
            ["updated_time"]=> string(24) "2011-03-19T16:45:55+0000" }

         */
    /*
        $manager = App_Manager_Person::getInstance();
//try {
        $manager->autoRegisterFacebookUser();
        /*
}catch(Exception $e) {
    $this->dumpVar(__METHOD__, "autoregister failed");
    var_dump($e);
    exit;
}
         
         */
        /*
        $userBO = new App_Model_Bo_User();
        $userBO->setExternalKey($externalKey);

        $this->dumpVar(
            __METHOD__,
            array(
                "personRecord" => $userBO->getPersonRecord(),
                "fbPersonRecord" => $userBO->getFbPersonRecord()
            )
        );
         
         */
/*
    }

*/


}


