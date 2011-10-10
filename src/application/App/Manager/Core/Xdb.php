<?php
/**
 * App_Manager_Core_Xdb Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Core
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_Core_Xdb
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Core
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_Core_Xdb extends App_Manager_AbstractManager
{


    /**
     * @var App_Manager_Core_Xdb
     */
    private static $_instance;

    /**
     * @static
     * @return App_Manager_Core_Xdb
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    // ++++ for cms +++++++++++++++++
    /**
     * @var App_Cms_Db_Xdb_Client
     */
    protected $_dbClientCms;

    /**
     * @return App_Cms_Db_Xdb_Client
     */
    public function getDbClientCms()
    {
        throw new Exception("Implement ".__METHOD__);
    }


    // ++++ for vz ++++++

    /**
     * @var App_Vz_Db_Xdb_Client
     */
    protected $_dbClientVz;

    /**
     * @return App_Vz_Db_Xdb_Client
     */
    public function getDbClientVz()
    {
        if (($this->_dbClientVz instanceof App_Vz_Db_Xdb_Client) !== true) {

            $this->_dbClientVz = new App_Vz_Db_Xdb_Client();
            $this->_dbClientVz->init();
        }
        return $this->_dbClientVz;

    }


    // ++++ for fb ++++++

   /**
     * @var App_Facebook_Db_Xdb_Client
     */
    protected $_dbClientFb;

    /**
     * @return App_Facebook_Db_Xdb_Client
     */
    public function getDbClientFb()
    {
        if (($this->_dbClientFb instanceof App_Facebook_Db_Xdb_Client) !== true) {

            $this->_dbClientFb = new App_Facebook_Db_Xdb_Client();
            $this->_dbClientFb->init();
        }
        return $this->_dbClientFb;

    }

   
}
