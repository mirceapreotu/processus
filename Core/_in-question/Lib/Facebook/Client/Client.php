<?php
/**
 * Lib_Facebook_Client_Client Class
 *
 * @package Lib_Facebook_Client
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Client_Client
 *
 *
 * @package Lib_Facebook_Service
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Client_Client
{

    protected $_clientId;

    public function setClientId($value) {
        $this->_clientId = $value;


        
    }

    public function getClientId() {
        return $this->_clientId;
    }


    /**
     * @var Lib_Facebook_Client_Cache
     */
    protected $_cache;



    /**
     * @return Lib_Facebook_Client_Cache
     */
    public function getCache()
    {
        if (($this->_cache instanceof Lib_Facebook_Client_Cache)!==true) {
            $this->_cache = new Lib_Facebook_Client_Cache();
            $this->_cache->init($this);
        }
        return $this->_cache;
    }

    /**
     * @return Lib_Facebook_Client_CachePolicy
     */
    public function newCachePolicy()
    {
        return $this->getCache()->newCachePolicy();
    }
    /**
     * @return Lib_Facebook_Client_CacheInvalidator
     */
    public function newCacheInvalidator()
    {
        return $this->getCache()->newCacheInvalidator();
    }

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
     * @return Lib_Facebook_Client_Response
     */
    public function newResponse()
    {
        $clientResponse = new Lib_Facebook_Client_Response();
        return $clientResponse;
    }

    /**
     * @return string|null
     */
    public function getAccessTokenApplication()
    {
        $application = $this->getApplication();

        $application->mockFacebookSignedRequestIfMockingEnabled();
        $facebook = $this->getFacebook();

        $accessTokenApp = $facebook->getAccessToken();

        if (Lib_Utils_String::isEmpty($accessTokenApp)) {
            return null;
        }

        return $accessTokenApp;
    }

    /**
     * @return string|null
     */
    public function getAccessTokenUser()
    {
        $application = $this->getApplication();

        $application->mockFacebookSignedRequestIfMockingEnabled();
        $facebook = $this->getFacebook();

        $accessTokenUser = $facebook->getUserAccessToken();

        if (Lib_Utils_String::isEmpty($accessTokenUser)) {
            return null;
        }

        return $accessTokenUser;
    }














    /**
     * @throws Exception
     * @param  $id
     * @param  $additionalGraph
     * @param  $apiEndpointParams
     * @param  $allowMe
     * @param  $cachePolicy Lib_Facebook_Client_CachePolicy
     * @return Lib_Facebook_Client_Response
     */
    public function apiGetById(
        Lib_Facebook_Client_CachePolicy $cachePolicy,

        $id,
        $additionalGraph,
        $apiEndpointParams,

        $allowMe



    )
    {

        if (is_string($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        $id = trim($id);
        if (Lib_Utils_String::isEmpty($id)) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        if (ctype_alnum($id)!==true) {
            throw new Exception("Invalid parameter 'id' must be alphanum at "
                                .__METHOD__
            );
        }

        if (is_bool($allowMe)!==true) {
            throw new Exception("Invalid parameter 'allowMe' at ".__METHOD__);
        }


        $application = $this->getApplication();

        $application->mockFacebookSignedRequestIfMockingEnabled();

        $accessTokenApp = $this->getAccessTokenApplication();
        $accessTokenUser = $this->getAccessTokenUser();

        $facebook = $this->getFacebook();

        if (    (strtolower($id)) ==="me") {
            if ($facebook->isValidUserId($facebook->getUserId())) {
                $id = $facebook->getUserId();
            }
            
            if (Lib_Utils_String::isEmpty($id)) {
                throw new Exception(
                    "Invalid parameter 'id' after replace by current userId at "
                    .__METHOD__);
            }
        }

        $this->requireValidGraphId($id, $allowMe, __METHOD__);


        $useCache = $cachePolicy->getEnabled();

        if (    (strtolower($id)) ==="me") {
            $useCache = false;
        }


        $apiEndpoint = "/".$id;

        if (is_string($apiEndpoint)) {
            $apiEndpoint = trim($apiEndpoint);
        }
        if (Lib_Utils_String::isEmpty($apiEndpoint)) {
            throw new Exception(
                "Invalid parameter 'apiEndpoint' at ".__METHOD__
            );
        }

        if (is_string($additionalGraph)) {
            $additionalGraph = trim($additionalGraph);
        }


        if (Lib_Utils_String::isEmpty($additionalGraph)!==true) {

            $additionalGraph = trim($additionalGraph);


            $startChars = array("/","?","&");
            $startWithChar = false;
            foreach($startChars as $startChar) {
                if (
                    Lib_Utils_String::startsWith(
                        $additionalGraph, $startChar, true)
                ) {

                    $startWithChar = true;
                    break;
                }
            }

            if ($startWithChar) {
                $apiEndpoint .= $additionalGraph;
            } else {
                $apiEndpoint .= "/".$additionalGraph;
            }
        }



        if (strpos($apiEndpoint, "//")!==false) {
            throw new Exception(
                "Invalid parameter 'apiEndpoint' contains double dash at "
                .__METHOD__
            );
        }



        if ($apiEndpointParams === null) {
            $apiEndpointParams = array();
        }
        if (is_array($apiEndpointParams)!==true) {
            throw new Exception(
                "Invalid parameter 'apiEndpointParams' at ".__METHOD__
            );
        }





        if (Lib_Utils_String::isEmpty($accessTokenApp)) {

            //$response["error"] = new Exception("invalid fb app accesstoken");
            //return $response;
        }

        if (Lib_Utils_String::isEmpty($accessTokenUser)) {

            //$response["error"] = new Exception("invalid fb user accesstoken");
            //return $response;
        }


        $apiHttpMethod = "GET";

        $clientResponse = $this->newResponse();
        $clientResponse->setRequestEndpoint($apiEndpoint);
        $clientResponse->setRequestEndpointParams($apiEndpointParams);
        $clientResponse->setRequestHttpMethod($apiHttpMethod);

//var_dump($apiEndpoint);var_dump($apiEndpointParams);
        if (($useCache===true) && ($cachePolicy->getLoad()===true)) {
            $this->getCache()->loadClientResponse(
                $clientResponse,
                $cachePolicy,

                $id,
                $additionalGraph,
                $apiEndpoint,
                $apiEndpointParams

            );
            if (
                    ($clientResponse->hasResult())
                    && ($clientResponse->hasError()!==true)
            ){
                return $clientResponse;
            }
        }



        // do api call
        $apiResponse = null;
        $apiError = null;
        try {
            $apiResponse = $facebook->api(
                $apiEndpoint, $apiHttpMethod, $apiEndpointParams
            );
            $clientResponse->setDataProvider($apiResponse);

        } catch (FacebookApiException $facebookApiException) {
            $apiError = $facebookApiException;
            $clientResponse->setError($apiError);
        }





        if (($useCache===true) && ($cachePolicy->getSave()===true)) {
            $this->getCache()->saveClientResponse(
                $clientResponse,
                $cachePolicy,

                $id,
                $additionalGraph,
                $apiEndpoint,
                $apiEndpointParams
           );


        }




        return $clientResponse;
    }



    /**
     * @param Lib_Facebook_Client_Response $clientResponse
     * @param Lib_Facebook_Client_CacheInvalidator $cacheInvalidator
     * @return null
     */
    public function apiGetInvalidate(
        Lib_Facebook_Client_Response $clientResponse,
        Lib_Facebook_Client_CacheInvalidator $cacheInvalidator

    )
    {


        return $this->getCache()
                ->invalidateClientResponse($clientResponse, $cacheInvalidator);

    }




    /**
     * @throws Exception
     * @param  $id
     * @param  $additionalGraph
     * @param  $apiEndpointParams
     * @param  $allowMe
     * @return Lib_Facebook_Client_Response
     */
    public function apiPostById(

        $id,
        $additionalGraph,
        $apiEndpointParams,

        $allowMe



    )
    {

        if (is_string($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        $id = trim($id);
        if (Lib_Utils_String::isEmpty($id)) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        if (ctype_alnum($id)!==true) {
            throw new Exception("Invalid parameter 'id' must be alphanum at "
                                .__METHOD__
            );
        }

        if (is_bool($allowMe)!==true) {
            throw new Exception("Invalid parameter 'allowMe' at ".__METHOD__);
        }


        $application = $this->getApplication();

        $application->mockFacebookSignedRequestIfMockingEnabled();

        $accessTokenApp = $this->getAccessTokenApplication();
        $accessTokenUser = $this->getAccessTokenUser();

        $facebook = $this->getFacebook();

        if (    (strtolower($id)) ==="me") {
            if ($facebook->isValidUserId($facebook->getUserId())) {
                $id = $facebook->getUserId();
            }

            if (Lib_Utils_String::isEmpty($id)) {
                throw new Exception(
                    "Invalid parameter 'id' after replace by current userId at "
                    .__METHOD__);
            }
        }

        $this->requireValidGraphId($id, $allowMe, __METHOD__);


        $apiEndpoint = "/".$id;

        if (is_string($apiEndpoint)) {
            $apiEndpoint = trim($apiEndpoint);
        }
        if (Lib_Utils_String::isEmpty($apiEndpoint)) {
            throw new Exception(
                "Invalid parameter 'apiEndpoint' at ".__METHOD__
            );
        }

        if (is_string($additionalGraph)) {
            $additionalGraph = trim($additionalGraph);
        }


        if (Lib_Utils_String::isEmpty($additionalGraph)!==true) {

            $additionalGraph = trim($additionalGraph);


            $startChars = array("/","?","&");
            $startWithChar = false;
            foreach($startChars as $startChar) {
                if (
                    Lib_Utils_String::startsWith(
                        $additionalGraph, $startChar, true)
                ) {

                    $startWithChar = true;
                    break;
                }
            }

            if ($startWithChar) {
                $apiEndpoint .= $additionalGraph;
            } else {
                $apiEndpoint .= "/".$additionalGraph;
            }
        }



        if (strpos($apiEndpoint, "//")!==false) {
            throw new Exception(
                "Invalid parameter 'apiEndpoint' contains double dash at "
                .__METHOD__
            );
        }



        if ($apiEndpointParams === null) {
            $apiEndpointParams = array();
        }
        if (is_array($apiEndpointParams)!==true) {
            throw new Exception(
                "Invalid parameter 'apiEndpointParams' at ".__METHOD__
            );
        }





        if (Lib_Utils_String::isEmpty($accessTokenApp)) {

            //$response["error"] = new Exception("invalid fb app accesstoken");
            //return $response;
        }

        if (Lib_Utils_String::isEmpty($accessTokenUser)) {

            //$response["error"] = new Exception("invalid fb user accesstoken");
            //return $response;
        }


        $apiHttpMethod = "POST";

        $clientResponse = $this->newResponse();
        $clientResponse->setRequestEndpoint($apiEndpoint);
        $clientResponse->setRequestEndpointParams($apiEndpointParams);
        $clientResponse->setRequestHttpMethod($apiHttpMethod);

        $log = array(
            $apiEndpoint,
            $apiEndpointParams,
            $apiHttpMethod,
        );
        var_dump($log);

        $apiResponse = null;
        $apiError = null;
        try {
            $apiResponse = $facebook->api(
                $apiEndpoint, $apiHttpMethod, $apiEndpointParams
            );
            $clientResponse->setDataProvider($apiResponse);

        } catch (FacebookApiException $facebookApiException) {
            $apiError = $facebookApiException;
            $clientResponse->setError($apiError);
        }



        return $clientResponse;
    }

















    /**
     * WILL NOT SUPPORT CACHING, SINCE WE HAVE "ME" issue
     * @throws Exception
     * @param  string $apiEndpoint
     * @param  string|null $additionalGraph
     * @param  array|null $apiEndpointParams
     * @return Lib_Facebook_Client_Response
     */
    public function apiGet(
        $apiEndpoint, $additionalGraph, $apiEndpointParams
    )
    {

        if (is_string($apiEndpoint)) {
            $apiEndpoint = trim($apiEndpoint);
        }
        if (Lib_Utils_String::isEmpty($apiEndpoint)) {
            throw new Exception(
                "Invalid parameter 'apiEndpoint' at ".__METHOD__
            );
        }

        if (is_string($additionalGraph)) {
            $additionalGraph = trim($additionalGraph);
        }



        if (Lib_Utils_String::isEmpty($additionalGraph)!==true) {

            $additionalGraph = trim($additionalGraph);

            $startChars = array("?","&","/");
            $startWithChar = false;
            foreach($startChars as $startChar) {
                if (
                    Lib_Utils_String::startsWith(
                        $additionalGraph, $startChar, true)
                ) {

                    $startWithChar = true;
                    break;
                }
            }

            if ($startWithChar) {
                $apiEndpoint .= $additionalGraph;
            } else {
                $apiEndpoint .= "/".$additionalGraph;
            }
        }




        if (strpos($apiEndpoint, "//")!==false) {
            throw new Exception(
                "Invalid parameter 'apiEndpoint' contains double dash at "
                .__METHOD__
            );
        }


        if ($apiEndpointParams === null) {
            $apiEndpointParams = array();
        }
        if (is_array($apiEndpointParams)!==true) {
            throw new Exception(
                "Invalid parameter 'apiEndpointParams' at ".__METHOD__
            );
        }


        $application = $this->getApplication();

        $application->mockFacebookSignedRequestIfMockingEnabled();
        $facebook = $this->getFacebook();



        $accessTokenApp = $this->getAccessTokenApplication();
        if (Lib_Utils_String::isEmpty($accessTokenApp)) {

            //$response["error"] = new Exception("invalid fb app accesstoken");
            //return $response;
        }

        $accessTokenUser = $this->getAccessTokenUser();
        if (Lib_Utils_String::isEmpty($accessTokenUser)) {

            //$response["error"] = new Exception("invalid fb user accesstoken");
            //return $response;
        }


        $apiHttpMethod = "GET";

        $clientResponse = $this->newResponse();
        $clientResponse->setRequestEndpoint($apiEndpoint);
        $clientResponse->setRequestEndpointParams($apiEndpointParams);
        $clientResponse->setRequestHttpMethod($apiHttpMethod);


        $apiCacheVersion = 1;
        $apiCacheOrigin = array(

            "cache" => array(
                "class" => null,
                "version" => 1,
            ),
            "client" => array(
                "class" => get_class($this),
                "method" => __METHOD__,
            ),
            "request" => array(
                "endpoint" =>
                    $clientResponse->getRequestEndpoint(),
                "endpointParams" =>
                    $clientResponse->getRequestEndpointParams(),
                "httpMethod" =>
                    $clientResponse->getRequestHttpMethod(),
            ),
            "response" => array(
                "class" => get_class($clientResponse),
            ),
            "accessToken" => array(
                "application" => $accessTokenApp,
                "user" => $accessTokenUser,
            ),

        );
        $apiCacheOriginJson = json_encode($apiCacheOrigin);
        $apiCacheChecksum = md5($apiCacheOriginJson)
                            .sha1($apiCacheOriginJson);
        $apiCacheKey = get_class($this)."_"
                       .get_class($clientResponse)
                       ."_v".$apiCacheVersion;
        $apiCacheKey = str_replace(".", "___", $apiCacheKey);
        $apiCacheKey = str_replace("::", "___", $apiCacheKey);
        $apiCacheKey = str_replace(":", "___", $apiCacheKey);

        $apiCacheKey .= "___checksum___".$apiCacheChecksum;




        $apiResponse = null;
        $apiError = null;
        try {
            $apiResponse = $facebook->api(
                $apiEndpoint, $apiHttpMethod, $apiEndpointParams
            );
            $clientResponse->setDataProvider($apiResponse);

        } catch (FacebookApiException $facebookApiException) {
            $apiError = $facebookApiException;
            $clientResponse->setError($apiError);
        }

        if ($clientResponse->hasError()!==true) {

            $apiCacheData = (array)$apiCacheOrigin;
            $apiCacheData["cache"]["modified"] = time();
            $apiCacheData["response"]["dataProvider"] =
                    $clientResponse->getDataProvider();

            // store to cache
            $apiCacheValue = json_encode($apiCacheData);
            // $this->getCache()->save($apiCacheKey, $apiCacheValue);

        }





        return $clientResponse;
    }


    /**
     * @param  int|string|null|mixed $id
     * @return bool
     */
    public function isValidId($id)
    {
        return $this->getFacebook()->isValidId($id);
    }


    /**
     * @throws Lib_Application_Exception
     * @param  mixed $id
     * @param  string|null $errorMethod
     * @return void
     */
    public function requireValidGraphId($id, $allowMe, $errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        if ($allowMe === null) {
            $allowMe = false;
        }

        $isValid = $this->isValidId($id);
        if ($allowMe===true) {
            if ($id==="me") {
                $isValid = true;
            }
        }



        if ($isValid!==true) {


            $e = new Lib_Application_Exception("Invalid graph id");
            $e->setMethod($errorMethod);
            $e->setFault(array(
                             "id" => $id,
                             "allowMe" => $allowMe,
                         ));
            throw $e;
        }

    }



	
}
