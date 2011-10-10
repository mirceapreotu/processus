<?php
/**
 * App_Manager_Fb_FacebookPermissions
 *
 * @category	meetidaaa.com
 * @package        App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */

/**
 * App_Manager_Fb_FacebookPermissions
 *
 *
 * @category	meetidaaa.com
 * @package        App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */
class App_Manager_Fb_FacebookPermissions extends App_Manager_AbstractManager
{



     /**
     * @var App_Manager_Fb_FacebookPermisssions
     */
    private static $_instance;


    /**
     * @static
     * @return App_Manager_Fb_FacebookPermisssions
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
     * @return int
     */
    protected function _getCacheTtl()
    {
        return 300; // sec
    }


    /**
     * @var array|null
     */
    protected $_facebookMePermissionsList;

    /**
     * @return array|null
     */
    public function getFacebookMePermissionsList()
    {

        $application = $this->getApplication();

        if (is_array($this->_facebookMePermissionsList)) {
            return $this->_facebookMePermissionsList;
        }


        $facebook = $this->getFacebook();
        $apiClient = $facebook->getApiClient();

        $apiService = $facebook->getServiceUserPermissions();
        $apiCachePolicy = $facebook->getApiClientCachePolicy();
        $apiCachePolicy->setLoad(true);
        $apiCachePolicy->setSave(true);
        $apiCachePolicy->setInvalidateOnError(true);

        $apiCachePolicy->setTtl($this->_getCacheTtl());

        $serviceResponse = $apiService->getMePermissions(
           $apiClient,
           $apiCachePolicy
        );
        //DO NOT DELEGATE ERROR: $serviceResponse->delegateError(__METHOD__);


        $permissionsList = $this->_parsePermissionsListResponse(
            $serviceResponse
        );

        $this->_facebookMePermissionsList = $permissionsList;

        return $this->_facebookMePermissionsList;
    }


    /**
     * @param  $serviceResponse
     * @return array
     */
    protected function _parsePermissionsListResponse(
        Lib_Facebook_Client_Response $serviceResponse
    )
    {
        // parse permissions
        $permissionsList = array();
        if ($serviceResponse->hasResult()) {
            $data = $serviceResponse->getResultProperty("data");

            $firstItem = null;
            if (is_array($data)) {
                $firstItem = Lib_Utils_Array::getProperty($data, 0);
            }
            foreach($firstItem as $permissionName => $permissionValue) {
                $permissionValue = Lib_Utils_TypeCast_String::asBool(
                    $permissionValue, null
                );

                if (is_bool($permissionValue)) {
                    $permissionsList[] = $permissionName;
                }
            }
        }

        return $permissionsList;
    }


    /**
     * @return array|null
     */
    public function reloadFacebookMePermissionsList()
    {
        $application = $this->getApplication();

        $this->destroyFacebookMePermissionsList();

        $facebook = $this->getFacebook();

        $apiClient = $facebook->getApiClient();

        $apiService = $facebook->getServiceUserPermissions();
        $apiCachePolicy = $facebook->getApiClientCachePolicy();

        $apiCachePolicy->setLoad(false);
        $apiCachePolicy->setSave(true);
        $apiCachePolicy->setInvalidateOnError(true);
        $apiCachePolicy->setTtl($this->_getCacheTtl());


        $serviceResponse = $apiService->getMePermissions(
           $apiClient,
           $apiCachePolicy
        );

        $permissionsList = $this->_parsePermissionsListResponse(
            $serviceResponse
        );

        $this->_facebookMePermissionsList = $permissionsList;

        return $this->_facebookMePermissionsList;

    }


    /**
     * @return void
     */
    public function destroyFacebookMePermissionsList()
    {
        $this->_facebookMePermissionsList = null;
    }



    /**
     * @param  $haystackAvailable
     * @param  $needlesRequired
     * @return array
     */
    protected function _getMissingPermissions(
        $haystackAvailable, $needlesRequired
    )
    {
        $missing = array();

        $needlesRequired = (array)$needlesRequired;
        $haystackAvailable = (array)$haystackAvailable;

        foreach($needlesRequired as $needleRequired) {

            if (in_array($needleRequired, $haystackAvailable, true)!==true) {
                $missing[] = $needleRequired;
            }
        }

        $missing = (array)array_unique($missing);
        return $missing;
    }

    /**
     * @param  array $permissionsList
     * @return array
     */
    public function getFacebookMePermissionsMissingByList($permissionsList)
    {

        $haystackAvailable = $this->getFacebookMePermissionsList();
        $needlesRequired = $permissionsList;

        $missing = $this->_getMissingPermissions(
            $haystackAvailable, $needlesRequired
        );

        return $missing;

    }
    /**
     * @param  array $permissionsList
     * @return bool
     */
    public function hasFacebookMePermissionsMissingByList($permissionsList)
    {

        $missing = $this->getFacebookMePermissionsMissingByList(
            $permissionsList
        );

        if (count($missing)>0) {
            return true;
        }
        return false;

    }

    /**
     * @return bool
     */
    public function hasFacebookMePermissionByName($name)
    {
        $permissionsList = array(
            $name
        );

        if ($this->hasFacebookMePermissionsMissingByList($permissionsList)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function hasFacebookMePermissionInstalled()
    {
        $name = "installed";
        return $this->hasFacebookMePermissionByName($name);
    }



    /**
     * @throws Lib_Application_Exception
     * @param  array $list
     * @param  $errorMethod
     * @return
     */
    public function requireFacebookMePermissionsByList($list, $errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        if (is_array($list)!==true) {
            throw new Exception("Invalid parameter 'list' at ".__METHOD__);
        }
        $missing = $this->getFacebookMePermissionsMissingByList($list);


        $list = (array)$list;
        if ($this->hasFacebookMePermissionsMissingByList($list)!==true) {
            return;
        }

        $missing = $this->getFacebookMePermissionsMissingByList($list);

        $e=new Lib_Application_Exception("REQUIRE_PERMISSION");
        $e->setMethod($errorMethod);
        $e->setData(array(
            "permissionsRequired" => $missing,
        ));

        throw $e;

    }

    /**
     * @throws Lib_Application_Exception
     * @param  $name
     * @param  $errorMethod
     * @return
     */
    public function requireFacebookMePermissionByName($name, $errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        $list = array(
            $name,
        );

        return $this->requireFacebookMePermissionsByList($list, $errorMethod);

    }

    /**
     * @throws Lib_Application_Exception
     * @param  $name
     * @param  $errorMethod
     * @return
     */
    public function requireFacebookMePermissionInstalled($errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }
        $name = "installed";
        return $this->requireFacebookMePermissionByName($name, $errorMethod);

    }





}
