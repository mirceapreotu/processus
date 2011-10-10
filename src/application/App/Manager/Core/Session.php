<?php
/**
 * App_Manager_Core_Session Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Core
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_Core_Session
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Core
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_Core_Session extends App_Manager_AbstractManager
{


    /**
     * @var App_Manager_Core_Session
     */
    private static $_instance;

    /**
     * @static
     * @return App_Manager_Core_Session
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    // ++++ session for cms +++++++++++++++++
    /**
     * @var App_Cms_Session
     */
    protected $_sessionCms;

    /**
     * @return App_Cms_Session
     */
    public function getSessionCms()
    {
        if (($this->_sessionCms instanceof App_Cms_Session) !== true) {

            $this->_sessionCms = new App_Cms_Session();
            $this->_sessionCms->init();
            $this->_sessionCms->start();
        }
        return $this->_sessionCms;
    }


    // ++++ session for vz app ++++++

    /**
     * @var App_Vz_Session
     */
    protected $_sessionVz;

    /**
     * @return App_Vz_Session
     */
    public function getSessionVz()
    {
        if (($this->_sessionVz instanceof App_Vz_Session) !== true) {

            $this->_sessionVz = new App_Vz_Session();
            $this->_sessionVz->init();
            $this->_sessionVz->start();
        }
        return $this->_sessionVz;

    }

    // ++++ session for fb app ++++++

    /**
     * @var App_Facebook_Session
     */
    protected $_sessionFb;

    /**
     * @return App_Facebook_Session
     */
    public function getSessionFb()
    {
        if (($this->_sessionFb instanceof App_Facebook_Session) !== true) {

            $this->_sessionFb = new App_Facebook_Session();
            $this->_sessionFb->init();
            $this->_sessionFb->start();
        }
        return $this->_sessionFb;

    }

   
}
