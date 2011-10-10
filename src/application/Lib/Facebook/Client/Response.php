<?php
/**
 * Lib_Facebook_Client_Response Class
 *
 * @package Lib_Facebook_Client
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Client_Response
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
class Lib_Facebook_Client_Response
{


    protected $_cacheData;


    /**
     * @var array|null
     */
    protected $_dataProvider;

    /**
     * @var Exception|null
     */
    protected $_error;



    /**
     * @var string|null
     */
    protected $_requestEndpoint;

    /**
     * @var array|null
     */
    protected $_requestEndpointParams;

    /**
     * @var string|null
     */
    protected $_requestHttpMethod;








    /**
     * @var Lib_Facebook_Client_Client
     */
    //protected $_client;

    /**
     * @return Lib_Facebook_Client_Client|null
     */
    /*
    public function getClient()
    {
        return $this->_client;
    }*/

    /**
     * @param Lib_Facebook_Client_Client $client
     * @return void
     */
    /*
    public function setClient(Lib_Facebook_Client_Client $client)
    {
        $this->_client = $client;
    }*/







    /**
     * @param  array|null $value
     * @return void
     */
    public function setCacheData($value) {

        if ($value !== null) {

            if (is_array($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }
        }

        $this->_cacheData = $value;
    }

    /**
     * @return array|null
     */
    public function getCacheData()
    {
        return $this->_cacheData;
    }




    /**
     * @param  string|null $value
     * @return void
     */
    public function setRequestEndpoint($value) {

        if ($value !== null) {

            if (Lib_Utils_String::isEmpty($value)) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }

        }

        $this->_requestEndpoint = $value;
    }
    /**
     * @param  $value
     * @return null|string
     */
    public function getRequestEndpoint() {
        return $this->_requestEndpoint;
    }

    /**
     * @param  array|null $value
     * @return void
     */
    public function setRequestEndpointParams($value) {
        if ($value !== null) {

            if (is_array($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }

        }
        $this->_requestEndpointParams = $value;
    }
    /**
     * @param  $value
     * @return null|string
     */
    public function getRequestEndpointParams() {
        return $this->_requestEndpointParams;
    }



    /**
     * @param  string|null $value
     * @return void
     */
    public function setRequestHttpMethod($value) {

        if ($value !== null) {

            if (Lib_Utils_String::isEmpty($value)) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }

            $value = strtoupper($value);

        }

        $this->_requestHttpMethod = $value;
    }
    /**
     * @param  $value
     * @return null|string
     */
    public function getRequestHttpMethod() {
        return $this->_requestHttpMethod;
    }

    

    /**
     * @return array|null
     */
    public function getDataProvider()
    {
        return $this->_dataProvider;
    }

    /**
     * @param  $value
     * @return void
     */
    public function setDataProvider($value)
    {
        $this->_dataProvider = $value;

        if ($value instanceof Exception) {
            $this->setError($value);
        }
    }


    /**
     * @param Exception $error
     * @return void
     */
    public function setError(Exception $error)
    {
        $this->_error = $error;
    }

    /**
     * @return Exception|null
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return (bool)($this->_error instanceof Exception);
    }


    /**
     * @param  bool|null $nice
     * @return mixed|null|string
     */
    public function getErrorClass($nice)
    {
        $result = null;
        if (is_bool($nice)!==true) {
            $nice = false;
        }

        $error = $this->getError();
        if (($error instanceof Exception)!==true) {
            return $result;
        }
        $result = get_class($error);
        if ($nice===true) {
            $result = str_replace("_", ".", $result);
        }
        return $result;
    }


    /**
     * @return string
     */
    public function getErrorMessage()
    {
        $result = "";
        $error = $this->getError();
        if (($error instanceof Exception)!==true) {
            return $result;
        }
        return $error->getMessage();
    }

    /**
     * @return null|string
     */
    public function getErrorType()
    {
        $result = null;
        $error = $this->getError();
        if (($error instanceof Exception)!==true) {
            return $result;
        }
        if ($error instanceof FacebookApiException) {
            /**
             * @var FacebookApiException $error
             */
            return $error->getType();
        }

    }


    /**
     * @return mixed|null
     */
    public function getResult()
    {
        $result = null;
        if ($this->hasError()) {
            return $result;
        }
        $dataProvider = $this->getDataProvider();
        return $dataProvider;
    }

    /**
     * @return bool
     */
    public function hasResult()
    {
        $result = false;
        if ($this->hasError()) {
            return $result;
        }
        $apiResult = $this->getResult();
        return (bool)(is_array($apiResult));
    }


    /**
     * @param  string $name
     * @return mixed|null
     */
    public function getResultProperty($name) {
        if ($this->hasError()) {
            return null;
        }
        if ($this->hasResult()!==true) {
            return null;
        }

        $result = $this->getResult();

        return Lib_Utils_Array::getProperty($result, $name);
    }



    /**
     * @throws Lib_Application_Exception
     * @param  $errorMethod
     * @return 
     */
    public function delegateError($errorMethod) {
        if ($this->hasError()!==true) {
            return;
        }

        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        $error = $this->getError();
        $exception = new Lib_Application_Exception(
            "FACEBOOK_API_CLIENT_EXCEPTION"
        );

        $exception->setMethod($errorMethod);
        $exception->setData(array(
                                "class" => $this->getErrorClass(true),
                                 "type" => $this->getErrorType(),
                                 "message" => $this->getErrorMessage(),
                             ));
        $exception->setFault(array(
                                "class" => $this->getErrorClass(true),
                                 "type" => $this->getErrorType(),
                                 "message" => $this->getErrorMessage(),
                             ));
        throw $exception;
    }

	
}
