<?php

namespace Processus
{
    use Zend\Config\Config;

    /**
     *
     */
    class Registry
    {
        /**
         * @var Registry instance
         */
        protected static $_instance;

    	/**
    	 * @var Config
    	 */
    	private $config;


        // #########################################################


        /**
    	 * @static
    	 * @return Registry
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
            $this->config = new Config(require PATH_APP . '/Application/Config/config.php');
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
}

?>