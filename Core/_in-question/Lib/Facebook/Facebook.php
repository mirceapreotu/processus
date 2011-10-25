<?php
/**
 * Lib_Facebook_Facebook Class
 *
 * @package Lib_Facebook
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Facebook
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


require_once PATH_CORE . "/Contrib/Facebook/src/facebook.php";

class Lib_Facebook_Facebook extends Facebook
{


	// api docs: http://developers.facebook.com/docs/api
	// patches to facebook-php-sdk //
    // http://thinkdiff.net/facebook/graph-api-iframe-base-facebook-application-development-php-sdk-3-0/

	/*permissions:
	http://developers.facebook.com/docs/authentication/permissions
	http://developers.facebook.com/docs/authentication/
	*/


    /**
     * @var Lib_Facebook_Client_Client;|null
     */
    protected $_apiClient;

    /**
     * @return Lib_Facebook_Client_Client
     */
    public function newApiClient()
    {
        return new Lib_Facebook_Client_Client();
    }
    /**
     * @return Lib_Facebook_Client_Client
     */
    public function getApiClient()
    {
        if (($this->_apiClient instanceof Lib_Facebook_Client_Client)!==true) {
            $this->_apiClient = $this->newApiClient();
        }
        return $this->_apiClient;
    }
    /**
     * @return Lib_Facebook_Client_CachePolicy
     */
    public function getApiClientCachePolicy()
    {
        $policy = $this->getApiClient()->newCachePolicy();
        $policy->setEnabled(true);
        return $policy;
    }


    // +++++++++++++++++++++++++ api services ++++++++++++++++++++

    /**
     * @var Lib_Facebook_Service_Graph
     */
    protected $_serviceGraph;
    /**
     * @return Lib_Facebook_Service_Graph
     */
    public function getServiceGraph() {
        if (($this->_serviceGraph
             instanceof Lib_Facebook_Service_Graph)!==true) {
            $this->_serviceGraph =
                    new Lib_Facebook_Service_Graph();
        }
        return $this->_serviceGraph;
    }



    /**
     * @var Lib_Facebook_Service_User_Permissions
     */
    protected $_serviceUserPermissions;
    /**
     * @return Lib_Facebook_Service_User_Permissions
     */
    public function getServiceUserPermissions() {
        if (($this->_serviceUserPermissions
             instanceof Lib_Facebook_Service_User_Permissions)!==true) {
            $this->_serviceUserPermissions =
                    new Lib_Facebook_Service_User_Permissions();
        }
        return $this->_serviceUserPermissions;
    }

    /**
     * @var Lib_Facebook_Service_Pages_Events
     */
    protected $_servicePagesEvents;
    /**
     * @return Lib_Facebook_Service_Pages_Events
     */
    public function getServicePagesEvents() {
        if (($this->_servicePagesEvents
             instanceof Lib_Facebook_Service_Pages_Events)!==true) {
            $this->_servicePagesEvents =
                    new Lib_Facebook_Service_Pages_Events();
        }
        return $this->_servicePagesEvents;
    }

    /**
     * @var Lib_Facebook_Service_Application
     */
    protected $_serviceApplication;
    /**
     * @return Lib_Facebook_Service_Application
     */
    public function getServiceApplication() {
        if (($this->_serviceApplication
             instanceof Lib_Facebook_Service_Application)!==true) {
            $this->_serviceApplication =
                    new Lib_Facebook_Service_Application();
        }
        return $this->_serviceApplication;
    }



    /**
     * @var Lib_Facebook_Service_User_Events
     */
    protected $_serviceUserEvents;
    /**
     * @return Lib_Facebook_Service_User_Events
     */
    public function getServiceUserEvents() {
        if (($this->_serviceUserEvents
             instanceof Lib_Facebook_Service_User_Events)!==true) {
            $this->_serviceUserEvents =
                    new Lib_Facebook_Service_User_Events();
        }
        return $this->_serviceUserEvents;
    }


    /**
     * @var Lib_Facebook_Service_Events
     */
    //protected $_serviceEvents;
    /**
     * @return Lib_Facebook_Service_Events
     */
    /*
    public function getServiceEvents() {
        if (($this->_serviceEvents
             instanceof Lib_Facebook_Service_Events)!==true) {
            $this->_serviceEvents =
                    new Lib_Facebook_Service_Events();
        }
        return $this->_serviceEvents;
    }
        */

     /**
     * @var Lib_Facebook_Service_Pages
     */
    protected $_servicePages;
    /**
     * @return Lib_Facebook_Service_Pages
     */

    public function getServicePages() {
        if (($this->_servicePages
             instanceof Lib_Facebook_Service_Pages)!==true) {
            $this->_servicePages =
                    new Lib_Facebook_Service_Pages();
        }
        return $this->_servicePages;
    }




    // +++++++++++++++++++++++++++++++++++++++++++++++++++



    /**
     * @return string|null
     */
    public function getForceloginUrlHost()
    {
        return Bootstrap::getRegistry()->getServerStageHost();
    }









    /**
     * @var Lib_Facebook_Config
     */
    protected $_config;


    /**
     * @param Lib_Facebook_Config $config
     */
    public function __construct(Lib_Facebook_Config $config)
    {


    //    self::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
    //    self::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;

        $this->_config = $config;

        $_config = array(
            "appId" => $config->getAppId(),
            "secret" => $config->getApiSecret(),
        );
        if (is_bool($config->getApiClientCookieSupportEnabled())) {
            $_config["cookie"] =
                    $config->getApiClientCookieSupportEnabled();
        }
        if (is_bool($config->getApiClientFileUploadSupportEnabled())) {
            $_config["fileUpload"] =
                    $config->getApiClientFileUploadSupportEnabled();
        }
        if (is_string($config->getApiClientBaseDomain())) {
            $_config["domain"] =
                    $config->getApiClientBaseDomain();
        }

        parent::__construct($_config);



    }


    /**
     * @return Lib_Facebook_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @override
     * make that method public
     * @return string
     */
    public function getCurrentUrl()
    {
       return parent::getCurrentUrl();
    }

    /**
     * @override
     * @throws Exception
     * @param  array|null $params
     * @return string
     */
    public function getLoginUrl($params = null)
    {


        if ($params === null) {
            $params = array();
        }
        if (is_array($params)!==true) {
            throw new Exception("Invalid parameter 'params' at ".__METHOD__);
        }
        // inject login scope if not provided
        if (array_key_exists("scope", $params)!==true) {
            $scope = $this->getConfig()->getLoginScope();
            if (Lib_Utils_String::isEmpty($scope)) {
                $scope = null;
            }

            if ($scope !== null) {
                $params["scope"] = $scope;
            }

        }


        //$sslDomain = "ssl-fb-five-gum-app.exitb.de";

        $forceDomain = null;
        $forceDomain = $this->getForceloginUrlHost();//"fb-five-gum-app.app.exitb.de";
        if (Lib_Utils_String::isEmpty($forceDomain)) {
            $result = parent::getLoginUrl($params);
            return $result;
        }

        // force non-ssl domain
        $redirectUriDefault = $this->getCurrentUrl();

        $redirectUri = $redirectUriDefault;

        $redirectUriFromParams = Lib_Utils_Array::getProperty($params, "redirect_uri");//'redirect_uri' => $currentUrl, // possibly overwritten
        if (Lib_Utils_String::isEmpty($redirectUriFromParams)!==true) {
            $redirectUri = $redirectUriFromParams;
        }

        // replace ssl-domain if any

        $zendUri = new Lib_Url_Uri();
        if ($zendUri->isValidUri($redirectUri) !== true) {
            throw new Exception("Invalid Uri at ".__METHOD__);
        }
        $zendUri->setUri($redirectUri);


        if (Lib_Utils_String::isEmpty($forceDomain)!==true) {
            $zendUri->setHost($forceDomain);
        }
        $params["redirect_uri"] = $zendUri->toString(Lib_Url_Uri::SCHEME_HTTP);

        $result = parent::getLoginUrl($params);
        return $result;
    }


    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getConfig()->getApiKey();
    }


    /**
     * @param  $path
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function graph($path, $method='GET', $params=array())
    {

        return $this->_graph($path, $method, $params);
    }

    /**
     * @param  $path
     * @param array $params
     * @return mixed
     */
 	public function apiPOST($path, $params=array())
    {
		// SESSION BASED!!!!
		// REQUIRES AUTHORIZATION !!!!//

     	$method = 'POST';

		$result = $this->graph($path,$method,$params);
     	return $result;
    }





    /**
     * @obsolete
     * 3rd-party-cookie-hack: e.g. safari
     * @param  array|string $sessionData
     * @return
     */
    public function getSessionRestore($sessionData)
    {
        throw new Exception("Obsolete ".__MTEHOD__);
        $session = null;
        $write_cookie = true;

        //var_dump($sessionData);
        if (is_array($sessionData)) {
        //var_dump($sessionData);
        }

        if (is_string($sessionData)) {
        $sessionData = json_decode(
          get_magic_quotes_gpc()
            ? stripslashes($sessionData)
            : $sessionData,
          true
        );
        }

         // var_dump($sessionData);
         // return $sessionData;
        $session = $this->validateSessionObject($sessionData);
        if ($session) {
            $this->setSession($session, $write_cookie);
        }

        //var_dump($this->session);
        return $this->session;
    }

    /**
     * @param  array $params
     * @return mixed
     */
    public function callRestServerRaw($params)
    {
        // generic application level parameters
        $params['api_key'] = $this->getAppId();
        //$params['format'] = 'json-strings';
        $result = $this->_oauthRequest(
            $this->getApiUrl($params['method']),
            $params
        );
        return $result;
        /*
        $result = json_decode($this->_oauthRequest(
          $this->getApiUrl($params['method']),
          $params
        ), true);

        // results are returned, errors are thrown
        if (is_array($result) && isset($result['error_code'])) {
          throw new FacebookApiException($result);
        }
        return $result;
        */
    }





    // ++++++++++++++++++++++ mocking & 3rd-party cookie bug ++++++++++++

    public function destroyAllPersistantData()
    {
        throw new Exception("disabled ".__METHOD__);
        $this->clearAllPersistentData();
        /*
        $this->user = null;
        $this->signedRequest = null;
        $this->state = null;
        */
    }

    /**
     * @NOTICE: YOU MUST NOT access facebook.getUser() before applying mockData
     * @param  string $signedRequest
     * @return void
     */
    public function setSignedRequest($signedRequest)
    {

        $this->clearAllPersistentData();

        $this->user = null;
        $this->signedRequest = null;
        $this->state = null;

        try {
            $this->signedRequest = $this->parseSignedRequest(
              $signedRequest
            );
        } catch(Exception $e) {
            //NOP

        }




        if (is_array($this->signedRequest)!==true) {
            $this->signedRequest = null;
        }

        //var_dump($signedRequest);
        //var_dump($this->signedRequest);exit;

    }
    /*
    public function getSignedRequest() {
        if (!$this->signedRequest) {
          if (isset($_REQUEST['signed_request'])) {
            $this->signedRequest = $this->parseSignedRequest(
              $_REQUEST['signed_request']);
          }
        }
        return $this->signedRequest;
    }
    */

    /**
     * @param  int|string|null|mixed $id
     * @return bool
     */
    public function isValidId($id)
    {
        if ((is_int($id)) && ($id>0)) {
            return true;
        }
        $defaultValue = null;
        $value = Lib_Utils_TypeCast_String::asUnsignedBigIntString(
            $id,
            $defaultValue
        );
        if ($value === null) {
            return false;
        }
        if ( (((int)$value)>0) !== true) {
            return false;
        }
        return true;
    }

    /**
     * @param  int|string $externalKey
     * @return bool
     */
    public function isValidUserId($externalKey)
    {
        if ((is_int($externalKey)) && ($externalKey>0)) {
            return true;
        }
        $defaultValue = null;
        $value = Lib_Utils_TypeCast_String::asUnsignedBigIntString(
            $externalKey,
            $defaultValue
        );
        if ($value === null) {
            return false;
        }
        if ( (((int)$value)>0) !== true) {
            return false;
        }
        return true;
    }


    /**
     * @return int|null|string|mixed
     */
    public function getUserId()
    {
        $userId = $this->user;
        return $userId;
    }

    /**
     * @return string|int|null|mixed
     */
    public function getUserIdFromAvailableData()
    {
        $userId = $this->getUser();
        return $userId;
    }


    /**
     * @return bool
     */
    public function hasValidUserId() {
        $result = false;
        $userId = $this->getUserId();
        if ($userId === null) {
            return $result;
        }
        if ($userId === 0) {
            return $result;
        }

        if ($this->isValidUserId($userId)) {
            return $result;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function hasValidUserIdFromAvailableData()
    {
        $result = false;
        $userId = $this->getUserIdFromAvailableData();
        if ($userId === null) {
            return $result;
        }
        if ($userId === 0) {
            return $result;
        }

        if ($this->isValidUserId($userId)) {
            return $result;
        }
        return true;
    }


    /**
     * @return null|array
     */
    public function fetchMe()
    {

        // fb me
        $me = null;
        $userId = null;

           try
           {
               $userId = $this->getUser();

               if ($userId) {
                   //var_dump($userId);
                    $me = $this->api('/me');
                   //var_dump($me);
               }

           } catch(FacebookApiException $e) {
               //NOP
               //var_dump($e->getMessage());
           }

        if (Lib_Utils_Array::isEmpty($me)) {
            return null;
        }
        return $me;
    }



    /**
     * @override
     * @return int
     */
   public function getUserFromAccessToken() {
       return parent::getUserFromAccessToken();
  }



    // the funny stuff

    /**
     * @param  int|string $userId
     * @param null|string $imageFormat
     * @return null|string
     */
    public function getProfileImageUrl($userId, $imageFormat = null)
    {
        $id = (string)$userId;
        if ($this->isValidUserId($id) !== true) {
            return null;
        }
        if (Lib_Utils_String::isEmpty($imageFormat)) {
            return "https://graph.facebook.com/".$id."/picture";
        }

        return "https://graph.facebook.com/".$id."/picture?type=".$imageFormat;
    }



    /**
     * @throws FacebookApiException
     * @param  int|string $pageId
     * @param  int|string $userId
     * @return bool|mixed
     */
    public function isUserFanOfPage($pageId,$userId)
	{
        if ((((int)$userId)>0) !== true) {
            return false;
        }

        if ((((int)$pageId)>0) !== true) {
            throw new Exception(
                "Invalid pageId must be int/string>0 at "
                        . __METHOD__." for ".get_class($this)
            );
        }


        //FB.api({ method: 'pages.isFan', page_id: response.id },
		// https://api.facebook.com/method/pages.isFan?page_id=169915089688960&uid=1013680688&access_token=2227470867%7C2._e9wWX2XA52Y3pfjAlWcTA__.3600.1288972800-1013680688%7CiMezS7pCLqGEGcSfyqMdhx479AQ&format=json

		/*
		Note: This call no longer requires a session key.
		You must pass a user ID if you don't pass a session key.
		If the user's pages are set to less than everyone privacy,
		you must ask the user for the user_likes  extended permission
		and include a valid user access token in the API call.
		*/

		$accessToken = $this->getAccessToken();

		$result = $this->callRestServerRaw(
			array(
					"method" =>'pages.isFan',
					"page_id" => $pageId,
					"uid" => $userId,
					"access_token" => $accessToken,
			 		'format' => 'json-strings',
					)
		);

		if (is_string($result)) {

			if (trim($result)==="true") {
				return true;
			}
			if (trim($result)==="false") {
				return false;
			}

			$result = json_decode($result,true);
		}

		if (is_array($result) && isset($result['error_code'])) {
	      throw new FacebookApiException($result);
	    }
		return $result;
	}



    public function getUserAccessToken()
    {
        return parent::getUserAccessToken();
    }

    /**
     * @param  string $signedRequest
     * @return array
     */
    public function decodeSignedRequest($signedRequest)
    {
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


        try {
            $data = $this->parseSignedRequest($signedRequest);
            $result["data"] = $data;

            $result["app_data"] = Lib_Utils_Array::getProperty(
                $data, "app_data"
            );

            // page info
            $page = $result["page"];
            $page["app_data"] = $result["app_data"];
            $_page = Lib_Utils_Array::getProperty($data, "page");
            if (is_array($_page)) {
                foreach($_page as $key => $value) {
                    $page[$key] = $value;
                }
            }
            $result["page"] = $page;


            // user info
            $user = $result["user"];
            if (isset($data["user"])) {
                if (is_array($data["user"])) {
                    $user = $data["user"];
                }
                if (isset($data["user_id"])) {
                    $user["id"] = $data["user_id"];
                }
            }
            $result["user"] = $user;



            return $result;
        } catch(Exception $e) {
            $result["error"] = array(
                "message" => $e->getMessage(),
            );
            return $result;

        }
    }


    /**
     * @override make it public
     * @param  $signed_request
     * @return array
     */
    public function parseSignedRequest($signed_request) {
        return parent::parseSignedRequest($signed_request);
    }



    /**
     * @throws Exception
     * @param SplFileInfo $fileInfo
     * @param string|null $message
     * @param array|null $additionalProperties
     * @return array
     */
    public function uploadMePhoto(
        SplFileInfo $fileInfo,
        $message=null,
        $additionalProperties=null
    )
    {
        if ($additionalProperties === null) {
            $additionalProperties = array();
        }

        if (is_array($additionalProperties)!==true) {
            throw new Exception(
                "Invalid parameter 'additionalProperties' at ".__METHOD__
            );
        }

        if ((($message===null)||(is_string($message)))!==true) {
            throw new Exception(
                "Invalid parameter 'message' at ".__METHOD__
            );
        }


        if ($fileInfo->isFile() !== true) {
            throw new Exception(
                "Invalid parameter 'fileInfo' is not a file at ".__METHOD__
            );
        }


        try {
            $location = $fileInfo->getPathname();
            $locationRealPath = $fileInfo->getRealPath();
            $type = (int)exif_imagetype($locationRealPath);
            if ($type<1) {
                throw new Exception("Invalid exiftype");
            }
        }catch (Exception $e) {
            throw new Exception(
                "Invalid parameter 'fileInfo' is not an image at "
                .__METHOD__." details: ".$e->getMessage()
            );
        }

        $facebook = $this;
        $facebook->setFileUploadSupport(true);


        $image = (array)$additionalProperties;
        $image['access_token'] = $facebook->getAccessToken();
        if ($message !== null) {
            $image["message"] = $message;
        }
        $image["source"] = '@'.$locationRealPath;

        /*
         * may throw a FacebookApiException with message
         * An active access token must be used to query information about the current user.
         */

        //        $facebook->api("/".$album_id."/photos", 'post', $post_data);
        $result = $facebook->api('/me/photos', 'POST', $image);

        try {
            $facebookImageId = $result["id"];
            if ($this->isValidId($facebookImageId)!==true) {

                throw new Exception("invalid result.id ");
            }
            return $result;

        } catch (Exception $e) {
            throw new Exception(
                "Method returns invalid result at ".__METHOD__
                ." details: ".$e->getMessage()
            );
        }



    }





     /**
     * @throws Exception
     * @param SplFileInfo $fileInfo
     * @param int|string $albumId
     * @param string|null $message
     * @param array|null $additionalProperties
     * @return array
     */
    public function uploadAlbumPhoto(
        SplFileInfo $fileInfo,
        $albumId,
        $message=null,
        $additionalProperties=null
    )
    {
        if ($this->isValidId($albumId)!==true) {
            throw new Exception("Invalid parameter 'albumId' at ".__METHOD__);
        }


        if ($additionalProperties === null) {
            $additionalProperties = array();
        }

        if (is_array($additionalProperties)!==true) {
            throw new Exception(
                "Invalid parameter 'additionalProperties' at ".__METHOD__
            );
        }

        if ((($message===null)||(is_string($message)))!==true) {
            throw new Exception(
                "Invalid parameter 'message' at ".__METHOD__
            );
        }


        if ($fileInfo->isFile() !== true) {
            throw new Exception(
                "Invalid parameter 'fileInfo' is not a file at ".__METHOD__
            );
        }


        try {
            $location = $fileInfo->getPathname();
            $locationRealPath = $fileInfo->getRealPath();
            $type = (int)exif_imagetype($locationRealPath);
            if ($type<1) {
                throw new Exception("Invalid exiftype");
            }
        }catch (Exception $e) {
            throw new Exception(
                "Invalid parameter 'fileInfo' is not an image at "
                .__METHOD__." details: ".$e->getMessage()
            );
        }

        $facebook = $this;
        $facebook->setFileUploadSupport(true);


        $image = (array)$additionalProperties;
        $image['access_token'] = $facebook->getAccessToken();
        if ($message !== null) {
            $image["message"] = $message;
        }
        $image["source"] = '@'.$locationRealPath;

        /*
         * may throw a FacebookApiException with message
         * An active access token must be used to query information about the current user.
         */

        //        $facebook->api("/".$album_id."/photos", 'post', $post_data);
        $result = $facebook->api('/'.$albumId.'/photos', 'POST', $image);

        try {
            $facebookImageId = $result["id"];
            if ($this->isValidId($facebookImageId)!==true) {

                throw new Exception("invalid result.id ");
            }
            return $result;

        } catch (Exception $e) {
            throw new Exception(
                "Method returns invalid result at ".__METHOD__
                ." details: ".$e->getMessage()
            );
        }



    }



}
