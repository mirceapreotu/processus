<?php

namespace Processus
{
    use Processus\Lib\Vo\Configs\ProcessusConfig;
    
    use Zend\Config\Config;

    /**
     *
     */
    class Registry
    {

        /**
         * @var Config
         */
        private $config;

        /**
         * @var ProcessusConfig
         */
        private $_processusConfig;

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
         * @return bool
         */
        public function getConfig($key = NULL)
        {
            if (! is_null($key) && $this->config->$key) {
                return $this->config->$key;
            }
            
            return false;
        }

        // #########################################################
        

        /**
         * @return ProcessusConfig
         */
        public function getProcessusConfig()
        {
            if (! $this->_processusConfig) {
                $this->_processusConfig = new ProcessusConfig();
                $this->_processusConfig->setData($this->getConfig("processus"));
            }
            
            return $this->_processusConfig;
        }
    }
}

?>