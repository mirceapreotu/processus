<?php

/**
 * Lib_JsonRpc_Gateway
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_JsonRpc_Gateway
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_JsonRpc_Gateway implements Lib_JsonRpc_GatewayInterface
{

    



    /**
     * @var bool
     */
    protected $_isRequestCrypted;

     /**
     * @var bool
     */
    protected $_isRequestBatched;

    /**
     * @var bool
     */
    protected $_isRequestMocked;

    /**
     * @var array|string
     */
    protected $_requestMockData;


    /**
     * @var bool
     */
    protected $_requestMockIgnoreCryptRequirements;

    
     /**
     * @var int
     */
    protected $_apiVersion;

    /**
     * @return bool
     */
    public function isRequestCrypted()
    {
        return (bool)($this->_isRequestCrypted === true);
    }



    /**
     * @return bool
     */
    public function isRequestBatched()
    {
        return (bool)($this->_isRequestBatched === true);
    }


    /**
     * @return bool
     */
    public function isRequestMocked()
    {
        return (bool)($this->_isRequestMocked === true);
    }

    /**
     * @return bool
     */
    public function isRequestMockIgnoreCryptRequirements()
    {
        return (bool)($this->_requestMockIgnoreCryptRequirements === true);
    }



    /**
     * @return int
     */
    public function getApiVersion()
    {
        if (is_int($this->_apiVersion)) {
            return $this->_apiVersion;
        }

        $this->_apiVersion = (int)$this->fetchApiVersion();
        return $this->_apiVersion;
    }



    // +++++++++++++ config +++++++++++++++++++++++++

     /**
     * @var Zend_Config
     */
    protected $_config;
    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(

        "enabled" => true,

        "batch" => array(
            "maxItems" => 100,
        ),
        "server" => array(
            "class" => "Lib_JsonRpc_Server"
            //"class" => "App_JsonRpc_V1_Cms_Server",
        ),

        "response" => array(
            "headers" => array(
                "Content-Type: application/json; charset=utf-8",
            ),
        ),

        "destination" => array(
            "whitelist" => array(
                "cms.*"
            ),
            "blacklist" => array(
            ),
        ),
         
        "command" => array(
            "whitelist" => array(
                //e.g. "describeApi"
            ),
            "blacklist" => array(
            ),
        ),

        "crypt" => array(
            "key" => null, //e.g.: "dfjfueurencndzZTThendn"
            "destination" => array(
                // which destinations require crypted requests?
                "requirelist" => array(
                    //e.g. "*"
                ),
                // which destinations do not require crypted requests?
                "ignorelist" => array(
                ),
            ),
        ),

    );


   


    


    /**
     * @return Zend_Config
     */
    public function getConfig()
    {
        if (($this->_config instanceof Zend_Config) !== true) {
            $this->_parseConfig();
            if ($this->_config instanceof Zend_Config) {
                $this->_onConfigParsed();
            } else {
                throw new Exception(
                    "Method returns invalid result at ".__METHOD__
                );
            }
        }
        return $this->_config;
    }

    /**
     * @return Zend_Config
     */
    public function getConfigDefault()
    {
        return new Zend_Config((array)$this->_configDefault, true);
    }



    protected function _parseConfig()
    {
        $configDefault = $this->getConfigDefault();
/*
        $zendConfig = Lib_Utils_Config_Parser::loadByClassName(
            get_class($this)
        );
*/
        //loadByClassOrSuperClasses($instance)
        $zendConfig = Lib_Utils_Config_Parser::loadByClassOrSuperClasses(
            $this
        );
        $config = Lib_Utils_Config_Parser::merge(
            array(
                 $configDefault,
                 $zendConfig
            )
        );

        $this->_config = $config;


    }


    /**
     * your hooks here
     * @return void
     */
    protected function _onConfigParsed()
    {
        $config = $this->getConfig();

    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++

    

    

    /**
	 *
	 * @return boolean
	 */
	public function isDebugMode()
	{
		return Bootstrap::getRegistry()->getDebug()->isDebugMode();
	}

    /**
     * @return bool
     */
    public function isEnabled()
    {

        $result = false;
        $config = $this->getConfig();
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        return (bool)($config->enabled === true);
    }

    // +++++++++++++++++ config.batch ++++++++++++++++++++++++

    /**
     * @return int
     */
    public function getBatchMaxItems()
    {
        $result = 100;
        $config = $this->getConfig()->batch;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        $maxItems = (int)$config->maxItems;
        if ($maxItems>0) {
            return $maxItems;
        }
        return $result;
    }


    // +++++++++++++++++++ config: destination ++++++++++++++++++++

    /**
     * @param  string $destination
     * @return bool
     */
    public function isDestinationWhitelisted($destination)
    {

        $target = $destination;

        $result = false;
        $listConfig = $this->getConfig()->destination->whitelist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $target, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }
    /**
     * @param  string $destination
     * @return bool
     */
    public function isDestinationBlacklisted($destination)
    {

        $target = $destination;

        $result = false;
        $listConfig = $this->getConfig()->destination->blacklist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $target, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }


    /**
     * @param  string $destination
     * @return bool
     */
    public function isDestinationAllowed($destination)
    {
        $isWhiteListed = $this->isDestinationWhitelisted($destination);
        $isBlacklisted = $this->isDestinationBlacklisted($destination);

        return (bool)(($isWhiteListed === true) && ($isBlacklisted !== true));
    }


    // ++++++++++++++++ config.command +++++++++++++++++++++++++++++


     /**
     * @param  string $method
     * @return bool
     */
    public function isCommandWhitelisted($method)
    {

        $target = $method;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->command;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->whitelist;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        $list = $config->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $target, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }
    /**
     * @param  string $method
     * @return bool
     */
    public function isCommandBlacklisted($method)
    {

        $target = $method;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->command;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->blacklist;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        $list = $config->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $target, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }


    /**
     * @param  string $method
     * @return bool
     */
    public function isCommandAllowed($method)
    {
        $isWhiteListed = $this->isCommandWhitelisted($method);
        $isBlacklisted = $this->isCommandBlacklisted($method);

        return (bool)(($isWhiteListed === true) && ($isBlacklisted !== true));
    }





    // ++++++++++++++++++++++++ config.crypt +++++++++++++++++++++

    /**
     * @return null|string
     */
    public function getCryptKey()
    {
        $result = null;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->crypt;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        return (string)$config->key;
    }


    /**
     * @param  string $destination
     * @return bool
     */
    public function isCryptDestinationRequirelisted($destination)
    {

        $target = $destination;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->crypt;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->destination;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->requirelist;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        /**
         * @var Zend_Config $listConfig
         */
        $list = $config->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $target, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }
    /**
     * @param  string $destination
     * @return bool
     */
    public function isCryptDestinationIgnorelisted($destination)
    {
        $target = $destination;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->crypt;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->destination;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->ignorelist;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        /**
         * @var Zend_Config $listConfig
         */
        $list = $config->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $target, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }


    /**
     * @param  string $destination
     * @return bool
     */
    public function isCryptDestinationRequired($destination)
    {
        $isRequireListed = $this->isCryptDestinationRequirelisted($destination);
        $isIgnorelisted = $this->isCryptDestinationIgnorelisted($destination);

        $isRequired = (bool)(
                ($isRequireListed === true) && ($isIgnorelisted !== true)
        );
        return $isRequired;
    }


    // +++++++++++++++++++++ mock ++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @param  array $data
     * @param bool $ignoreCryptRequirements
     * @return void
     */
    public function setRequestMockData($data, $ignoreCryptRequirements = false)
    {
        if (is_array($data)!==true) {
            throw new Exception(
                "Parameter data must be an array at ".__METHOD__
            );
        }

        $requestText = json_encode($data);
        $requestData = json_decode($requestText, true);
        $this->_isRequestBatched =
                        (bool)is_array(json_decode($requestText, false));

        if (is_array($requestData)!==true) {
            throw new Exception(
                "Parameter data is not serializable to an array at ".__METHOD__
            );
        }

        $this->_requestMockData = $requestData;
        $this->_requestMockIgnoreCryptRequirements = (bool)
                ( $ignoreCryptRequirements===true );
        $this->_isRequestMocked = true;
    }

    /**
     * @return array|null
     */
    public function getRequestMockData()
    {
        return $this->_requestMockData;
    }
    /**
     * @return void
     */
    public function unsetRequestMockData()
    {
        $this->_requestMockData = null;
        $this->_isRequestMocked = false;
        $this->_requestMockIgnoreCryptRequirements = false;
    }






    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++




    /**
     *
     * @return array
     */
    public function newResponseHeaders()
    {


        $result = array();
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->response;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        $config = $config->headers;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        return $config->toArray();

    }



    /**
     *
     * @return Lib_JsonRpc_Server
     */
    public function newServer()
    {
        $class = null;

        $config = $this->getConfig()->server;
        if (($config instanceof Zend_Config)!== true) {
            //return $result;
        } else {
            $class = $config->class;
        }
        try {
            if (Lib_utils_String::isEmpty($class) === true) {
                throw new Exception("class name cant be empty!");
            }
            /**
             * @var Lib_JsonRpc_Server $instance
             */
            $instance = new $class();
            if (($instance instanceof Lib_JsonRpc_Server) !== true) {
                throw new Exception(
                    "class name must be instanceof Lib_JsonRpc_Server!"
                );
            }

            $instance->setGateway($this);
            return $instance;

        } catch(Exception $exception) {
            $e = new Lib_Application_Exception(
                    "Invalid Config - server class at ".__METHOD__
                );
                $e->setMethod(__METHOD__);
                $e->setFault(array(
                        "class" => $class,
                        "message" => $exception->getMessage(),
                             ));
            //var_dump($e->getFault());
            throw $e;
        }
    }


    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return int
     */
    public function fetchApiVersion()
    {
        $value = (int)Lib_Utils_Array::getProperty($_GET,"v");
        return $value;
    }


   

    /**
	 * @return string
	 */
	public function fetchRequestText()
	{
		$requestText = file_get_contents('php://input');
		return $requestText;
	}

    /**
     * @return mixed|null
     */
    public function fetchPostJsonRpcText()
    {
        $postText = Lib_Utils_Array::getProperty(
            $_POST,
            "jsonrpc"
        );
        return $postText;
    }


       /**
     * @return array|null
     */
    public function fetchRequestData()
    {
        $requestData = null;
        $this->_isRequestCrypted = false;
        $this->_isRequestBatched = false;

        // try from mock
        if ($this->isRequestMocked()) {
            return $this->getRequestMockData();
        }

        // try from post.jsonrpc
        $requestText = $this->fetchPostJsonRpcText();
        if (is_string($requestText)) {

            $requestData = json_decode($requestText, true);
            if (is_array($requestData)) {

                $this->_isRequestBatched =
                        (bool)is_array(json_decode($requestText, false));

                return $requestData;
            } else {

                // try decrypt
                $crypter = new Lib_JsonRpc_CrypterFlash();
                $key = $this->getCryptKey();
                if (Lib_Utils_String::isEmpty($key) !== true) {
                    $crypter->setKey($key);
                    $requestData = $crypter->decodeRequest();
                    if (is_array($requestData)) {
                        $this->_isRequestCrypted = true;
                        $this->_isRequestBatched = false;
                        return $requestData;
                    }
                }

            }

        }

        // try from php:input
        $requestText = $this->fetchRequestText();
        if (is_string($requestText)) {
            $requestData = json_decode($requestText, true);
            if (is_array($requestData)) {
               $this->_isRequestBatched =
                       (bool)is_array(json_decode($requestText, false));

               return $requestData;
            }
        }

        return $requestData;
    }



    /**
     * @return array
     */
    public function fetchCommand()
    {
        $command = array(
            "method" => null,
            "params" => array(),
        );

        $commandText = (string)trim(
            strtolower(Lib_Utils_Array::getProperty(
            $_GET, "command"
        )));
        if (strlen($commandText)<1) {
            return $command;
        }
        $commandData = json_decode($commandText, true);
        if (is_array($commandData)!==true) {
            $e = new Lib_Application_Exception(
                "Invalid command data (decode failed)"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                             "gateway" => str_replace(
                                 "_", ".", get_class($this)
                             ),
                         ));
            throw $e;
        }

        $method = Lib_Utils_Array::getProperty($commandData, "method");
        $params = Lib_Utils_Array::getProperty($commandData, "params");
        
        if (is_array($params)!==true) {
            $params = array();
        }

        $command = array(
            "method" => (string)$method,
            "params" => (array)$params,
        );


        return $command;
    }



    /**
     * @param  $data
     * @return int|string
     */
    public function encodeResponseDataCryptedFlash($data)
    {
        $crypter = new Lib_JsonRpc_CrypterFlash();
        $key = $this->getCryptKey();

        if (Lib_Utils_String::isEmpty($key)) {
            throw new Exception("Config error (no crypt key)");
        }
        
        $crypter->setKey($key);
        $data = $crypter->encodeResponse($data);
        return $data;
    }


    

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @throws Exception
     * @return
     */
	public function run()
	{


		try {

            if ($this->isEnabled() !== true) {
                throw new Exception("GATEWAY IS NOT ENABLED");
            }

            $this->_run();

		} catch (Exception $exception) {

			$debugMode = $this->isDebugMode();
			$error = array(
				"code" => $exception->getCode(),
				"message" => $exception->getMessage(),
			);
            if ($exception instanceof Lib_Application_Exception) {
                /**
                 * @var Lib_Application_Exception $exception
                 */
                $error["data"] = $exception->getData();
                $error["userMessage"] = $exception->getUserMessage();
            }

			if ($debugMode === true) {
				$error["class"] = get_class($exception);
				$error["line"] = $exception->getLine();
				$error["file"] = $exception->getFile();
				$error["stackTrace"] = $exception->getTraceAsString();
                if ($exception instanceof Lib_Application_Exception) {
                    /**
                     * @var Lib_Application_Exception $exception
                     */
                    $error["fault"] = $exception->getFault();
                    $error["method"] = $exception->getMethod();
                    $error["methodLine"] = $exception->getMethodLine();
                }
			}

			$response = array(
				"result" => null,
				"error" => $error,
			);

			// out
			//header('Content-Type: application/json');
            $responseHeaders = $this->newResponseHeaders();
            foreach($responseHeaders as $responseHeader) {
                header($responseHeader);
            }
			echo json_encode($response);

			return;
		}


	}

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return void
     */
    protected function _run()
    {
        $command = $this->fetchCommand();
        //var_dump($command);
        if (Lib_Utils_String::isEmpty($command["method"]) !== true) {
            $this->_processCommand($command);
        } else {
            // no special command? so go for it
            $this->_processRpc();
        }
    }

    /**
     * @param  array $command
     * @return
     */
    protected function _processCommand($command)
    {
        $server = $this->newServer();
        $result = $server->executeCommand($command);
        $response = array(
            "result" => $result,
            "error" => null,
        );
        // out
        $responseHeaders = $this->newResponseHeaders();
        foreach($responseHeaders as $responseHeader) {
            header($responseHeader);
        }
        echo json_encode($response);
        return;
    }




    /**
     * @throws Exception
     * @return
     */
    protected function _processRpc()
    {

        $jsonRpcData = $this->fetchRequestData();

        if (is_array($jsonRpcData) !== true) {
            throw new Exception("Invalid Request");
        }

        // prepare
        $isRpcBatched = $this->isRequestBatched();

        $jsonRpcBatchItemList = array();

        if ($isRpcBatched === true) {
            $jsonRpcBatchItemList = (array)$jsonRpcData;
        } else {
            $jsonRpcBatchItemList[] = $jsonRpcData;
        }


        // do batch
        $responseList = array();

        $batchItemCount = 0;
        foreach ($jsonRpcBatchItemList as $jsonRpcBatchItem) {

            $batchItemCount++;
            if ($batchItemCount > $this->getBatchMaxItems()) {
                break;
            }

            $server = $this->newServer();
            $server->setGateway($this);


            $server->getRequest()
                    ->setDataProvider($jsonRpcBatchItem);

            $server->getRequest()
                    ->setVersion(
                        Lib_Utils_Array::getProperty(
                            $jsonRpcBatchItem,
                            "jsonrpc"
                        )
                    );
            $server->getRequest()
                    ->setId(
                        Lib_Utils_Array::getProperty(
                            $jsonRpcBatchItem,
                            "id"
                        )
                    );
            $server->getRequest()
                    ->setMethod(
                        Lib_Utils_Array::getProperty(
                            $jsonRpcBatchItem,
                            "method"
                        )
                    );
            $server->getRequest()
                    ->setParams(
                        Lib_Utils_Array::getProperty(
                            $jsonRpcBatchItem,
                            "params"
                        )
                    );

            $server->run();

            $response = $this->_getResponseSanitized($server);

            $responseList[] = $response;

        }


        $responseData = null;
        if ($isRpcBatched === true) {
            $responseData = $responseList;
        } else {
            $responseData = Lib_Utils_Array::getProperty($responseList, 0);
        }


        // out
        //header('Content-Type: application/json');
        $responseHeaders = $this->newResponseHeaders();
        foreach($responseHeaders as $responseHeader) {
            header($responseHeader);
        }


        if ($this->isRequestCrypted()) {
            try {
                $responseText =
                        $this->encodeResponseDataCryptedFlash($responseData);
                echo $responseText;
            } catch(Exception $e) {
                //log?
                die("Error while encoding response");
            }
        } else {
            $responseText = json_encode($responseData);
            echo $responseText;
        }

        return;
    }


    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


	/**
	 * @param Lib_JsonRpc_Server $server
	 * @return array
	 */
	protected function _getResponseSanitized(Lib_JsonRpc_Server $server)
	{

		$response = array(
			//"_in" =>$jsonRpcBatchItem,
			"id" => $server->getResponse()->getId(),
			"jsonrpc" => $server->getResponse()->getVersion(),
			"result" => $server->getResponse()->getResult(),
			"error" => $server->getResponse()->getError(),
		);

		$isDebugMode = $this->isDebugMode();

		if ($isDebugMode === true) {

		}

		if ($server->getResponse()->getError() instanceof Exception) {
			$exception = $server->getResponse()->getError();

			$response["result"] = null;

			$unsetKeys = array("code", "stackTrace", "file", "line", "fault");

			if ($isDebugMode === true) {
				// expose full errors
				$unsetKeys = array();
			}

			if ($exception instanceof Lib_Application_Exception) {

				$error = $exception->toArray();
			} else {
				$error = Lib_Utils_Exception::toArray($exception);
			}

			foreach ($unsetKeys as $key) {
				unset($error[$key]);
			}


			$response["error"] = $error;
		}

		// add request method for easier debugging of batched requests
		if ($isDebugMode === true) {
			$response["__debug__" . __METHOD__] = array(
				"method" => $server->getRequest()->getMethod(),
			);
			$response["__debug__log__"] = $server->getLogger()->getItems();
		}


		return $response;

	}


    // ++++++++++++++++++++++++++++++++++++++++++++++++++++




}


