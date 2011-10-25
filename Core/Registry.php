<?php
/**
 * Core_Registry
 *
 *
 *
 * @category
 * @package
 *
 * @copyright
 * @license
 * @version     
 */

class Core_Registry
{
    /**
     * @var Core_Registry instance
     */
    protected static $_instance;

	/**
	 * @var holds Zend_Config
	 */
	private $config;


    // #########################################################


    /**
	 * @static
	 * @return Core_Registry
	 */
    public static function getInstance()
    {
        if (self::$_instance instanceof self !== TRUE)
        {
            self::$_instance = new self();
            self::$_instance->init();
        }

        return self::$_instance;
    }


    // #########################################################


    /**
	 * @return void
	 */
    public function init()
    {
        $this->config = new Zend_Config(require PATH_APP . '/Config/config.php');
    }


    // #########################################################


    /**
	 * @param null $key
	 * @return
	 */
    public function getConfig($key = NULL)
    {
        if( ! is_null($key) && $this->config->$key)
        {
			return $this->config->$key;
        }

	    return;
    }
}

?>
