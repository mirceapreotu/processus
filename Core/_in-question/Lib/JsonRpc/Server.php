<?php

/**
 * Lib_JsonRpc_Server
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
 * Lib_JsonRpc_Server
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

class Lib_JsonRpc_Server
{

     /**
     * @return string
     */
    public function getJsonRpcPrefix()
    {
        return "App_JsonRpc";
    }

    /**
     * @var Zend_Reflection_Class
     */
    protected $_serviceReflectionClass;
    /**
     * @var Zend_Reflection_Method
     */
    protected $_serviceReflectionMethod;

    /**
     * @var Lib_JsonRpc_Gateway
     */
    protected $_gateway;

    /**
	 *
	 * @var Lib_JsonRpc_Request
	 */
	protected $_request;
	/**
	 *
	 * @var Lib_JsonRpc_Response
	 */
	protected $_response;

    /**
	 *
	 * @var Lib_JsonRpc_ContextInterface
	 */
	protected $_context;

    /**
     * @var Lib_JsonRpc_Logger
     */
    protected $_logger;

    /**
     * @var string
     */
    protected $_destination;
    /**
     * @var array|null
     */
    protected $_destinationParsed;


    /**
     * @var string|null
     */
    protected $_serviceClassMethodQualifiedName;

    /**
      * @var Lib_JsonRpc_CommandHandler
      */
     protected $_commandHandler;


    /**
     * @var int
     */
    protected $_apiVersion;

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
        "apiVersion" => array(
            "default" => 1,
        ),

        "crypt" => array(
           
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


        "context" => array(
            "class" => "Lib_JsonRpc_Context"
            //"class" => "App_JsonRpc_V1_Cms_Context",
        ),

        "classes" => array(
            // NO WILDCARDS HERE! SINCE WE NEED ServiceNames FOR describe Api
            // e.g.: App_JsonRpc_V1_Cms_Service_Auth
        ),
        
        "destination" => array(
            "whitelist" => array(
                //e.g. "*"
            ),
            "blacklist" => array(
            ),
        ),

        "command" => array(
            "whitelist" => array(
                //e.g. "describeApi"
            ),
            "blacklist" => array(
                "getServer",
                "setServer",
            ),
        ),


        "interfaces" => array(
            "Lib_JsonRpc_ServiceInterface",
        ),

        "methodQualifiedName" => array(
            "whitelist" => array(
                //e.g. "*"
            ),
            "blacklist" => array(
                    //eg.: "*::getContext";
            ),
        )


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


    // ++++++++++++++++++++++++ config.enabled ++++++++++++++++++++++++++

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return Bootstrap::getRegistry()->isDebugMode();
    }

    /**
     * @return bool
     */
    public function isDeveloper()
    {
        return Bootstrap::getRegistry()->getDebug()->isDeveloper();
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



    // ++++++++++++++++++++++++ config.destination +++++++++++++++++++++


    /**
     * @param  string $destination
     * @return bool
     */
    public function isDestinationWhitelisted($destination)
    {

        $target = $destination;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->destination;
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
     * @param  string $destination
     * @return bool
     */
    public function isDestinationBlacklisted($destination)
    {

        $target = $destination;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->destination;
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
     * @param  string $destination
     * @return bool
     */
    public function isDestinationAllowed($destination)
    {
        $isWhiteListed = $this->isDestinationWhitelisted($destination);
        $isBlacklisted = $this->isDestinationBlacklisted($destination);

        return (bool)(($isWhiteListed === true) && ($isBlacklisted !== true));
    }






    // ++++++++++++++++++++++++ config.command +++++++++++++++++++++



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


    // ++++++++++++++++ config.classes +++++++++++++++++++++++


    /**
     * @return array
     */
    public function getServiceClasses()
    {
        $result = array();
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->classes;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        $result = $config->toArray();
        return $result;
    }


    public function isServiceClassAvailable($className)
    {
        $result = false;
        $className = trim(strtolower($className));
        if (strlen($className)<1) {
            return $result;
        }
        $list = $this->getServiceClasses();
        foreach($list as $pattern) {

            $pattern = trim(strtolower($pattern));
            if (strlen($pattern)<1) {
                continue;
            }

            if ($pattern === $className) {
                return true;
            }
        }
        return $result;
   
    }


    // ++++++++++++++++ config.methodQualifiedName +++++++++++



    /**
     * @param  string $qname
     * @return bool
     */
    public function isMethodQualifiedNameWhitelisted($qname)
    {

        $target = $qname;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->methodQualifiedName;
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
     * @param  string $qname
     * @return bool
     */
    public function isMethodQualifiedNameBlacklisted($qname)
    {

        $target = $qname;

        $result = false;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->methodQualifiedName;
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
     * @param  string $qname
     * @return bool
     */
    public function isMethodQualifiedNameAllowed($qname)
    {
        $isWhiteListed = $this->isMethodQualifiedNameWhitelisted($qname);
        $isBlacklisted = $this->isMethodQualifiedNameBlacklisted($qname);

        return (bool)(($isWhiteListed === true) && ($isBlacklisted !== true));
    }


    // +++++++++++++++++++ config.interfaces +++++++++++++++++++++++

    /**
     *
     * @return array
     */
    public function getRequiredInterfaceNames()
    {


        $result = array();
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->interfaces;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }
        return $config->toArray();        
    }


    // ++++++++++++++++++++++ config.apiVersion +++++++++++++++++++++++++

     /**
     * @param  
     * @return int
     */
    public function getApiVersionDefault()
    {

        $result = 1;
        /**
         * @var Zend_Config $config
         */
        $config = $this->getConfig()->apiVersion;
        if (($config instanceof Zend_Config)!== true) {
            return $result;
        }

        $defaultVersion = (int)$config->default;
        if ($defaultVersion<1) {
            return $result;
        }
        return $defaultVersion;
        
    }

    /**
     * @return void
     */
    public function getApiVersionPreferred()
    {
        $value = (int)$this->getGateway()->getApiVersion();
        return $value;
    }


    
    /**
     * @return int
     */
    public function getApiVersion()
    {
        $apiVersion = $this->_apiVersion;
        if (is_int($apiVersion)) {
            return $apiVersion;
        }


        $apiVersionDefault = (int)$this->getApiVersionDefault();
        $apiVersionPreferred = (int)$this->getApiVersionPreferred();
        if ($apiVersionPreferred<$apiVersionDefault) {
            $this->_apiVersion = (int)$apiVersionDefault;
        } else {
            $this->_apiVersion = (int)$apiVersionPreferred;
        }

        return $this->_apiVersion;

    }


  // ++++++++++++++++++++++ gateway +++++++++++++++++++++++++++++++++++


    /**
     * @param Lib_JsonRpc_Gateway $gateway
     * @return void
     */
    public function setGateway(Lib_JsonRpc_Gateway $gateway)
    {
        $this->_gateway = $gateway;
    }

    /**
     * @return Lib_JsonRpc_Gateway
     */
    public function getGateway()
    {
        return $this->_gateway;
    }


    // ++++++++++++++++++++++++ context ++++++++++++++++++++++++++

    /**
	 *
	 * @return Lib_JsonRpc_ContextInterface
	 */
	public function getContext()
	{
		return $this->_context;
	}

    /**
	 *
	 * @param Lib_JsonRpc_ContextInterface $context
	 */
	public function setContext(Lib_JsonRpc_ContextInterface $context)
	{
		$this->_context = $context;
	}

    /**
     * @throws Exception|Lib_Application_Exception
     * @return Lib_JsonRpc_Context
     */
     public function newContext()
     {
         $class = null;
         $config = $this->getConfig()->context;
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
              * @var Lib_JsonRpc_Context $instance
              */
             $instance = new $class();
             if (($instance instanceof Lib_JsonRpc_ContextInterface) !== true) {
                 throw new Exception(
                     "class name must be instanceof"
                     . " Lib_JsonRpc_ContextInterface!"
                 );
             }


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
             throw $e;
         }
     }


    // +++++++++++++++++++++++++ logging +++++++++++++++++++++++++


    /**
     *
     * @return Lib_JsonRpc_Logger $result
     */
    public function getLogger()
    {
        if (($this->_logger instanceof Lib_JsonRpc_Logger) !== true) {
            $this->_logger = $this->newLogger();
        }
        return $this->_logger;
    }

    /**
     *
     * @return Lib_JsonRpc_Logger $result
     */
    public function newLogger()
    {
        $result = new Lib_JsonRpc_Logger();
        return $result;
    }


    // ++++++++++++++++ request ++++++++++++++++++++++++++++++

    /**
     *
     * @return Lib_JsonRpc_Request
     */
    public function getRequest()
    {
        if (($this->_request instanceof Lib_JsonRpc_Request) !== true) {
            $this->_request = $this->newRequest();
        }

        return $this->_request;
    }

     /**
     *
     * @return Lib_JsonRpc_Request
     */
    public function newRequest()
    {
        return new Lib_JsonRpc_Request();
    }
    // ++++++++++++++++ response ++++++++++++++++++++++++++++++

    /**
     *
     * @return Lib_JsonRpc_Response
     */
    public function getResponse()
    {
        if (($this->_response instanceof Lib_JsonRpc_Response) !== true) {
            $this->_response = $this->newResponse();
        }

        return $this->_response;
    }

    /**
     *
     * @return Lib_JsonRpc_Response
     */
    public function newResponse()
    {
        return new Lib_JsonRpc_Response();
    }



    // +++++++++++++++++++++++ reflection +++++++++++++++++++++++++++

    /**
     * @return Zend_Reflection_Class
     */
    public function getServiceReflectionClass()
    {
        return $this->_serviceReflectionClass;
    }

    /**
     * @return Zend_Reflection_Method
     */
    public function getServiceReflectionMethod()
    {
        return $this->_serviceReflectionMethod;
    }


    /**
    * @param  array $destinationParsed
    * @param int|null $version
    * @return string
    */
   public function findClassNameByDestinationParsed(
       $destinationParsed,
       $version = null
   )
   {

       return $this->_findClassNameByDestinationParsed(
           $destinationParsed,
           $version
       );
   }

    /**
     * @param  string $qname
     * @return null|string
     */
    public function findDestinationByMethodQualifiedName($qname)
    {
        return $this->_getDestinationByMethodQualifiedName($qname);
    }
     /**
     * @param  int $apiVersion
     * @return array
     */
    public function exploreServiceDestinations($apiVersion)
    {
        $apiVersion = (int)$apiVersion;
        if ($apiVersion<1) {
            $apiVersion = $this->getApiVersion();
        }
        $result = array(
            "apiVersion" => $apiVersion,
            "server" => str_replace("_",".",get_class($this)),
            "gateway" => str_replace("_",".",get_class($this->getGateway())),
            "methodQualifiedName" => array(),
            "destination" => array(),
        );

        $services = $this->getServiceClasses();
        foreach($services as $service) {

            // classes
            if (class_exists($service) !== true) {
                continue;
            }
            $serviceReflectionClass = new Zend_Reflection_Class($service);
            $serviceInterfaces = $this->getRequiredInterfaceNames();
            try {
                $this->_validateServiceClass(
                $serviceReflectionClass,
                $serviceInterfaces
                );
            }catch(Exception $e) {
                continue;
            }


            // methods
            foreach($serviceReflectionClass->getMethods() as $reflectionMethod) {

                try {
                    $this->_validateServiceClassMethod(
                        $serviceReflectionClass,
                        $reflectionMethod
                    );

                    $result["methodQualifiedName"][] =
                            $serviceReflectionClass->getName()
                                . "::"
                                . $reflectionMethod->getName();

                } catch(Exception $e) {
                    continue;
                }

            }

        }


        foreach($result["methodQualifiedName"] as $methodQualifiedName)
        {

            $destination = $this->_getDestinationByMethodQualifiedName(
                $methodQualifiedName
            );
            try {
                $this->_initDispatcher($destination, $apiVersion, true);
                $result["destination"][]= $destination;
            }catch(Exception $e) {
                //nop
            }
        }
        $result["destination"] = (array)array_unique($result["destination"]);

        //echo json_encode($result);exit;
        return $result;

    }




    /**
     * @param  int $apiVersion
     * @return array
     */
    public function exploreServiceDestination($destination, $apiVersion)
    {
        $apiVersion = (int)$apiVersion;
        if ($apiVersion<1) {
            $apiVersion = $this->getApiVersion();
        }
        $result = array(
            "apiVersion" => $apiVersion,
            "server" => str_replace("_",".",get_class($this)),
            "gateway" => str_replace("_",".",get_class($this->getGateway())),
            "methodQualifiedName" => array(),
            "destination" => array(),
        );

        $destinationParsed = $this->parseDestination($destination);
        $destination = $destinationParsed["destination"];

        $classname = $this->_findClassNameByDestinationParsed(
            $destinationParsed
        );
        /*
var_dump($classname);
        $methodQualifiedName = $classname."::".$destinationParsed["methodName"];

var_dump($methodQualifiedName);
        */
/*
        $destination = $this->_getDestinationByMethodQualifiedName(
                $methodQualifiedName
        );
*/
//var_dump($destination);
        try {
            $this->_initDispatcher($destination, $apiVersion, true);
            $result["destination"][]= $destination;
        }catch(Exception $e) {
            //nop
        }

//var_dump($result);
        return $result;

    }




    // +++++++++++++++++ destination +++++++++++++++++++++++++++++++

   

    /**
     * @param  string $destination
     * @return void
     */
    public function setDestination($destination)
    {
        $this->_destination = $destination;
        $this->_destinationParsed = $this->_parseDestination($destination);
        $this->_destination = $this->_destinationParsed["destination"];
    }


    /**
     * @param  $destination
     * @return array|null
     */
    public function parseDestination($destination)
    {
        return $this->_parseDestination($destination);
    }


    /**
     * @param  $destination
     * @return array|null
     */
    protected function _parseDestination($destination)
    {
        $filter = new Lib_JsonRpc_Filter_Destination();
        $destination = $filter->sanitize($destination);
        return $filter->getParsedDestination();
    }


    /**
     * @return array|null
     */
    public function getDestinationParsed()
    {
        return $this->_destinationParsed;
    }

    /**
     * @return string|null
     */
    public function getDestination()
    {
        $destinationParsed = $this->getDestinationParsed();
        return Lib_Utils_Array::getProperty($destinationParsed, "destination");
    }




    // ++++++++++++++++++++++++ methodQualifiedName ++++++++++++++++++++

    /**
     * @return null|string
     */
    public function getServiceClassMethodQualifiedName()
    {
        return $this->_serviceClassMethodQualifiedName;
    }



    // +++++++++++++++++++++++ commands ++++++++++++++++++++++ //


 
    /**
     * @return Lib_JsonRpc_CommandHandler
     */
    public function getCommandHandler()
    {
        if (($this->_commandHandler instanceof Lib_JsonRpc_CommandHandler)!==true) {
            $this->_commandHandler = new Lib_JsonRpc_CommandHandler();
            $this->_commandHandler->setServer($this);
        }
        return $this->_commandHandler;
    }


    /**
     * @throws Lib_Application_Exception
     * @param  array $command
     * @return mixed|null
     */
    public function executeCommand($command)
    {
        $commandHandler = $this->getCommandHandler();
        $fault = array(
                         "command" => $command,
                         "server" => str_replace(
                             "_" ,".", get_class($this)
                         ),
                         "gateway" => str_replace(
                                 "_" ,".", get_class($this->getGateway())
                             ),
                         "commandHandler" => str_replace(
                             "_" ,".", get_class($commandHandler)
                         ),
                     );


        
        $error = new Lib_Application_Exception("Invalid command data");
        $error->setMethodLine(__LINE__);
        $error->setMethod(__METHOD__);
        $error->setMethodLine(__LINE__);
        $error->setFault($fault);

        try {
            if (is_array($command)!==true) {
                throw new Exception("Invalid command data");
            }

            $method = trim(Lib_Utils_Array::getProperty($command, "method"));
            if (strlen($method)<1) {
                throw new Exception("Invalid command data (method)");
            }
            $params = Lib_Utils_Array::getProperty($command, "params");
            if (is_array($params)!==true) {
                $params = array();
            }


            $methodQualifiedName = get_class($commandHandler)."::".$method;
            $filter = new Lib_JsonRpc_Filter_ClassMethod();
            try {
                $methodQualifiedName = $filter->sanitize($methodQualifiedName);
            } catch(Exception $exception) {
                $fault["message"] = $exception->getMessage();
                throw new Exception(
                    "Invalid command data (method qualifiedname is invalid)"
                );
            }

            $parts = explode("::", $methodQualifiedName);
            $_method = array_pop($parts);
            if (strtolower($method) !== strtolower($_method)) {
                $fault["_method"] = $_method;
                throw new Exception(
                    "Invalid command data (method name is invalid)"
                );
            }


            if ($this->isCommandAllowed($method)!==true) {
                throw new Exception(
                    "Invalid command data (method not allowed by server)"
                );
            }

            if ($this->getGateway()->isCommandAllowed($method)!==true) {
                throw new Exception(
                    "Invalid command data (method not allowed by gateway)"
                );
            }



            $reflectionClass = new Zend_Reflection_Class($commandHandler);
            if ($reflectionClass->hasMethod($method) !== true) {
                throw new Exception(
                    "Invalid command data (method does not exist)"
                );
            }


            $reflectionMethod = $reflectionClass->getMethod($method);

            // check method is invokable
            if (strpos($method, "_") !== FALSE) {
                throw new Exception(
                    "Invalid command data (method does not invokable) "
                    . __LINE__
                );
            }
            if ($reflectionMethod->isPublic() !== true) {
                throw new Exception(
                    "Invalid command data (method does not invokable) "
                    . __LINE__
                );              }
            if ($reflectionMethod->isAbstract() === true) {
                throw new Exception(
                    "Invalid command data (method does not invokable) "
                    . __LINE__
                );
            }
            if ($reflectionMethod->isStatic() === true) {
                throw new Exception(
                    "Invalid command data (method does not invokable) "
                    . __LINE__
                );
            }

        } catch(Exception $exception) {


            $error = new Lib_Application_Exception($exception->getMessage());
            $error->setFault($fault);
            $error->setMethod(__METHOD__);
           
            throw $error;
        }

        $result = $reflectionMethod->invokeArgs($commandHandler, $params);
        return $result;

    }





    // +++++++++++++++++++++++++ run +++++++++++++++++++++++++++++++++


    /**
     *
     *
     * @return void
     */
    public function run()
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



            // dispatcher
            $destination = (string)$this->getRequest()->getMethod();
            $apiVersion = $this->getApiVersion();
            $this->_initDispatcher($destination, $apiVersion, false);


            // check for crypt requirements
            $this->_validateCurrentRequestCryptionRequirements();


            // dispatch: validate method args
            $reflectionClass = $this->getServiceReflectionClass();
            $reflectionMethod = $this->getServiceReflectionMethod();
            $serviceInstanceMethodArgs = $this->getRequest()->getParams();
            /** noinspection PhpParamsInspection */
            $this->_validateServiceClassMethodParams(
                $reflectionClass,
                $reflectionMethod,
                $serviceInstanceMethodArgs
            );

            // context
            // use context
            $context = $this->newContext();
            $this->setContext($context);
            $this->getContext()->setServer($this);
            $this->_onBeforePrepareContext();
            $this->getContext()->prepare();

            // invoke
            $this->_onBeforeInvoke();
            $serviceInstance = $reflectionClass->newInstance($context);
            /** @noinspection PhpParamsInspection */
            $serviceCallResult = $this->_invokeArgs(
                $reflectionClass,
                $reflectionMethod,
                $serviceInstanceMethodArgs,
                $context
            );


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


    // +++++++++++++++++++++ events ++++++++++++++++++++++++++++++

    


    /**
     *
     * @param mixed $result
     * @return void
     */
    protected function _onResult($result)
    {
        // log ?
        $this->getContext()->onResult($result);
    }

    /**
     *
     * @param Exception $exception
     * @return @void
     */
    protected function _onError(Exception $exception)
    {
        // log ?
        if ($this->getContext() instanceof Lib_JsonRpc_ContextInterface) {
            $this->getContext()->onError($exception);
        }

    }



    /**
    * @return void
    */
    protected function _onBeforePrepareContext()
    {

    }

    /**
    * @return void
    */
    protected function _onBeforeInvoke()
    {

    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    /**
	 * @throws Exception
	 * @param string $destination
	 * @return void
	 *
	 */
	protected function _validateDestination($destination)
	{
        //sanitize destination
        $filter = new Lib_JsonRpc_Filter_Destination();
        $destination = $filter->sanitize($destination);
            
        //var_dump($filter->getParsedDestination()); exit;

        // (1) check server.config.destinations

        if ($this->isDestinationAllowed($destination) !== true) {
            $e = new Lib_Application_Exception(
                "Invalid destination not supported by server"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    "destination" => $destination,
                    "server" => get_class($this),
                         ));
            throw $e;
        }

        // (2) check server->gateway.config.destinations

        if ($this->getGateway()->isDestinationAllowed($destination) !== true) {
            $e = new Lib_Application_Exception(
                "Invalid destination not supported by gateway"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    "destination" => $destination,
                    "server" => get_class($this),
                    "gateway" => get_class($this->getGateway()),
                         ));
            throw $e;
        }

    }


/**
     * @throws Lib_Application_Exception
     * @return void
     */
    protected function _validateCurrentRequestCryptionRequirements()
    {
        if ($this->getGateway()->isRequestCrypted() === true) {
            // request is crypted, everything is fine
            return;
        }

        if ($this->getGateway()->isRequestMocked() === true) {
            // request is mocked, do we ignore the crypt requirements?
            $ignoreRequirements =
                    ($this->getGateway()
                            ->isRequestMockIgnoreCryptRequirements() === true);

            if ($ignoreRequirements === true) {

                return;
            }

        }

        if ($this->isDebugMode() === true) {

            // we are in debugMode, ignore crypt requirements?
            return;
        }


        // the default behaviour ...


        $destination = $this->getDestination();

        // (1) check by server.config

        $isCryptRequiredByServer = (bool)$this->isCryptDestinationRequired(
            $destination
        );
        $isCryptRequiredByGateway = (bool)$this->getGateway()
                    ->isCryptDestinationRequired($destination);

        

        $isCryptRequired = (bool)(
                $isCryptRequiredByServer
                ||
                $isCryptRequiredByGateway
        );


        if ($isCryptRequired !== true) {
            return;
        }

        $error = new Lib_Application_Exception(
            "Destination requires crypted transport"
        );

        $message = "blocked by ";
        if ($isCryptRequiredByGateway) {
            $message .= " gateway";
        }
        if ($isCryptRequiredByServer) {
            $message .= " server";
        }
        $error->setMethod(__METHOD__);
        $error->setMethodLine(__LINE__);
        $error->setFault(array(
                          "message"=>$message,
                          "destination" => $destination,
                          "server" => get_class($this),
                          "gateway" => get_class($this->getGateway()),
                          "cryptRequiredByServer" => $isCryptRequiredByServer,
                          "cryptRequiredByGateway" => $isCryptRequiredByGateway,
                         ));
        throw $error;
    }




    /**
     * @throws Lib_Application_Exception
     * @param ReflectionClass $reflectionClass
     * @param  array $requiredInterfaceNames
     * @return void
     */
    protected function _validateServiceClass(
        ReflectionClass $reflectionClass,
        $requiredInterfaceNames
    )
	{

        $className = $reflectionClass->getName();

        if ($this->isServiceClassAvailable($className) !== true) {
            $e = new Lib_Application_Exception(
                "class is not available by server"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    //"destinationParsed" => $destinationParsed,
                    "className" => $className,
                    //"methodName" => $methodName
                         ));
            throw $e;
        }


        
        $filter = new Lib_JsonRpc_Filter_Service();
        $filter->setRequiredInterfaceNames($requiredInterfaceNames);

        $isInstantiable = $filter->isInstantiable($reflectionClass);
        if ($isInstantiable !== true) {
            $e = new Lib_Application_Exception(
                    "class is not instantiable"
                );
                $e->setMethod(__METHOD__);
                $e->setFault(array(
                        //"destinationParsed" => $destinationParsed,
                        "className" => $className,
                        //"methodName" => $methodName
                             ));
				throw $e;
        }

    }

    /**
     * @throws Lib_Application_Exception
     * @param ReflectionClass $reflectionClass
     * @param ReflectionMethod $reflectionMethod
     * @return void
     */
    protected function _validateServiceClassMethod(
        ReflectionClass $reflectionClass,
        ReflectionMethod $reflectionMethod
    )
    {

        $className = $reflectionClass->getName();
        $methodName = $reflectionMethod->getName();

        
        $filter = new Lib_JsonRpc_Filter_Service();
        $isValid = $filter->isReflectionMethodInvokeable($reflectionMethod);

        if ($isValid !== true) {
            $e = new Lib_Application_Exception(
                    "method is not invokable"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    //"destinationParsed" => $destinationParsed,
                    "className" => $className,
                    "methodName" => $methodName
                         ));
            throw $e;
        }


        $qname = $className."::".$methodName;


        $isAllowed = $this->isMethodQualifiedNameAllowed($qname);
        if ($isAllowed !== true) {
            $e = new Lib_Application_Exception(
                    "method is not allowed"
                );
                $e->setMethod(__METHOD__);
                $e->setFault(array(
                        //"destinationParsed" => $destinationParsed,
                        "className" => $className,
                        "methodName" => $methodName,
                        "methodQName" => $qname,
                        "methodIsWhitelisted" =>
                            $this->isMethodQualifiedNameWhitelisted($qname),
                        "methodIsBlacklisted" =>
                            $this->isMethodQualifiedNameBlacklisted($qname),
                             ));
				throw $e;
        }



    }




    /**
     * @throws Lib_Application_Exception
     * @param  $destination
     * @param  $apiVersion
     * @param bool $dryRun
     * @return void
     */
    protected function _initDispatcher($destination, $apiVersion, $dryRun=false)
    {

        // check server is enabled
        if ($this->isEnabled() !== true) {
            $e = new Lib_Application_Exception(
                "server is not enabled"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    "server" => get_class($this),
                         ));
            throw $e;

        }

        // dispatch: validate destination

        if ($dryRun !== true) {
            $this->setDestination($destination);
            $destination = $this->getDestination();
            $this->_validateDestination($destination);

            $destinationParsed = $this->getDestinationParsed();
            $className = $destinationParsed["classQualifiedName"];
            $methodName = $destinationParsed["methodName"];
        } else {
            $destinationParsed = $this->_parseDestination($destination);
            $destination = $destinationParsed["destination"];
            $this->_validateDestination($destination);
            $className = $destinationParsed["classQualifiedName"];
            $methodName = $destinationParsed["methodName"];
        }




        // dispatch: validate class


        $className = $this->_findClassNameByDestinationParsed(
            $destinationParsed,
            $apiVersion
        );



        if (Lib_Utils_Class::exists($className) !== true) {
            $e = new Lib_Application_Exception(
                "Invalid className does not exists for destination"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    "server" => get_class($this),
                    "destinationParsed" => $destinationParsed,
                    "className" => $className,
                    "methodName" => $methodName,
                    "apiVersion" => $apiVersion,
                         ));
            throw $e;
        }

        $reflectionClass = new Zend_Reflection_Class($className);
        if ($dryRun !== true) {
            $this->_serviceReflectionClass = $reflectionClass;
            $reflectionClass = $this->getServiceReflectionClass();
        }

        $this->_validateServiceClass(
            $reflectionClass,
            $this->getRequiredInterfaceNames()
        );


        // dispatch: validate method
        if ($reflectionClass->hasMethod($methodName) !== true) {
            $e = new Lib_Application_Exception(
                "Invalid methodName"
            );
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                    "server" => get_class($this),
                    "apiVersion" => $apiVersion,
                    "destinationParsed" => $destinationParsed,
                    "className" => $className,
                    "methodName" => $methodName
                         ));
            throw $e;
        }

        $reflectionMethod = $reflectionClass->getMethod($methodName);
        if ($dryRun !== true) {
            $this->_serviceReflectionMethod = $reflectionMethod;
            $reflectionMethod = $this->getServiceReflectionMethod();
        }

        $this->_validateServiceClassMethod(
            $reflectionClass,
            $reflectionMethod
        );


        $this->_serviceClassMethodQualifiedName =
                    $reflectionClass->getName()
                   ."::".
                   $reflectionMethod->getName();



    }




	/**
	 * @throws Exception
	 * @param ReflectionClass $reflectionClass
	 * @param ReflectionMethod $reflectionMethod
	 * @param array $methodArgs
	 * @param Lib_JsonRpc_Context_Interface $context
	 * @return mixed
	 */
	protected function _invokeArgs(ReflectionClass $reflectionClass,
	                               ReflectionMethod $reflectionMethod,
	                               $methodArgs,
	                               Lib_JsonRpc_ContextInterface $context)
	{
		$methodArgs = (array)$methodArgs;
		$instance = $reflectionClass->newInstance($context);
		$result = $reflectionMethod->invokeArgs($instance, $methodArgs);
		return $result;
	}

	/**
	 * @throws Exception
     * @param ReflectionClass $reflectionClass
	 * @param ReflectionMethod $reflectionMethod
	 * @param type $params
	 * @return void
	 */
	protected function _validateServiceClassMethodParams(
        ReflectionClass $reflectionClass,
		ReflectionMethod $reflectionMethod,
		$params
	)
	{

        $className = $reflectionClass->getName();
        $methodName = $reflectionMethod->getName();

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

            $e = new Lib_Application_Exception($msg);
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                "className" => $className,
                "methodName" => $methodName,
                         ));
            throw $e;

		}

		if ($argsGiven > $argsExpectedMax) {
			$msg = " Wrong number of parameters:";
			$msg .= " " . $argsGiven . " given";
			$msg .= " (required: " . $argsExpectedMin . "";
			$msg .= " optional: " . $argsOptional . ")";

            $e = new Lib_Application_Exception($msg);
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                "className" => $className,
                "methodName" => $methodName,
                         ));
            throw $e;
		}
	}



 


    /**
     * @param  array $destinationParsed
     * @param int|null $version
     * @return string
     */
    protected function _findClassNameByDestinationParsed(
        $destinationParsed,
        $version = null
    )
    {
        // translate e.g.: Cms.Auth to App_JsonRpc_V1_Cms_Service_Auth

        $prefix = $this->getJsonRpcPrefix(); "App_JsonRpc";

        $rootPackageName = $destinationParsed["rootPackageName"];
        $subPackageName = $destinationParsed["subPackageName"];
        $className = $destinationParsed["className"];


        $version = (int)$version;
        $versionString = null;
        if ($version>0) {
            $versionString = "V".$version;
        }

        $parts = array(
            $prefix,
            //$versionString,
            //$rootPackageName,
            //"Service",
            //$subPackageName,
            //$className,
        );

        if (Lib_Utils_String::isEmpty($versionString)!==true) {
            $parts[] = $versionString;
        }
        if (Lib_Utils_String::isEmpty($rootPackageName)!==true) {
            $parts[] = $rootPackageName;
        }

        $parts[] = "Service";

        if (Lib_Utils_String::isEmpty($subPackageName)!==true) {
            $parts[] = $subPackageName;
        }
        if (Lib_Utils_String::isEmpty($className)!==true) {
            $parts[] = $className;
        }



        $result = implode(".",$parts);
        $result = str_replace(
            array("."),
            array("_"),
            $result
        );

        return $result;
    }



    /**
     * @param  string $qname
     * @return null|string
     */
    protected function _getDestinationByMethodQualifiedName($qname)
    {

       // App_JsonRpc_V1_Cms_Service_Auth::blabulb1   -> to Cms.Auth
        $destination = $qname;


        $prefix = $this->getJsonRpcPrefix(); //"App_JsonRpc_";
        if (Lib_Utils_String::isEmpty($prefix)!==true) {
            $prefix .= "_";
            $destination = Lib_Utils_String::removePrefix(
                $destination,
                $prefix,
                true
            );
        }


        $method = Lib_Utils_String::getPostfixByDelimiter($destination, "::");
        $destination = Lib_Utils_String::removePostfixByDelimiterIfExists($destination, "::");
        //return $method;
        $parts = (array)explode("_", $destination);

        //version
        $versionString = array_shift($parts);


        // "Cms"
        $namespace = array_shift($parts);
        // "Service"
        $delete = array_shift($parts);

        $destination = $namespace.".".implode(".", $parts).".".$method;
        return $destination;
        
    }

















}
