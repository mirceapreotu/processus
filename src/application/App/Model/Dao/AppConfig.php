<?php
/**
 * App_Model_Dao_AppConfig
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dao_Store
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Dao_AppConfig
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dao_Store
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class App_Model_Dao_AppConfig extends Lib_Model_Dao_Store_Json
{
    /*
    const KEY_CAMPAIGN_STARTDATE = "campaignStartDate";
    const KEY_CAMPAIGN_ENDDATE = "campaignEndDate";
    const KEY_CURRENTDATE = "currentDate";
    const KEY_ENTERTAINING_EGG_VALUE = "entertainingEggValue";
    const KEY_ENTERTAINING_EGG_SHOW_TEASER = "entertainingEggShowTeaser";
    */
    const KEY_NEWS_ITEMS_COUNT = "newsItemsCount";

     /**
     * @var App_Model_Dao_AppConfig
     */
    private static $_instance;
    
	/**
	 * @var string
	 */
	protected $_dbTable = "AppConfig";

    /**
     * @var string
     */
    protected $_dbKeyColumn = "key";
    /**
     * @var string
     */
    protected $_dbValueColumn = "value";


     /**
     * @static
     * @throws Exception
     * @return App_Model_Dao_AppConfig
     */
    public static function getInstance()
    {


        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
         
         
    }

    
}
