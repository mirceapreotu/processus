<?php
/**
 * App_Manager_Core_Memcached Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Core
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_Core_Memcached
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Core
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_Core_Memcached extends App_Manager_AbstractManager
{


    /**
     * @var App_Manager_Core_Memcached
     */
    private static $_instance;

    /**
     * @static
     * @return App_Manager_Core_Memcached
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    /**
     * @var Lib_Cache_Impl_Osapi
     */
    protected $_osapi;

    /**
     * The default memcached
     * @return Lib_Cache_Impl_Osapi
     */
    public function getOsapi()
    {

        if (($this->_osapi instanceof Lib_Cache_Impl_Osapi) !== true) {

            $config = Bootstrap::getRegistry()->getConfig();
            $params = null; // use defaults

            if (isset($config->cache)) {

                $params = $config->cache->params->backend->toArray();
            }

            $this->_osapi = new Lib_Cache_Impl_Osapi($params);
        }
        return $this->_osapi;
    }

    



   
}
