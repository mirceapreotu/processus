<?php
/**
 * App_Ctrl_Facebook_Tab
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
 * App_Ctrl_Facebook_Tab
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



class App_Ctrl_Facebook_Tab extends App_Ctrl_Facebook_Abstract
{

    const ERROR_FANPAGE_LIKED_REQUIRED = "FANPAGE_LIKED_REQUIRED";

    /*
    const VIEW_TEMPLATE_FILENAME_AUTHORIZE_APP_AND_GO
        = "View.AuthorizeAppAndGo.tpl.php";
    */
    const VIEW_TEMPLATE_FILENAME_AUTHORIZE_APP_AND_GO
        = "View.tpl.php";

    const VIEW_TEMPLATE_FILENAME_LIKE_AND_GO = "View.LikeAndGo.tpl.php";

     /**
     * override
     * @var string
     */
    protected $_pageType = Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB;



    /**
     * @throws Exception
     * @param  string|null $appData
     * (if appData is string, we use that one, if null we autodetect)
     * @return string
     */
    public function newReloadFanpageUrl(
        // if appData===null -> autodetect
        $appData
    )
    {

        $application = $this->getApplication();

        if ($appData === null) {
            $appData = $application
                    ->getFacebookFanpageAppDataFromSignedRequestDecoded();
        }

        $fanpageId =
                $application->getFacebookFanpageIdFromSignedRequestDecoded();
        if ($application->isValidId($fanpageId)!==true) {
            // use from config
            $fanpageId = $application->getFacebook()
                    ->getConfig()->getFanPageId();
        }
        if ($application->isValidId($fanpageId)!==true) {
            throw new Exception("Invalid fanpageId at ".__METHOD__);
        }

        $url = $application->newUrlToFanpage($fanpageId, $appData);
        return $url;


    }



    /**
     * @param  $appData
     * @return void
     */
    public function reloadFanpage(
        // if appData===null -> autodetect
        $appData
    )
    {
        $url = $this->newReloadFanpageUrl($appData);

         echo("<script>
                    try {
                        top.location.href='" . $url . "';
                    } catch(e) {
                        throw e;
                    }
                    </script>");
        exit;
    }



    /**
     * override
     * @return void
     */
    /*
    public function run()
    {

        parent::run();

    }
    */


    /**
    * @return void
    */
    public function run()
    {

        $isFanpageLikeRequired = true;

        $application = $this->getApplication();

        $this->_bugfixAccessToken();


        $pageConfig = $this->getPageConfig();


        try {
            if ($this->_isRunning ===true) {
               throw new Exception("Is already running ".get_class($this));
            }
            $this->_isRunning = true;

            $pageConfig = $this->getPageConfig();
            if ($pageConfig->enabled !== true) {
                throw new Exception("PageType is not enabled at ".__METHOD__);
            }


            $this->handleRequestAuthCodeIfExists();
            // this may trigger a redirect
            $this->ensureContentIsEmbededIntoFanpage();
            // this may trigger a redirect

            // fanpage like required?
            $fanpageLikedError = null;
            try {
                if ($isFanpageLikeRequired === true) {
                    $this->requireFanpageLiked();
                }
            } catch(Exception $e) {

                $fanpageLikedError = $e;
                if ($fanpageLikedError->getMessage()
                    !== self::ERROR_FANPAGE_LIKED_REQUIRED) {
                    throw $e;
                }
            }
            if ($fanpageLikedError instanceof Exception) {
                $this->_onFanpageLikedFailed($fanpageLikedError);
                return;
            }

            if ($isFanpageLikeRequired === true) {
                //$this->_onFanpageLikedComplete();
                //return;
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



    // +++++++++++++++ ganz neu ++++++++++++++++++++++


    /**
     * @param  bool $forceFbAuthIfNotExists
     * @param  bool $updatePersonRecord
     * @return void
     */
    public function tryAutoRegisterFbUser(
        $forceFbAuthIfNotExists, $updatePersonRecord
    )
    {
        $application = $this->getApplication();
        $application->tryAutoRegisterFbUser(
            $forceFbAuthIfNotExists, $updatePersonRecord
        );
    }


    // ++++++++++++ new ++++++++++++++++++++++++++++++


    // ensure content has been loaded into the page iframe
    // after auth/auth failed we may leave the fanpage - but we do not want that
    //

    /**
     * @return
     */
    public function ensureContentIsEmbededIntoFanpage()
    {
        $application = $this->getApplication();


        $requestPageId = $application
                ->getFacebookFanpageIdFromSignedRequestDecoded();
        if($application->isValidId($requestPageId)) {
            // we are inside a page
            return;
        }


        $this->reloadFanpage(null);

    }

    // +++++++++++++++++ the request code ... ++++++++++++++++++
    /**
     * @return void
     */
    public function handleRequestAuthCodeIfExists()
    {
        if ((isset($_GET['code'])) && (!empty($_GET["code"]))) {

            $this->_onFacebookAuthCodeReceived();
        }
    }
    /**
     * @override
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



        $this->reloadFanpage(null);

    }


    // ++++++++++ the "like fanpage" requirements ... +++++++++++++++

    /**
     * @throws Exception
     * @return void
     */
    public function requireFanpageLiked()
    {
        $application = $this->getApplication();

        $pageLiked = $application
                ->getFacebookFanpageLikedByUserFromSignedRequestDecoded();
        if ($pageLiked !== true) {

            throw new Exception(self::ERROR_FANPAGE_LIKED_REQUIRED);
        }
        
    }



    /**
     * @param Exception $e
     * @return void
     */
    protected function _onFanpageLikedFailed(Exception $e)
    {
        // show teaser: "like this page and go"
        $this->getView()->setTemplateFilename(
            self::VIEW_TEMPLATE_FILENAME_LIKE_AND_GO
        );
        $this->renderView();
        return;

    }


    /**
     * @return void
     */
    protected function _onFanpageLikedComplete()
    {
        //var_dump(__METHOD__); exit;
    }









    // ++++++++++++++++++++++ overrides ++++++++++++++++

    /**
     * THE SPECIAL ONE FOR FANPAGE TAB
     * WATCHOUT (!) A LOT OF BRAINFUCK HERE
     * @override
     * @param null $params
     * @return string
     */
    public function getLoginUrl($params=null)
    {
//var_dump(__METHOD__);
        if ($params === null) {
            $params = array();
        }

        if (is_array($params)!==true) {
            throw new Exception("Invalid parameter 'params' at ".__METHOD__);
        }


        $redirect_uri = Lib_Utils_Array::getProperty($params, "redirect_uri");
        if (Lib_Utils_String::isEmpty($redirect_uri)!==true) {
            return parent::getLoginUrl($params);
        }

        // construct redirect uri from given environment

        $application=$this->getApplication();

        $fanpageId = null;// "152208658142315"; AUTODETCT from signed request or config

        $fanpageIdFromSignedRequest =
                $application->getFacebookFanpageIdFromSignedRequestDecoded();

        if ($application->isValidId($fanpageIdFromSignedRequest)) {
            $fanpageId = $fanpageIdFromSignedRequest;
        }


        // depplinks?
        $appData = $application
                ->getFacebookFanpageAppDataFromSignedRequestDecoded();

        $redirect_uri = $application->newFanpageLoginUrlRedirectUri(
            $fanpageId, $appData
        );


        $params["redirect_uri"] = $redirect_uri;
        return parent::getLoginUrl($params);

        

    }


    protected function _bugfixAccessToken()
    {
        $application = $this->getApplication();

        // das soll angeblich helfen, bei 3rd party cookie bug?
        // na mal sehn...
        $application->getFacebook()->getAccessToken();

    }

    /**
     * @param  array $me
     * @return void
     */
    protected function _onAuthComplete($me)
    {


        //var_dump(__METHOD__); //exit;

        $application = $this->getApplication();

        $this->_bugfixAccessToken();

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



        //var_dump(__METHOD__." ". $viewerBO->getExternalKey());
        //var_dump(__METHOD__." ". $viewerBO->getPersonRecord()->getId());


    }

    /**
     * @param Exception $e
     * @return void
     */
    protected function _onAuthFailed(Exception $e)
    {



        //var_dump(__METHOD__); //exit;

        /*
        $this->getApplication()->getDebug()->dumpVar(__METHOD__, $e);

        */
        if ($e->getMessage() === "LOGIN") {
            //$this->login();

            $this->getView()->setTemplateFilename(
                self::VIEW_TEMPLATE_FILENAME_AUTHORIZE_APP_AND_GO
            );


            $this->renderView();
            return;

        } else {
            throw $e;
        }

    }
}


