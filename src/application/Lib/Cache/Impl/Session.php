<?php
/**
 * Lib_Cache_Impl_Session
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
 * Lib_Cache_Impl_Session
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

class Lib_Cache_Impl_Session
	
{



        /**
         * override in subclass!
         * each key will be prefixed
         * @return string
         */
        public function getNamespacePrefix()
        {
            $moduleId = "Session_".get_class($this);
            $prefix = Bootstrap::getRegistry()
                ->newApplicationModulePrefix($moduleId);
            return $prefix;
        }


        /**
         * @var Zend_Session_Namespace
         */
        protected $_namespace;

        /**
         * @return Zend_Session_Namespace
         */
        public function getNamespace()
        {
            if (($this->_namespace instanceof Zend_Session_Namespace)!==true) {
                $this->_namespace = new Zend_Session_Namespace(
                    $this->getNamespacePrefix()
                );
            }
            return $this->_namespace;
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

            $this->getNamespace()->$prefixedKey = $value;

            $result = true;
            return $result;

        }

        /**
         * @param string $key
         * @return false|mixed
         */
        public function load($key)
        {
            $prefixedKey = $this->newPrefixedPropertyName($key);
            return $this->getNamespace()->$prefixedKey;

        }

        /**
         * @param string $key
         * @return bool
         */
        public function remove($key)
        {
            $prefixedKey = $this->newPrefixedPropertyName($key);
            delete($this->getNamespace()->$prefixedKey);
            return true;
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







    /**
     * override
     * @var array
     */
    public function getOptions()
    {
        $name = Bootstrap::getRegistry()
            ->getApplicationPrefix()."Cms";
        $value = array(
            //'cookie_secure'   => true, // only for https
            'name'              => $name,
            'cookie_httponly'   => true,
            //'gc_maxlifetime'  => 60*60,
        );
        return $value;
    }



    /**
     * override
     * 
     */
    public function init()
    {

        /*
        $cacheProxy = Bootstrap::getRegistry()
            ->getMemcachedSession();

        $saveHandler =
            new Lib_Session_SaveHandler_Memcached();
        $saveHandler->setCacheProxy($cacheProxy);
        Zend_Session::setSaveHandler($saveHandler);
        */
        
        $options = $this->getOptions();
        Zend_Session::setOptions($options);
    }








    /**
     * @return Zend_Session_Namespace
     */
    public function getData()
    {
        return $this->getNamespace();
    }


    /**
     * @return array
     */
    public function getDataPropertyNames()
    {
        $result = array();
        foreach ($this->getData()->getIterator() as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function exportData()
    {
        $result = array();
        foreach ($this->getData()->getIterator() as $key => $value) {
            $result[$key] = $value;
        }
        return $result;

    }









    /**
     *
     */
    public function start()
    {

        Zend_Session::start();

        $namespace = $this->getNamespace();


    }


    /**
     * @return bool
     */
    public function isStarted()
    {
        $result = (bool)Zend_Session::isStarted();
        return $result;
    }


    /**
     * @return string
     */
    public function getId()
    {
        $id = Zend_Session::getId();
        return $id;
    }

    /**
     * @return string
     */
    public function regenerateId()
    {
        Zend_Session::regenerateId();
        return $this->getId();
    }


}
