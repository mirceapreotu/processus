<?php

/**
 * App_JsonRpc_V1_App_Context
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_JsonRpc_V1_App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Context
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_JsonRpc_V1_App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class App_JsonRpc_V1_Public_Context extends Lib_JsonRpc_Context
{

    /**
     * Define a filter, to find service class methods, where no auth is required
     * @var array
     */
    protected $_ignoreAuthClassMethodFilterConfig = array(
        // we just check valid osapiUserId and req signature,
        // but do not fetch profile data (using vz api)
        "flags" => FNM_CASEFOLD,
        "whitelist" => array(
            "*_Service_*",
        ),
        "blacklist" => array(

        ),
    );




    // +++++++++++++++++++++++++++++++++++++++++++++++++++


    
    /**
     * @override
     * @return void
     */
    public function prepare()
    {
        $profiler = $this->getApplication()->profileMethodStart(__METHOD__);

        return;

        $rpc = $this->getServer()->getRequest()->getDataProvider();

        /*
        $signedRequest = Lib_Utils_Array::getProperty($rpc, "signed_request");
        if ($signedRequest !== null) {

            $this->getApplication()->getFacebook()
                    ->setSignedRequest($signedRequest);
        }
        */
        

        // require auth?
        $this->_checkAuthIfRequired($this->getIgnoreAuthClassMethodFilter());

        $profiler->stop();
    }

    /**
     * @override
     * @param  $result
     * @return void
     */
    public function onResult($result)
    {

        try {
            $this->getServer()
                    ->getLogger()
                    ->log(
                        __METHOD__,
                        "profiler",
                        $this->getApplication()->getProfilerRoot()
                                ->stopAndExportAsArray(true)
            );
        } catch (Exception $e) {

        }
    }

    /**
     * @param Exception $error
     * @return void
     */
    public function onError(Exception $error)
    {

    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @return
     */
    public function requireAuth()
    {
        $application = $this->getApplication();
        $application->destroyViewerBO();
        $application->destroyFacebookMe();

        $authError = null;

        try {
            $this->_requireFacebookMe();
        } catch (Exception $e) {
            $authError = $e;
            if ($authError->getMessage() !== "LOGIN") {
                throw $e;
            }
        }


        if ($authError instanceof Exception) {
            $this->_onAuthFailed($authError);
            return;
        } else {
            $this->_onAuthComplete($application->getFacebookMe());
            return;
        }


        // this case should never happen, but just in case ...
        throw new Exception("Method returns invalid result at ".__METHOD__);

    }





    /**
     * @throws Exception
     * @param Lib_Fb_Model_OsapiUser $osapiUser
     * @return void
     */
    protected function _onAuthComplete($me)
    {

        $this->getServer()->getLogger()->log(__METHOD__,"");

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

        $viewerBO = $application->newViewerBO();
        $viewerBO->setExternalKey($meId);
        if ($viewerBO->exists() !== true) {
            $viewerBO = $this->_autoRegisterPlatformUser();
        }
        $application->setViewerBO($viewerBO);
    }



    /**
     * @throws Lib_Application_Exception
     * @return App_Model_Bo_Fb_User
     */
    protected function _autoRegisterPlatformUser()
    {

        if ($this->getApplication()->hasFacebookMe() !== true) {
            $e = new Lib_Application_Exception(
                "invalid application.hasFacebookMe"
            );
            $e->setMethod(__METHOD__);
            throw $e;
        }
        $me = $this->getApplication()->getFacebookMe();
        $meId = $this->getApplication()->getFacebookMeId();
        $managerPerson = $this->getApplication()->getManagerPerson();
        $managerPerson->autoRegisterFacebookUserByFacebookMe($me);

        $this->getApplication()->destroyViewerBO();
        $viewerBO = $this->getApplication()->newViewerBO();
        $viewerBO->setExternalKey($meId);
        $this->getApplication()->setViewerBO($viewerBO);
        return $this->getApplication()->getViewerBO();

    }


    /**
     * @throws Exception
     * @param Exception $error
     * @return void
     */
    protected function _onAuthFailed(Exception $error)
    {
        //var_dump(__METHOD__);
        $e= new Lib_Application_Exception("LOGIN");
        $e->setMethod(__METHOD__);
        $e->setFault(
            $error
        );
        throw $e;
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @param  Lib_fnmatch_Filter $filterIgnoreClassMethodQName
     * @return
     */
    protected function _checkAuthIfRequired(
        Lib_Fnmatch_Filter $filterIgnoreClassMethodQName
    )
    {
        $target = $this->getServer()->getServiceClassMethodQualifiedName();
        $filter = $filterIgnoreClassMethodQName;
        $isWhitelisted = $filter->isWhitelisted($target, null);
        $isBlacklisted = $filter->isBlacklisted($target, null);

        $ignoreAuth = (bool)(
                ($isWhitelisted===true)
                && ($isBlacklisted !== true)
        );

        if ($ignoreAuth !== true) {
            $this->requireAuth();
        } else {
            return;
        }

    }

    /**
     * @return Lib_Fnmatch_Filter
     */
    public function getIgnoreAuthClassMethodFilter()
    {
        $config = $this->_ignoreAuthClassMethodFilterConfig;
        $filter = new Lib_Fnmatch_Filter();
        $filter->applyConfig($config);
        return $filter;
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     *
     * @param string $method
     * @param string $message
     * @param mixed|null $data
     * @return
     */
    public function log($method, $message, $data = null)
    {
        return $this->getServer()->getLogger()->log($method, $message, $data);
    }

    /**
	 * @return App_Registry
	 */
	public function getRegistry()
	{
		return Bootstrap::getRegistry();
	}

    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }


	/**
	 * @return Lib_Db_Xdb_Client
	 */
	public function getDbClient()
	{
		return $this->getApplication()->getDbClient();
	}


    /**
     * @return App_Fb_Session
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
     * @return App_Model_Bo_Fb_User
     */
    public function getViewerBO()
    {
        return $this->getApplication()->getViewerBO();
    }



}


