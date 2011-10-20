<?php
/**
 * Lib_Cache_Memcached
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Cache_Impl
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Cache_Memcached
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Cache_Impl
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Cache_Memcached
	
{

    /**
     * @var string
     */
    protected $_namespaceName = "Default";



    /**
      * @var Zend_Config
      */
     protected $_config;
     /**
      * override in subclass or inject using Zend_Config
      * @var array
      */
     protected $_configDefault = array(

         "model" => array(
            "version" => 1,
         ),

         "backendOptions" => array(
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => 11211
                )
            ),
            'compression' => true
         ),

         "frontendOptions" => array(
            'caching' => true,
	        'cache_id_prefix' => null,
	        'logging' => false,
	        //'logger'  => $oCacheLog,
	        'write_control' => true,
	        'automatic_serialization' => false,
	        'ignore_user_abort' => true,

            'caching' => true,

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


     public function setConfig(Zend_Config $config)
     {
         $this->_config = $config;

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



     // +++++++++++++++++ core +++++++++++++++++++++







	/**
	 * @var Zend_Cache_Core
	 */
	protected $_cache;




    /**
	 * override in subclass
     * @return array
     */
    public function getFrontendOptions()
    {
        $result =$this->getConfig()->frontendOptions;;
        if (($result instanceof Zend_Config)!==true) {
            throw new Exception(
                "Invalid config.frontendOptions at ".__METHOD__
            );
        }
		return $result;
    }

    /**
     * @return array
     */
    public function getBackendOptions()
    {
        $result =$this->getConfig()->backendOptions;;
        if (($result instanceof Zend_Config)!==true) {
            throw new Exception(
                "Invalid config.backendOptions at ".__METHOD__
            );
        }
		return $result;

    }


    /**
     * @return array
     */
    public function getModelConfig()
    {
        $result =$this->getConfig()->model;
        if (($result instanceof Zend_Config)!==true) {
            throw new Exception(
                "Invalid config.model at ".__METHOD__
            );
        }
		return $result;

    }

    /**
     * @throws Exception
     * @return int
     */
    public function getModelVersion()
    {
        $modelConfig = $this->getModelConfig();
        $version = $modelConfig->version;

        if ($version === null) {
            $version = 0;
        }
        if (is_int($version)!==true) {
            throw new Exception("Invalid config.model.version at ".__METHOD__);
        }

        return (int)$version;

    }


    /**
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        if (($this->_cache instanceof Zend_Cache_Core) !== true) {

            // check for prefix
            $frontendOptions = $this->getFrontendOptions()->toArray();

            $backendOptions = $this->getBackendOptions()->toArray();

            $frontendOptions['cache_id_prefix'] = $this->getCacheIdPrefix();


            $this->_cache = Zend_Cache::factory(
                'Core', 'Memcached',
                $frontendOptions,
                $backendOptions
            );
        }
        return $this->_cache;
    }


    /**
     * @return string
     */
    public function getNamespaceName()
    {
        return (string)$this->_namespaceName;
    }

    /**
     * @throws Exception
     * @param  string|null $value
     * @return
     */
    public function setNamespaceName($value)
    {
        if ($value === null) {
            $this->_namespaceName = "Default";
            return;
        }

        if (is_string($value)!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $value = str_replace(
            array(".", " ", ":"),
            array("_", "_", "_"),
                $value
        );
        if (is_string($value)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }


        if (Lib_Utils_String::isEmpty($value)) {
            $value = "Default";
        }

        $this->_namespaceName = $value;

    }


    /**
     * @return string
     */
    public function getApplicationPrefix() {
        $prefix = Bootstrap::getRegistry()->getApplicationPrefix();
        $prefix = str_replace(
            array(".", " "),
            array("_", "_"),
                $prefix
        );
        if (is_string($prefix)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }
        return $prefix;
    }


    /**
     * @return string
     */
    public function getCacheIdPrefix()
    {
        $applicationPrefix = $this->getApplicationPrefix();
        $prefix = $applicationPrefix."___CLASS___".get_class($this);
        return $prefix;
    }


    /**
     * @throws Exception
     * @return string
     */
    public function getNamespacePrefix()
    {
        $namespace = $this->_namespaceName;
        $namespace = str_replace(
            array(".", " ", ":"),
            array("_", "_", "_"),
                $namespace
        );
        if (is_string($namespace)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }

        if (Lib_Utils_String::isEmpty($namespace)) {
            $namespace = "Default";
        }


        
        $prefix = $this->getCacheIdPrefix()
                  ."___MODELVERSION___V".$this->getModelVersion()
                  ."__NAMESPACE___".$namespace;

        ;
        return $prefix;
    }






    // proxied methods

    /**
     * @throws Exception
     * @param  string $key
     * @param  mixed $value
     * @return bool
     */
    public function save($key, $value)
    {
        
        $prefixedKey = $this->newPrefixedPropertyName($key);

        $result = (bool)$this->getCache()
			->save($value, $prefixedKey);
        return $result;

    }


    /**
     * @param  $key
     * @param  mixed $value
     * @return bool
     */
    public function saveJson($key, $value)
    {
        $data = array("value"=>$value);
        $dataJson = json_encode($data);
        $result = $this->save($key, $dataJson);
        return $result;
    }

    /**
     * @param  string $key
     * @return mixed|null
     */
    public function loadJson($key)
    {
        $result = null;
        $data = $this->load($key);

        if (is_string($data)!==true) {
            return $result;
        }

        $data = json_decode($data, true);
        return Lib_Utils_Array::getProperty($data, "value");

    }




    /**
     * @param string $key
     * @return false|mixed
     */
    public function load($key)
    {
        $prefixedKey = $this->newPrefixedPropertyName($key);

        $result = $this->getCache()
			->load($prefixedKey);
        return $result;

    }

    /**
     * @param string $key
     * @return bool
     */
    public function test($key)
    {
        $prefixedKey = $this->newPrefixedPropertyName($key);

        $result = $this->getCache()
            ->test($prefixedKey);

        if ($result===false) {
            return $result;
        }

        return true;

    }


    /**
     * @param  string $key
     * @param  int $extraLifetime
     * @return bool
     */
    public function touch($key, $extraLifetime)
    {
        if (is_int($extraLifetime)!==true) {
            throw new Exception(
                "Invalid parameter 'extraLifetime' at ".__METHOD__
            );
        }
        $prefixedKey = $this->newPrefixedPropertyName($key);

        $result = $this->getCache()
            ->touch($prefixedKey, $extraLifetime);

        return (bool)($result===true);

    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        $prefixedKey = $this->newPrefixedPropertyName($key);

        $result = (bool)$this->getCache()
			->remove($prefixedKey);
        return $result;
    }

    /**
     * @throws Exception
     * @param string $key
     * @return string
     */
	public function newPrefixedPropertyName($key)
	{
        
		if (Lib_Utils_String::isEmpty($key) === true) {
			$message = "Parameter 'key' must be a string and cant be empty!";
			$message .= "key=" . $key . " at method" . __METHOD__;
            throw new Exception($message);
        }

		$namespacePrefix = $this->getNamespacePrefix();
		if (Lib_Utils_String::isEmpty($namespacePrefix) === true) {
			$message = "Parameter 'namespacePrefix' must be a string";
			$message .=" and cant be empty!";
			$message .= " at method" . __METHOD__;
            throw new Exception($message);
        }

		return "___".$namespacePrefix."___PROPERTY___".$key;

	}



}
