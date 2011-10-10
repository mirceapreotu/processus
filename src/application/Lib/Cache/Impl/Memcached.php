<?php
/**
 * Lib_Cache_Impl_Memcached
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
 * Lib_Cache_Impl_Memcached
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
class Lib_Cache_Impl_Memcached
	implements Lib_Cache_MemcachedProxyInterface
{

    /**
     * @var array
     */
    protected $_backendOptions = array(
        'servers' => array(
            array(
                'host' => '127.0.0.1',
                'port' => 11211
            )
        ),
        'compression' => false
    );
    
	/**
	 * @var Zend_Cache_Core
	 */
	protected $_cache;

	/**
	 * override in subclass!
	 * each key will be prefixed
	 * @return string
	 */
	public function getNamespacePrefix()
	{
		$moduleId = "CacheMemcached";
		$prefix = Bootstrap::getRegistry()
			->newApplicationModulePrefix($moduleId);
		return $prefix;
	}


    /**
	 * override in subclass
     * @return array
     */
    public function getFrontendOptions()
    {
		return  array(
        'caching' => true,
        'lifetime' => 1800,
        'automatic_serialization' => true
    	);
    }

    /**
     * @return array
     */
    public function getBackendOptions()
    {
		return $this->_backendOptions;
    }

    /**
     * @param array|null $config
     */
    public function __construct($config=null)
    {
        if ($config !== null) {

            $this->_backendOptions = $config;
        }
    }

    /**
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
		$property = "_cache";
        if (($this->$property instanceof Zend_Cache_Core) !== true) {
            $this->$property = Zend_Cache::factory(
                'Core', 'Memcached',
                $this->getFrontendOptions(),
                $this->getBackendOptions()
            );
        }
        return $this->$property;
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

		return "".$namespacePrefix."_".$key;

	}



}
