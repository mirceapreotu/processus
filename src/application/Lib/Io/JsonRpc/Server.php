<?php

/**
 * Lib_Io_JsonRpc_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Io_JsonRpc_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

class Lib_Io_JsonRpc_Server
{

    /**
     * @var ReflectionClass
     */
    protected $_serviceReflectionClass;
    /**
     * @var ReflectionMethod
     */
    protected $_serviceReflectionMethod;

    


    /**
     * @var Lib_Io_JsonRpc_Gateway
     */
    protected $_gateway;

	/**
	 *
	 * @var string
	 */
	protected $_classNamePrefix = 'App_JsonRpc_Service_';

    /**
     * @param Lib_Io_JsonRpc_Gateway $gateway
     * @return void
     */
    public function setGateway(Lib_Io_JsonRpc_Gateway $gateway)
    {
        $this->_gateway = $gateway;
    }

    /**
     * @return Lib_Io_JsonRpc_Gateway
     */
    public function getGateway()
    {
        return $this->_gateway;
    }


    /**
     * @return ReflectionClass
     */
    public function getServiceReflectionClass()
    {
        return $this->_serviceReflectionClass;
    }

    /**
     * @return ReflectionMethod
     */
    public function getServiceReflectionMethod()
    {
        return $this->_serviceReflectionMethod;
    }

	/**
	 * define what Interfaces a Service must implement
	 * @return array|null
	 */
	protected function _getRequiredServiceInterfaces()
	{
		return array(
			"Lib_Io_JsonRpc_ServiceInterface",
		);
	}

	/**
	 *
	 * @return array|null
	 */
	public function getExcludedDestinations()
	{
		return array(
		);
	}

	/**
	 * register service class.method names
	 * allowed for slave DB access
	 * @return array|null
	 */
	public function getSlaveDestinations()
	{
		return array(
		);
	}

	/**
	 * @return array
	 */
	public function getApiServiceNames()
	{
		return array(
			/*
            "BlogComment",
            "BlogPost",
            "Reflection",
            "Poll",
            "TestAction"

			 */
		);
	}

	/**
	 *
	 * @var Lib_Io_jsonRpc_Context
	 */
	protected $_context;


	/**
	 *
	 * @return Lib_Io_jsonRpc_Context_Interface
	 */
	public function getContext()
	{
		return $this->_context;
	}


	public function prepare()
	{

	}

	/**
	 *
	 * @param Lib_Io_jsonRpc_Context_Interface $context
	 */
	public function setContext(Lib_Io_jsonRpc_ContextInterface $context)
	{
		$this->_context = $context;
	}


	/**
	 * @var Lib_Io_JsonRpc_Logger
	 */
	protected $_logger;

	/**
	 *
	 * @return Lib_Io_JsonRpc_Logger $result
	 */
	public function getLogger()
	{
		if (($this->_logger instanceof Lib_Io_JsonRpc_Logger) !== true) {
			$this->_logger = $this->newLogger();
		}
		return $this->_logger;
	}

	/**
	 *
	 * @return Lib_Io_JsonRpc_Logger $result
	 */
	public function newLogger()
	{
		$result = new Lib_Io_JsonRpc_Logger();
		return $result;
	}

	/**
	 *
	 * @var string
	 */
	protected $_className;
	/**
	 *
	 * @var string
	 */
	protected $_methodName;


	/**
	 *
	 * @var Lib_Io_JsonRpc_Request
	 */
	protected $_request;
	/**
	 *
	 * @var Lib_Io_JsonRpc_Response
	 */
	protected $_response;

	/**
	 *
	 * @return Lib_Io_JsonRpc_Request
	 */
	public function getRequest()
	{
		if (($this->_request instanceof Lib_Io_JsonRpc_Request) !== true) {
			$this->_request = $this->newRequest();
		}

		return $this->_request;
	}

	/**
	 *
	 * @return Lib_Io_JsonRpc_Response
	 */
	public function getResponse()
	{
		if (($this->_response instanceof Lib_Io_JsonRpc_Response) !== true) {
			$this->_response = $this->newResponse();
		}

		return $this->_response;
	}


	/**
	 *
	 * @return Lib_Io_JsonRpc_Request
	 */
	public function newRequest()
	{
		return new Lib_Io_JsonRpc_Request();
	}

	/**
	 *
	 * @return Lib_Io_JsonRpc_Response
	 */
	public function newResponse()
	{
		return new Lib_Io_JsonRpc_Response();
	}

	/**
	 *
	 * @return string
	 */
	public function getClassNamePrefix()
	{
		return $this->_classNamePrefix;
	}

	/**
	 *
	 * @param string $prefix
	 */
	public function setClassNamePrefix($prefix)
	{
		$this->_classNamePrefix = $prefix;
	}

	/**
	 *
	 * @param string $className
	 */
	public function setClassName($className)
	{
		$this->_className = "" . $className;
	}

	/**
	 *
	 * @return string
	 */
	public function getClassName()
	{
		return $this->_className;
	}

	/**
	 *
	 * @param string $methodName
	 */
	public function setMethodName($methodName)
	{
		$this->_methodName = $methodName;
	}

	/**
	 *
	 * @return string
	 */
	public function getMethodName()
	{
		return $this->_methodName;
	}


	/**
	 *
	 * @param string $destination
	 * @return string|null
	 */
	public function getClassNameByDestination($destination)
	{
		$result = null;
		if (is_string($destination) !== true) {
			return $result;
		}

		$destination = trim($destination);

		$methodParts = (array)explode(".", $destination);
		$methodName = Lib_Utils_Array::pop($methodParts);

		$className = "" . $this->getClassNamePrefix();
		$className .= "" . implode("_", $methodParts);

		return $className;

	}

	/**
	 *
	 * @param string $destination
	 * @return string|null
	 */
	public function getClassMethodNameByDestination($destination)
	{
		$result = null;
		if (is_string($destination) !== true) {
			return $result;
		}

		$destination = trim($destination);

		$methodParts = (array)explode(".", $destination);
		$methodName = Lib_Utils_Array::pop($methodParts);
		if (is_string($methodName) !== true) {
			return $result;
		}

		$methodName = trim($methodName);

		if ((strlen($methodName) > 0) !== true) {
			return $result;
		}

		return $methodName;

	}

	/**
	 *
	 * @param string $className
	 * @param string $methodName
	 * @return string|null
	 */
	public function getDestinationByClassAndMethodName($className, $methodName)
	{
		$result = null;
		if (is_string($className) !== true) {
			return $result;
		}
		if (is_string($methodName) !== true) {
			return $result;
		}

		$className = trim($className);
		$methodName = trim($methodName);

		$prefix = "" . $this->getClassNamePrefix();

		$serviceName = Lib_Utils_String::removePrefix(
			$className,
			$prefix,
			false
		);

		if (is_string($serviceName)) {
			$serviceName = str_replace("_", ".", $serviceName);
		} else {
			return null;
		}

		$destination = $serviceName . "." . $methodName;

		return $destination;

	}


	/**
	 *
	 * @param Lib_Io_jsonRpc_ContextInterface $context
	 * @return void
	 */
	public function run(Lib_Io_jsonRpc_ContextInterface $context)
	{
		try {

			// prepare response (clone properties from request)
			$this->getResponse()
					->setId(
						$this->getRequest()->getId()
					);
			$this->getResponse()
					->setVersion(
						$this->getRequest()->getVersion()
					);

			// use context
			$this->setContext($context);
			$this->getContext()->setServer($this);



			$destination = (string)$this->getRequest()->getMethod();
			$this->_validateDestination($destination);


			$className = $this->getClassNameByDestination($destination);
			$this->setClassName($className);
			$methodName = $this->getClassMethodNameByDestination($destination);
			$this->setMethodName($methodName);


            $allowedServices = $this->getApiServiceNames();
            $sanitizedServiceName = strtolower(trim($className));
            $isAllowedService = false;
            if (Lib_Utils_String::isEmpty($sanitizedServiceName) !== true) {
                foreach($allowedServices as $serviceName) {

                    $serviceName = strtolower(trim($serviceName));
                    if (Lib_Utils_String::isEmpty($serviceName)) {
                        continue;
                    }
                    $serviceName = $this->getClassNamePrefix().$serviceName;
                    $serviceName = strtolower(trim($serviceName));
                    if (Lib_Utils_String::isEmpty($serviceName)) {
                        continue;
                    }

                    if ($sanitizedServiceName === $serviceName) {
                        $isAllowedService = true;
                        break;
                    }
                }
            }
            if ($isAllowedService !== true) {
                $e = new Lib_Application_Exception(
                    "Service is not available (server.apiServiceNames)"
                );
                $e->setMethod(__METHOD__);
                /*
                $e->setFault(array(
                    "serviceName" => $sanitizedServiceName,
                ));
                 
                 */
                throw $e;
            }


			/*
            $_destination = $this->getDestinationByClassAndMethodName(
			 $className, $methodName
			 );
			*/

			// check service class can be loaded by autoloader

			if (Lib_Utils_Class::exists($className) !== true) {
				$msg = "Invalid className at destination=" . $destination;
				throw new Exception($msg);
			}

			// reflection
			$reflectionClass = new ReflectionClass($className);
            $this->_serviceReflectionClass = $reflectionClass;
			$this->_validateServiceClass(
				$reflectionClass,
				$this->_getRequiredServiceInterfaces()
			);
			$this->_validateServiceClassMethod($reflectionClass, $methodName);

			$reflectionMethod = $reflectionClass->getMethod($methodName);
            $this->_serviceReflectionMethod = $reflectionMethod;
			$serviceInstanceMethodArgs = $this->getRequest()->getParams();
			/** @noinspection PhpParamsInspection */
			$this->_validateServiceClassMethodParams(
				$reflectionMethod,
				$serviceInstanceMethodArgs
			);


			// context
			$this->getContext()->prepare();


			$slaveDestinations = $this->getSlaveDestinations();
			$isAllowedOnSlave = (
			in_array($destination, $slaveDestinations)
			);

			if (!$isAllowedOnSlave) {
				// master on!
				Lib_Db_Xdb_Client::beginMaster();
			}

			// invoke
			$serviceInstance = $reflectionClass->newInstance($context);
			/** @noinspection PhpParamsInspection */
			$serviceCallResult = $this->_invokeArgs(
				$reflectionClass,
				$reflectionMethod,
				$serviceInstanceMethodArgs,
				$context
			);

			if (!$isAllowedOnSlave) {
				// master off!
				Lib_Db_Xdb_Client::endMaster();

			}

			//var_dump($serviceCallResult);

			// use result
			$this->getResponse()->setResult($serviceCallResult);

			//var_dump($this->getResponse());

			$this->_onResult($serviceCallResult);

		} catch (Exception $exception) {
			// use error
			$this->getResponse()->setError($exception);
			$this->_onError($exception);
		}
	}


	/**
	 *
	 * @param ReflectionClass $reflectionClass
	 * @return boolean
	 */
	public function isServiceClassInstantiable(
		ReflectionClass $reflectionClass
	)
	{
		$requiredInterfaces = $this->_getRequiredServiceInterfaces();
		return $this->_isServiceClassInstantiable(
			$reflectionClass,
			$requiredInterfaces
		);
	}

	/**
	 *
	 * @param ReflectionClass $reflectionClass
	 * @param string $methodName
	 * @return bool
	 */
	public function isServiceMethodInvokeable(
		ReflectionClass $reflectionClass,
		$methodName
	)
	{
		return $this->_isServiceMethodInvokeable(
			$reflectionClass,
			$methodName
		);
	}


	/**
	 * @throws Exception
	 * @param string $destination
	 * @return void
	 * @todo implement parsing excludedDestinatinations
	 */
	protected function _validateDestination($destination)
	{
		if (is_string($destination) !== true) {
			throw new Exception("Invalid rpc method = " . $destination);
		}
		$destination = trim($destination);
		if ((strlen($destination) > 0) !== true) {
			throw new Exception("Invalid rpc method = " . $destination);
		}

        // is restricted by gateway.allowDestinations ?
        $gateway = $this->getGateway();
        $allowedDestinations = $gateway->getAllowedDestinations();
        if (is_array($allowedDestinations)) {
            $isAllowedDestination = false;
            $_destination = strtolower($destination);
            foreach ($allowedDestinations as $allowedDestination) {
                $_allowedDestination = trim(strtolower($allowedDestination));
                if ($_destination === $_allowedDestination) {
                    $isAllowedDestination = true;
                    break;
                }
            }
            if ($isAllowedDestination !== true) {
                $e = new Lib_Application_Exception(
                    "ACCESS_DENIED"
                        ." (Destination ".$destination
                            . " is locked by Gateway "
                            . str_replace("_",".", get_class($gateway))
                        .")"
                );
                $e->setMethod(__METHOD__);
                $e->setFault(array(
                    "allowedDestinations" => $allowedDestinations
                ));
                throw $e;
            }
        }


        // check for excluded destinations by server
		$excludedDestinations = (array)$this->getExcludedDestinations();

		$_destination = strtolower($destination);
		$_excludedDestinations = array();

		foreach ($excludedDestinations as $_excludedDestination) {
			//var_dump($_excludedDestination);


			if (is_string($_excludedDestination) !== true) {
				continue;
			}

			$_excludedDestination = trim(strtolower($_excludedDestination));

			if ((strlen($_excludedDestination) > 0) !== true) {
				continue;
			}

			$_excludedDestinations[] = $_excludedDestination;
		}


		foreach ($_excludedDestinations as $_excludedDestination) {

			$_destination = (strtolower(trim($destination)));
			$_excludedDestination = (strtolower(trim($_excludedDestination)));

			// check destination level
			if (Lib_Utils_String::isEmpty($_destination)) {
				throw new Exception(
                    "ACCESS_DENIED"
                        . " (Destination ".$destination." is locked by server "
                        . str_replace("_",".", get_class($this))
                );
			}
			if ($_destination === $_excludedDestination) {
                throw new Exception(
                    "ACCESS_DENIED"
                        . " (Destination ".$destination." is locked by server "
                        . str_replace("_",".", get_class($this))
                );
			}

			$_destinationMeta = $this->_parseDestination($_destination);
			$_destinationExcludedMeta = $this->_parseDestination(
				$_excludedDestination
			);

			// check packageAndClass level
			if ($_destinationMeta["packageAndClass"]
			    !== $_destinationExcludedMeta["packageAndClass"]) {
				continue;
			}

			if ($_destinationExcludedMeta["method"] === "*") {
                throw new Exception(
                    "ACCESS_DENIED"
                        . " (Destination ".$destination." is locked by server "
                        . str_replace("_",".", get_class($this))
                );
			}

			// check package level
			if ($_destinationMeta["package"]
			    !== $_destinationExcludedMeta["package"]) {
				continue;
			}

			if ($_destinationExcludedMeta["class"] === "*") {
                throw new Exception(
                    "ACCESS_DENIED"
                        . " (Destination ".$destination." is locked by server "
                        . str_replace("_",".", get_class($this))
                );
			}
			if ($_destinationExcludedMeta["method"] === "*") {
                throw new Exception(
                    "ACCESS_DENIED"
                        . " (Destination ".$destination." is locked by server "
                        . str_replace("_",".", get_class($this))
                );
			}


		}

	}


	protected function _parseDestination($destination)
	{
		$result = array(
			"destination" => "",
			"package" => "",
			"class" => "",
			"method" => "",
			"packageAndClass" => "",
		);

		$result["destination"] = (string)trim($destination);
		$destinationParts = (array)explode(".", $destination);
		$method = array_pop($destinationParts);
		if (Lib_Utils_String::isEmpty($method)) {
			$method = "";
		}
		$result["method"] = trim($method);

		$packageAndClass = implode(".", $destinationParts);
		if (Lib_Utils_String::isEmpty($packageAndClass)) {
			$packageAndClass = "";
		}
		$result["packageAndClass"] = trim($packageAndClass);

		$class = array_pop($destinationParts);
		if (Lib_Utils_String::isEmpty($class)) {
			$class = "";
		}
		$result["class"] = trim($class);

		$package = array_pop($destinationParts);
		if (Lib_Utils_String::isEmpty($package)) {
			$package = "";
		}
		$result["package"] = trim($package);

		return $result;
	}


	/**
	 *
	 * @param ReflectionClass $reflectionClass
	 * @param string $methodName
	 * @return bool
	 */
	protected function _isServiceMethodInvokeable(
		ReflectionClass $reflectionClass,
		$methodName
	)
	{
		try {

			if (is_string($methodName) !== true) {
				return false;
			}

			if (($reflectionClass->hasMethod($methodName) !== true)) {
				return false;
			}

			$reflectionMethod = $reflectionClass->getMethod($methodName);

			if ($reflectionMethod->isPublic() !== true) {
				return false;
			}
			if ($reflectionMethod->isAbstract() === true) {
				return false;
			}
			if ($reflectionMethod->isStatic() === true) {
				return false;
			}


			if (
				Lib_Utils_String::startsWith(
					$reflectionMethod->getName(),
					"_", true
				)
			) {
				return false;
			}

			return true;
		} catch (Exception $exception) {
			return false;
		}
		return false;
	}

	/**
	 *
	 * @param ReflectionClass $reflectionClass
	 * @param array $requiredInterfaces|null
	 * @return boolean
	 */
	protected function _isServiceClassInstantiable(
		ReflectionClass $reflectionClass,
		$requiredInterfaces
	)
	{
		$result = false;
		try {
			if ($reflectionClass->isAbstract() === true) {
				return false;
			}
			if ($reflectionClass->isInterface() === true) {
				return false;
			}
			if ($reflectionClass->isInternal() === true) {
				return false;
			}
			if ($reflectionClass->isInstantiable() !== true) {
				return false;
			}

			$requiredInterfaces = (array)$requiredInterfaces;


			foreach ($requiredInterfaces as $requiredInterfaceName) {
				$hasInterface = $reflectionClass->implementsInterface(
					$requiredInterfaceName
				);
				if ($hasInterface !== true) {
					return false;
				}
			}


			return true;

		} catch (Exception $exception) {

		}

		return $result;
	}

	/**
	 * @throws Exception
	 * @param ReflectionClass $reflectionClass
	 * @param ReflectionMethod $reflectionMethod
	 * @param array $methodArgs
	 * @param Lib_Io_jsonRpc_Context_Interface $context
	 * @return mixed
	 */
	protected function _invokeArgs(ReflectionClass $reflectionClass,
	                               ReflectionMethod $reflectionMethod,
	                               $methodArgs,
	                               Lib_Io_jsonRpc_ContextInterface $context)
	{
		$methodArgs = (array)$methodArgs;
		$instance = $reflectionClass->newInstance($context);
		$result = $reflectionMethod->invokeArgs($instance, $methodArgs);
		return $result;
	}

	/**
	 * @throws Exception
	 * @param ReflectionMethod $reflectionMethod
	 * @param type $params
	 * @return void
	 */
	protected function _validateServiceClassMethodParams(
		ReflectionMethod $reflectionMethod,
		$params
	)
	{


		$argsGiven = 0;
		$methodArgs = (array)$params;

		if (is_array($methodArgs)) {
			$argsGiven = count($methodArgs);
		}

		$argsExpectedMin = $reflectionMethod->getNumberOfRequiredParameters();
		$argsExpectedMax = $reflectionMethod->getNumberOfParameters();
		$argsOptional = $argsExpectedMax - $argsExpectedMin;

		if ($argsGiven < $argsExpectedMin) {
			$msg = " Wrong number of parameters:";
			$msg .= " " . $argsGiven . " given";
			$msg .= " (required: " . $argsExpectedMin . "";
			$msg .= " optional: " . $argsOptional . ")";

			throw new Exception($msg);
		}

		if ($argsGiven > $argsExpectedMax) {
			$msg = " Wrong number of parameters:";
			$msg .= " " . $argsGiven . " given";
			$msg .= " (required: " . $argsExpectedMin . "";
			$msg .= " optional: " . $argsOptional . ")";

			throw new Exception($msg);
		}
	}


	/**
	 * @throws Exception
	 * @param ReflectionClass $reflectionClass
	 * @params array|null $requiredServiceInterfaces
	 * @return void
	 */
	protected function _validateServiceClass(
		ReflectionClass $reflectionClass,
		$requiredServiceInterfaces
	)
	{
		// reflection: validate service
		if (
			$this->_isServiceClassInstantiable(
				$reflectionClass,
				$requiredServiceInterfaces
			)
			!== true
		) {
			$msg = "Service is not instantiable! className =";
			throw new Exception($msg);
		}


        






	}

	/**
	 * @throws Exception
	 * @param ReflectionClass $reflectionClass
	 * @param string $methodName
	 * @return void
	 */
	protected function _validateServiceClassMethod(
		ReflectionClass $reflectionClass,
		$methodName
	)
	{
		if ($this->_isServiceMethodInvokeable($reflectionClass, $methodName)
		    !== true) {
			$msg = "Method is not invokable! methodName =" . $methodName;
			throw new Exception($msg);
		}
	}


	/**
	 *
	 * @param mixed $result
	 * @return void
	 */
	protected function _onResult($result)
	{
		// log ?
	}

	/**
	 *
	 * @param Exception $exception
	 * @return @void
	 */
	protected function _onError(Exception $exception)
	{
		// log ?
	}


	/**
	 * EXT STYLE
	 * @return array
	 */
	public function getApi()
	{
		$result = array();

		$serviceList = (array)$this->getApiServiceNames();

		foreach ($serviceList as $serviceName) {

			try {
				$serviceInfo = $this->describeService($serviceName);
				$result[$serviceName] = $serviceInfo;

			} catch (Exception $e) {
				//NOP

				//var_dump($e);
			}
		}

		return $result;

	}

	/**
	 * EXT STYLE
	 * @throws Exception
	 * @param string $serviceName
	 * @return array
	 */
	public function describeService($serviceName)
	{
		$result = array(

		);

		$server = $this;
		$className = $server->getClassNamePrefix() . $serviceName;
		//var_dump($className);
		if (Lib_Utils_Class::exists($className) !== true) {
			throw new Exception("Invalid Service-Name");
		}

		$reflectionClass = new ReflectionClass($className);

		if ($server->isServiceClassInstantiable($reflectionClass) !== true) {
			throw new Exception("Invalid Service-Class");
		}


		$reflectionMethods = $reflectionClass->getMethods();
		foreach ($reflectionMethods as $reflectionMethod) {

			$methodName = $reflectionMethod->getName();
			$methodInfo = array(
				"name" => $methodName,
				"params" => array(
				),
			);
			if (
				$server->isServiceMethodInvokeable(
					$reflectionClass,
					$methodName
				) === true
			) {

				$reflectionParams = $reflectionMethod->getParameters();
				foreach ($reflectionParams as $reflectionParameter) {

					$methodInfo["params"][] = array(
						"name" => $reflectionParameter->getName(),
						"position" => $reflectionParameter->getPosition(),
						"isOptional" => $reflectionParameter->isOptional(),
					);
				}

				$result[] = $methodInfo;
			}


		}


		return $result;

	}


	/**
	 * Full exploration
	 * @return array
	 */
	public function exploreApi()
	{
		$result = array();

		$serviceList = (array)$this->getApiServiceNames();

		foreach ($serviceList as $serviceName) {

			try {
				$serviceInfo = $this->exploreService($serviceName);
				$result[$serviceName] = $serviceInfo;

			} catch (Exception $e) {
				//NOP

				//var_dump($e);
			}
		}

		return $result;
	}


	/**
	 * FULL EXPLORE
	 * @throws Exception
	 * @param string $serviceName
	 * @return array
	 */
	public function exploreService($serviceName)
	{
		$result = array(

		);

		$server = $this;
		$className = $server->getClassNamePrefix() . $serviceName;
		//var_dump($className);
		if (Lib_Utils_Class::exists($className) !== true) {
			throw new Exception("Invalid Service-Name");
		}

		$reflectionClass = new ReflectionClass($className);

		if ($server->isServiceClassInstantiable($reflectionClass) !== true) {
			throw new Exception("Invalid Service-Class");
		}


		$reflectionMethods = $reflectionClass->getMethods();
		foreach ($reflectionMethods as $reflectionMethod) {

			$methodName = $reflectionMethod->getName();
			$methodInfo = array(
				"name" => $methodName,
				"params" => array(
				),
				"docComment" => $reflectionMethod->getDocComment(),

			);
			if (is_string($methodInfo["docComment"]) !== true) {
				$methodInfo["docComment"] = "";
			}


			$zrm = new Zend_Reflection_Method(
				$reflectionClass->getName(), $methodName
			);


			try {
				//$methodInfo["content"] = $zrm->getContents(false);
			} catch (Exception $e) {
				//NOP
			}

			try {
				$tags = $zrm->getDocblock()->getTags();
				$methodInfo["tags"] = array();
				foreach ($tags as $tag) {
					//$t = new Zend_Reflection_Docblock_Tag();
					//$t->

					$tag->getName();
					$methodInfo["tags"][] = array(
						"name" => $tag->getName(),
						"description" => $tag->getDescription()
					);
				}
				//$methodInfo["doc"] = $zrm->getDocblock();
			} catch (Exception $e) {
				//NOP
			}

			if (
				$server->isServiceMethodInvokeable(
					$reflectionClass,
					$methodName
				) === true
			) {

				$reflectionParams = $reflectionMethod->getParameters();

				$reflectionParams = $zrm->getParameters();
				/*
foreach($parr as $p) {
	$xp = new Zend_Reflection_Parameter();
	$xp->getType();

}*/


				foreach ($reflectionParams as $reflectionParameter) {

					$item = array(
						"name" => $reflectionParameter->getName(),

						"position" => $reflectionParameter->getPosition(),
						"isOptional" => $reflectionParameter->isOptional(),
						"isDefaultValueAvailable" => $reflectionParameter->isDefaultValueAvailable(),
						"allowsNull" => $reflectionParameter->allowsNull(),
						//"defaultValue" => $reflectionParameter->getDefaultValue(),
						//"isArray" =>$reflectionParameter->isArray(),
					);
					if ($reflectionParameter->isDefaultValueAvailable()) {
						$item["defaultValue"] = $reflectionParameter->getDefaultValue();
					}


					try {
						$item["type"] = $reflectionParameter->getType();
					} catch (Exception $e) {
						$item["type"] = null;
					}


					$methodInfo["params"][] = $item;
				}

				$result[] = $methodInfo;
			}

		}


		return $result;

	}


}
