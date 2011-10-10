<?php
/**
 * App_Manager_Fb_AppConfig Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Fb
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_Fb_AppConfig
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_Fb_AppConfig extends App_Manager_AppConfig
{



    /**
     * @var App_Manager_Fb_AppConfig
     */
    private static $_instance;

    /**
     * @static
     * @return App_Manager_Fb_AppConfig
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
     * @override
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





    
}
