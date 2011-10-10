<?php
/**
 * App_Ctrl_Facebook_CtrlInviteAbstract
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
 * App_Ctrl_Facebook_CtrlInviteAbstract
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
 
class App_Ctrl_Facebook_CtrlInviteAbstract
{



    protected $_redirectUrl;
    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        if (Lib_Utils_String::isEmpty($this->_redirectUrl) !== true) {
            return $this->_redirectUrl;
        }



        switch($this->getPageType()) {

            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS: {
                $this->_redirectUrl = $this->getFacebook()
                        ->getConfig()
                        ->getContentUrlByPageType(
                    $this->getPageType()
                );
                return $this->_redirectUrl;
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB: {
                $this->_redirectUrl = $this->getFacebook()
                        ->getConfig()
                        ->getContentUrlByPageType(
                    $this->getPageType()
                );
                return $this->_redirectUrl;
                break;
            }

            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT: {
                $this->_redirectUrl = $this->getFacebook()
                        ->getConfig()
                        ->getContentUrlByPageType(
                    $this->getPageType()
                );
                return $this->_redirectUrl;
                break;
            }


            default: {
                throw new Exception(
                    "Invalid pageType at ".__METHOD__." for ".get_class($this)
                );
            }

        }



    }






    protected $_isChoiceComplete;

    /**
     * @return bool
     */
    public function isChoiceComplete()
    {
        return (bool)($this->_isChoiceComplete === true);
    }


    protected $_selectedIds;
    /**
     * @return array
     */
    public function getSelectedIds()
    {
        if (is_array($this->_selectedIds)!==true) {
            $this->_selectedIds = array();
        }
        return $this->_selectedIds;
    }






    /**
     * @var App_View_Facebook_ViewInviteAbstract
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
    protected $_facebookUserId;
    protected $_facebookMe;

    protected $_facebookLoginUrl;

    protected $_facebookSession;


    protected $_facebookSignedRequestDecoded;




    /**
     * @return App_View_Facebook_ViewInviteAbstract
     */
    public function getView()
    {
        if (($this->_view instanceof App_View_Facebook_ViewInviteAbstract) !== true) {
            $this->_view = $this->newView();
        }
        return $this->_view;
    }


    /**
     * @return string
     */
    public function getPageType()
    {
        return $this->_pageType;
    }

    /**
     * @param string $type
     * @return bool
     */
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

    /**
     * @throws Exception
     * @return 
     */
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




    /**
     * that is required for construction the friendselector.form.action endpoint,
     * so we just try to reload the current script
     * @return string
     */

    public function getFriendSelectorInviteUrl()
    {
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
     * @param  string $url
     * @param string $target
     * @param bool $exit
     * @return void
     */
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


    /**
     * @param  string $method
     * @param  mixed $var
     * @param bool $exit
     * @return void
     */
    public function dumpVar($method, $var, $exit = false)
    {
        return App_Debug::getInstance()->dumpVar($method, $var, $exit);
    }

    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return new Browser();
    }

    /**
     * @return string
     */
    public function getBrowserName()
    {
        return $this->getBrowser()->getBrowser();
    }


    /**
     * @param null|string $relativePath
     * @return string
     */
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


    /**
     * @return App_Debug
     */
    public function getDebug()
    {
        return Bootstrap::getRegistry()->getDebug();
    }

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->getDebug()->isDebugMode();
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getUrl($url)
    {
        return Bootstrap::getRegistry()->getUrl($url);
    }

    /**
     * @throws Exception
     * @return mvc_Abstract_View
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
     * @param App_View_Facebook_ViewInviteAbstract $view
     * @return void
     */

     public function onBeforeRenderView(App_View_Facebook_ViewInviteAbstract $view)
     {
        // your hooks here


        $this->dumpVar(__METHOD__," redirectUrl=".$this->getRedirectUrl());

        //$this->dumpVar(__METHOD__, json_encode($_POST));
        //{"user_id":"12345","mfs_typeahead_req_form_4d664c7647e8b8f77929114":"seb","ids":["100001680154141"]}
      // CHECK DEN POST
      //array(3) { ["user_id"]=> string(5) "12345" ["mfs_typeahead_req_form_4d664b5c413262278321956"]=> string(3) "seb" ["ids"]=> array(1) { [0]=> string(15) "100001680154141" } }

        $isDone = false;
        if (isset($_GET["choiceComplete"])) {
          $isDone = true;
        }
        $this->_isChoiceComplete = $isDone;




        if ($this->isChoiceComplete()) {
            $this->onChoiceComplete();
        }



     }



    /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return Bootstrap::getRegistry()->getFacebook();
    }


    /**
     * @return string
     */

    public function getFacebookUserId()
    {
        return $this->_facebookUserId;
    }

    /**
     * @return array|null
     */

    public function getFacebookMe()
    {
        return $this->_facebookMe;
    }

    /**
     * @return array|null
     */

    public function getFacebookSession()
    {
        return $this->_facebookSession;
    }

    /**
     * @return array
     */

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


    /**
     * @return string
     */

    public function getFacebookLoginUrl()
    {
        return $this->_facebookLoginUrl;
    }

    /**
     * @param null|string $target
     * @param null|array $params
     * @return void
     */
    public function login($target = null, $params = null)
    {
        $loginUrlTarget = "window.parent.location";

        switch($this->getPageType()) {

            case Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB: {
                $loginUrlTarget = "window.parent.location";
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT: {
                $loginUrlTarget = "window.location";
                break;
            }
            case Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS: {
                $loginUrlTarget = "window.location";
                break;
            }

            default: {
                $loginUrlTarget = "window.parent.location";
                break;
            }
        }

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



    /**
     * @return array
     */

    public function getFacebookSignedRequestDecoded()
    {
        if (is_array($this->_facebookSignedRequestDecoded)!==true) {
            $this->_facebookSignedRequestDecoded =
                    $this->decodeFacebookRequestSigned();
        }
        return $this->_facebookSignedRequestDecoded;
    }

     /**
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @return int
     */
    public function getFacebookPageId()
    {
        $page = $this->getFacebookPageFromSignedRequest();
        $id = (int)Lib_Utils_Array::getProperty($page, "id");
        return $id;
    }

    /**
     * @return bool
     */

    public function isFacebookPageLiked()
    {
        $page = $this->getFacebookPageFromSignedRequest();
        $is = (bool)Lib_Utils_Array::getProperty($page, "liked");
        return $is;
    }

    /**
     * @return bool
     */
    public function isFacebookPageAdmin()
    {
        $page = $this->getFacebookPageFromSignedRequest();
        $is = (bool)Lib_Utils_Array::getProperty($page, "admin");
        return $is;
    }

    /**
     * @return bool
     */
    public function isFacebookAppJustInstalled()
    {
        $installed = (int)Lib_Utils_Array::getProperty($_REQUEST,"installed");
        return ($installed===1);
    }



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
    protected $_authComplete;
    /**
     * you may want to override in subclasses
     * @return void
     */

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
     * @var bool
     */
    protected $_authFailed;
    /**
     * @return void
     */
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


    /**
     * @throws Exception
     * @return
     */
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



    /**
     * @var bool
     */
    protected $_isFanpageLiked;
    /**
     * @throws Exception
     * @return bool
     */
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
            throw $e;
        }

        if (is_bool($liked)) {
            $this->_isFanpageLiked = $liked;
        }

        return (bool)$this->_isFanpageLiked;
    }



    /**
     * @var bool
     */
    protected $_isRunning;
    /**
     * @return void
     */
    public function run()
    {
//$this->dumpVar(__METHOD__,__LINE__);
        if ($this->_isRunning ===true) {
            throw new Exception("Is alreay running ".get_class($this));
        }
        $this->_isRunning = true;
       try{

           try {
               $this->requireAuth();
           } catch(Exception $e) {
              // $this->dumpVar(__METHOD__,__LINE__); exit;

               if ($e->getMessage() === "LOGIN") {
                   //$this->dumpVar(__METHOD__,"AUTH FAILED".__LINE__); exit;
                   $this->onAuthFailed();
                   return;
               } else {
                   throw $e;
               }
           }

           $this->onAuthComplete();


       }catch(Exception $e) {
           $this->dumpVar(__METHOD__, $e);
           throw $e;
       }

    }



    /**
     * @throws Exception
     * @return void
     */
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

    }







    // +++++++++++++++ callbacks




    /**
     * USER SLECTED FRIENDS or CANCELLED SELECTION
     * @return void
     */
    public function onChoiceComplete()
    {
       $this->dumpVar(__METHOD__,json_encode($_POST));


        $selectedIds = array();
        if (isset($_POST["ids"])) {
          $selectedIds = $_POST["ids"];
          if (is_array($selectedIds)!==true) {
              $selectedIds = array();
          }
        }
        $this->_selectedIds = $selectedIds;


        $selectedIds = $this->getSelectedIds();
        if (count($selectedIds)>0) {
            $this->onFriendsSelected($selectedIds);
        } else {
            $this->onFriendsSelectionCanceled();
        }
    }


    /**
     * @param  array $selectedIds
     * @return void
     */
    public function saveSelectedFriends($selectedIds)
    {
        // store to db
        if (is_array($selectedIds)!==true) {
            $selectedIds = array();
        }



        // do sth. eg: store to db

        $externalKey = $this->getFacebookUserId();
        $userBO = new App_Model_Bo_User();
        $userBO->setExternalKey($externalKey);
        $personId = null;
        if ($userBO->getPersonRecord()->exists()) {
            $personId = $userBO->getPersonRecord()->getId();
        }

        if (App_Manager_Person::getInstance()->isValidId($personId) !== true) {
            return;
        }

        $personId = (int)$personId;
        $oldInviteRow = App_Manager_PersonInvite::getInstance()
                ->loadRow($personId);
        $oldExternalKeyList = Lib_Utils_Array::getProperty(
            $oldInviteRow,
            "friendsExternalKeyList"
        );
        if (is_string($oldExternalKeyList)) {
            $oldExternalKeyList = json_decode($oldExternalKeyList, true);
        }

        $newExternalKeyList = (array)$selectedIds;
        if (is_array($oldExternalKeyList)) {
            $newExternalKeyList = array_merge(
                $oldExternalKeyList,
                $selectedIds
            );
        }

        $newExternalKeyList =
                Lib_Utils_Vector_UnsignedBigIntString::filter(
                    $newExternalKeyList,
                    array(0,"0"),
                    true
                );
        $newExternalKeyList = (array)array_unique($newExternalKeyList);
        $newExternalKeyListItemCount = (int)count($newExternalKeyList);

        // save to db
        $row = array(
            "personId" => (int)$personId,
            "inviteCount" => (int)$newExternalKeyListItemCount,
            "friendsExternalKeyList" => (string)json_encode(
                $newExternalKeyList
            ),
        );
        App_Manager_PersonInvite::getInstance()
                ->insertOrUpdateRow($row);


    }


    /**
     * WHAT DO YOU WANT TO DO, AFTER USER HAS INVITED FRIENDS?
     *
     * @param  array $selectedIds
     * @return void
     */
    public function onFriendsSelected($selectedIds)
    {

        // store to db
        if (is_array($selectedIds)!==true) {
            $selectedIds = array();
        }


/*
var_dump(__METHOD__);
var_dump($selectedIds);
        exit;
*/
        if (Lib_Utils_Array::isEmpty($selectedIds) !== true) {

            // do sth. eg: store to db

            $this->saveSelectedFriends($selectedIds);

        }


        // redirect to our app ...
        // redirect to app url
        $url = $this->getRedirectUrl();

        if (Lib_Utils_String::isEmpty($url)) {
            throw new Exception (
                "Redirect Url cant be empty at ".__METHOD__
                        .' for '.get_class($this)
                        .' pageType = '.$this->getPageType()
                        .' pageContentUrl = '.$this->getFacebook()
                            ->getConfig()
                            ->getContentUrlByPageType($this->getPageType())
            );
        }

        //$target = "window.parent.location";
        $target = "document.location";
        $this->dumpVar(
            __METHOD__,
            array(
                "target"=>$target,
                "url"=>$url,
                "selectedIds"=>$selectedIds
            ));
        $this->redirect(
                       html_entity_decode($url),
                       $target
                   );

        // stop the script
        exit;

    }

    /**
     * WHAT DO YOU WANT TO DO, AFTER USER HAS SKIPPED INVITING FRIENDS?
     * @return void
     */
    public function onFriendsSelectionCanceled()
    {

        // redirect to our app ...
        // redirect to app url
        $url = $this->getRedirectUrl();

        if (Lib_Utils_String::isEmpty($url)) {
            throw new Exception (
                "Redirect Url cant be empty at ".__METHOD__
                        .' for '.get_class($this)
                        .' pageType = '.$this->getPageType()
                        .' pageContentUrl = '.$this->getFacebook()
                            ->getConfig()
                            ->getContentUrlByPageType($this->getPageType())
            );
        }

        //$target = "window.parent.location";
        $target = "document.location";
        $this->dumpVar(
            __METHOD__,
            array(
                "target"=>$target,
                "url"=>$url,
            ));
        $this->redirect(
                       html_entity_decode($url),
                       $target
                   );

        // stop the script
        exit;
    }






}


