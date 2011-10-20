<?php
/**
 * Lib_Session_AbstractSession
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Session
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Session
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Session_SaveHandler
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class Lib_Session_AbstractSession
{

   

    const NAMESPACE_PREFIX = null; //e.g."VzSession"
    // if null, we use the classname for prefixing;


    const NAMESPACE_NAME_DEFAULT = "Default";
    //const NAMESPACE_NAME_FOO ="Foo";


    const SAVEHANDLER_TYPE_NONE = "NONE";
    const SAVEHANDLER_TYPE_MEMCACHED = "MEMCACHED";



    /**
     * @var array
     */
    protected $_namespacesDict = array();

     /**
     * @var Zend_Config
     */
    protected $_config;

    /**
     * @var array
     */
    protected $_options;


    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(
        "sessionNamePrefix" => null,
        "options" => array(
            //'cookie_secure'   => true, // only for https
            'name'              => null, //auto
            'cookie_httponly'   => true,
            //'gc_maxlifetime'  => 60*60,
        ),
        "saveHandler" => array(
            "type" => null,
            "backendOptions" => array(
                'servers' => array(
                    array(
                        'host' => '127.0.0.1',
                        'port' => 11211
                    ),
                ),
                'compression' => false,
            ),
             "frontendOptions" => array(
                'caching' => true,
                'lifetime' => 1800,
                'automatic_serialization' => true,

                //   pre-pended to they id (index)
                //   you choose for each cached item.
                //'cache_id_prefix' => 'myApp',
                //'logging' => true,
                //'logger'  => $oCacheLog,

                //   this performs a consistency check
                // whenever data is written to cache.
                'write_control' => true,

                // If this is set, ignore_user_abort will be set to true
                // while cache is being written. This helps prevent data corruption.
                'ignore_user_abort' => true,
            ),
        ),

    );


    /**
     * @return array
     */
    public function getNamespaceNamesAvailable()
    {
        return array(
            self::NAMESPACE_NAME_DEFAULT,
            //self::NAMESPACE_NAME_FOO,
        );
    }





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




     /**
      * @return array
      */
    public function getOptions()
    {

        if (is_array($this->_options)) {
            return $this->_options;
        }


        $config = $this->getConfig();

        $sessionName = Bootstrap::getRegistry()->getApplicationPrefix();

        $modulePrefix = $config->sessionNamePrefix;

        if (is_string($modulePrefix) !== true) {
            $modulePrefix = strtolower(get_class($this));
        }

        $sessionName .= "__".$modulePrefix;

        $source = new Zend_Config( array(
            //'cookie_secure'   => true, // only for https
            'name'              => $sessionName,
            'cookie_httponly'   => true,
            //'gc_maxlifetime'  => 60*60,
        ), true);

        $mixin = $config->options;

        $fieldlist = array(
             'cookie_secure',
            //'name', -> protect from being override
            'cookie_httponly',
            'gc_maxlifetime',
        );

        $newConfig = Lib_Utils_Config_Parser::toZendConfigAndMixinFields(
            $source,
            $mixin,
            $fieldlist
        );


        $this->_options = $newConfig->toArray();

        return $this->_options;
    }



    /**
     * @return Zend_Config
     */
    public function getSaveHandlerConfig()
    {

        $config = $this->getConfig();
        $result = $config->saveHandler;
        if (($result instanceof Zend_Config)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }

        return $config->saveHandler;
    }





    /**
     * @return void
     */
    public function applyOptions()
    {
        $options = $this->getOptions();
        $this->zendSetOptions($options);
    }


    /**
     * @param  $name
     * @return string
     */
    public function newNamespacePrefixedName($name)
    {
        $result = Bootstrap::getRegistry()->getApplicationPrefix();

        $modulePrefix = self::NAMESPACE_PREFIX;
        if (
                (is_string($modulePrefix))
            ) {

            // NOP
        } else {
            $modulePrefix = strtolower(get_class($this));
        }

        $result .= "__MODULEPREFIX__".$modulePrefix;
        $result .= "__NAME__".$name;
        return $result;
    }

    /**
     * @return null|string
     */
    public function getSaveHandlerType()
    {
        //var_dump($this->getSaveHandlerConfig()->type);
        return $this->getSaveHandlerConfig()->type;
    }

    /**
     * @throws Lib_Application_Exception
     * @param  $namespaceName
     * @return array
     */
    public function export($namespaceName)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($namespaceName, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $namespaceName
                             ));
            throw $error;
        }
        $result = array();

        $zendNamespace = $this->getNamespace($namespaceName);
        foreach ($zendNamespace->getIterator() as $key => $value) {
            $result[$key] = $value;
        }
        return $result;

    }

    /**
     * @throws Lib_Application_Exception
     * @param  $namespaceName
     * @return array
     */
    public function exportKeys($namespaceName)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($namespaceName, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $namespaceName
                             ));
            throw $error;
        }
        $result = array();

        $zendNamespace = $this->getNamespace($namespaceName);
        foreach ($zendNamespace->getIterator() as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }


    /**
     * @param  $namespaceName
     * @return Zend_Session_Namespace
     */
    public function getNamespace($namespaceName)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($namespaceName, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $namespaceName
                             ));
            throw $error;
        }

        $dict = $this->_namespacesDict;

        if (
                (isset($dict[$namespaceName]))
                && ($dict[$namespaceName] instanceof Zend_Session_Namespace)
        ) {
            return $dict[$namespaceName];
        }

        $dict[$namespaceName] = new Zend_Session_Namespace(
             $namespaceName = $this->newNamespacePrefixedName($namespaceName)
        );
        return $dict[$namespaceName];

    }


    /**
     * @throws Lib_Application_Exception
     * @param  $namespaceName
     * @return true
     */
    public function destroyNamespace($namespaceName)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($namespaceName, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $namespaceName
                             ));
            throw $error;
        }
        $zendNamespace = $this->getNamespace($namespaceName);
        return $zendNamespace->unsetAll();
    }

    /**
     * @return array
     */
    public function getAllNamespaces()
    {
        $result = array();
        
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();

        foreach($namespaceAvailableList as $namespaceName) {

            $zendNamespace = $this->getNamespace($namespaceName);
            $result[$namespaceName] = $zendNamespace;
        }

        return $result;
    }


    /**
     * @return void
     */
    public function destroyAllNamespaces()
    {
        $dict = $this->getAllNamespaces();

        foreach($dict as $zendNamespace) {

            if ($zendNamespace instanceof Zend_Session_Namespace) {

                /**
                 * @var Zend_Session_Namespace $zendNamespace
                 */
                $zendNamespace->unsetAll();
            }

        }
    }


    /**
     * @return void
     */
    public function start()
    {
        return $this->zendStart();
    }

    /**
	 * override
	 *
	 */

    public function init()
    {
       // var_dump(__METHOD__);

        $this->applyOptions();


        switch($this->getSaveHandlerType()) {

            case self::SAVEHANDLER_TYPE_MEMCACHED: {
                $this->_initSaveHandlerTypeMemcached();
                break;
            }
            case null:
            case self::SAVEHANDLER_TYPE_NONE: {
                $this->_initSaveHandlerTypeNone();
                break;
            }

            default: {
                throw new Exception("Invalid SaveHandlerType at ".__METHOD__);
                break;
            }

        }

    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->zendGetId();
    }



    /**
     * @return void
     */
    protected function _initSaveHandlerTypeNone()
    {
        //var_dump(__METHOD__);

    }

    /**
     * @return void
     */
    protected function _initSaveHandlerTypeMemcached()
    {
        // @see: http://www.minte9.com/kb/zend-memcached-programming-zend-framework-i4241
        //var_dump(__METHOD__);
        $frontendOptions = $this->getSaveHandlerConfig()->frontendOptions;
        $backendOptions = $this->getSaveHandlerConfig()->backendOptions; 
        $cache = Zend_Cache::factory(
                'Core',
                'Memcached',
                $frontendOptions->toArray(),
                $backendOptions->toArray()
        );

        $cacheIdPrefix = Bootstrap::getRegistry()->getApplicationPrefix();
        $cacheIdPrefix .= "__".strtolower(get_class($this));
        $cache->setOption('cache_id_prefix', $cacheIdPrefix);

        $saveHandler = new Lib_Session_SaveHandler_Memcached();
        $saveHandler->setCache($cache);
        $this->zendSetSaveHandler($saveHandler);

    }







    // ++++ from Zend_Session +++++++++++++++++++++++++

    /**
     * @param bool $removeCookie
     * @param bool $readOnly
     * @return void
     */
    public function zendDestroy($removeCookie = true, $readOnly = true)
    {
        return Zend_Session::destroy($removeCookie, $readOnly);
    }

    /**
     * @return void
     */
    public function zendExpireSessionCookie()
    {
        return Zend_Session::expireSessionCookie();
    }

    /**
     * @return void
     */
    public function zendForgetMe()
    {
        return Zend_Session::forgetMe();
    }

    /**
     * @return string
     */
    public function zendGetId()
    {
        return Zend_Session::getId();
    }

    /**
     * @return ArrayObject
     */
    public function zendGetIterator()
    {
        return Zend_Session::getIterator();
    }

    /**
     * @param null|string $optionName
     * @return array|string
     */
    public function zendGetOptions($optionName = null)
    {
        return Zend_Session::getOptions($optionName);
    }

    /**
     * @return Zend_Session_SaveHandler_Interface
     */
    public function zendGetSaveHandler()
    {
        return Zend_Session::getSaveHandler();
    }

    /**
     * @return bool
     */
    public function zendIsDestroyed()
    {
        return Zend_Session::isDestroyed();
    }

    /**
     * @return bool
     */
    public function zendIsReadable()
    {
        return Zend_Session::isReadable();
    }

    /**
     * @return bool
     */
    public function zendIsRegenerated()
    {
        return Zend_Session::isRegenerated();
    }

    /**
     * @return bool
     */
    public function zendIsStarted()
    {
        return Zend_Session::isStarted();
    }

    /**
     * @return bool
     */
    public function zendIsWritable()
    {
        return Zend_Session::isWritable();
    }

    /**
     * @param  $name
     * @return array
     */
    public function zendNamespaceGet($name)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($name, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $name
                             ));
            throw $error;
        }
        $name = $this->newNamespacePrefixedName($name);
        return Zend_Session::namespaceGet($name);
    }

    /**
     * @param  $name
     * @return bool
     */
    public function zendNamespaceIsset($name)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($name, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $name
                             ));
            throw $error;
        }

        $name = $this->newNamespacePrefixedName($name);
        return Zend_Session::namespaceIsset($name);
    }

    /**
     * @param  $name
     * @return void
     */
    public function zendNamespaceUnset($name)
    {
        $namespaceAvailableList = $this->getNamespaceNamesAvailable();
        if (!in_array($name, $namespaceAvailableList, true)) {
            $error = new Lib_Application_Exception(
                "This session namespace is not available"
            );
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                    "namespace" => $name
                             ));
            throw $error;
        }
        
        $name = $this->newNamespacePrefixedName($name);
        return Zend_Session::namespaceUnset($name);
    }

    /**
     * @return void
     */
    public function zendRegenerateId()
    {
        return Zend_Session::regenerateId();
    }

    /**
     * @param Zend_Session_Validator_Interface $validator
     * @return void
     */
    public function zendRegisterValidator(
        Zend_Session_Validator_Interface $validator
    )
    {
        return Zend_Session::registerValidator($validator);
    }

    /**
     * @param null $seconds
     * @return void
     */
    public function zendRememberMe($seconds = null)
    {
        return Zend_Session::rememberMe($seconds);
    }

    /**
     * @param int $seconds
     * @return void
     */
    public function zendRememberUntil($seconds = 0)
    {
        return Zend_Session::rememberUntil($seconds);
    }

    /**
     * @return bool
     */
    public function zendSessionExists()
    {
        return Zend_Session::sessionExists();
    }

    /**
     * @param  string $id
     * @return void
     */
    public function zendSetId($id)
    {
        return Zend_Session::setId($id);
    }

    /**
     * @param array $userOptions
     * @return void
     */
    public function zendSetOptions($userOptions = array())
    {
        return Zend_Session::setOptions($userOptions);
    }

    /**
     * @param Zend_Session_SaveHandler_Interface $saveHandler
     * @return void
     */
    public function zendSetSaveHandler(
        Zend_Session_SaveHandler_Interface $saveHandler
    )
    {
        return Zend_Session::setSaveHandler($saveHandler);
    }

    /**
     * @param  array|null $options
     * @return void
     */
    public function zendStart($options = null)
    {
        return Zend_Session::start($options);
    }

    /**
     * @return void
     */
    public function zendStop()
    {
        return Zend_Session::stop();
    }

    /**
     * @param bool $readOnly
     * @return void
     */
    public function zendWriteClose($readOnly = true)
    {
        return Zend_Session::writeClose($readOnly);
    }



}
