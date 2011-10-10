<?php
/**
 * App_Manager_Fb_Abstract
 *
 * @category	meetidaaa.com
 * @package        App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */

/**
 * App_Manager_Fb_Abstract
 *
 *
 * @category	meetidaaa.com
 * @package        App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */
class App_Manager_Fb_Abstract extends App_Manager_AbstractManager
{



     /**
     * @var App_Manager_Fb_Abstract
     */
    private static $_instance;


    /**
     * @static
     * @return App_Manager_Fb_Abstract
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }


    /**
     * @override
     * @return App_Facebook_Db_Xdb_Client
     */
	public function getDbClient()
	{
        return $this->getApplication()->getDbClient();
	}

    /**
     * @return null|string
     */
    public function getCurrentDate()
    {
        return $this->getApplication()->getCurrentDate();
    }


    /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++

}
