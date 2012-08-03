<?php

namespace Processus
{
    use Zend\Config\Config;

    /**
     *
     */
    class ProcessusRegistry
    {

        /**
         * @var Config
         */
        private $config;

        /**
         * @var \Processus\Lib\Vo\Configs\ProcessusConfig
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
         *
         * @return null|mixed
         */
        public function getConfig($key = NULL)
        {
            if (!is_null($key) && $this->config->$key) {
                return $this->config->$key;
            }

            return NULL;
        }

        // #########################################################


        /**
         * @return Lib\Vo\Configs\ProcessusConfig
         */
        public function getProcessusConfig()
        {
            if (!$this->_processusConfig) {
                $this->_processusConfig = new \Processus\Lib\Vo\Configs\ProcessusConfig();
                $this->_processusConfig->setData($this->getConfig("processus"));
            }

            return $this->_processusConfig;
        }

        /**
         * @var \Processus\Lib\Vo\Configs\FacebookConfig
         */
        private $_facebookConfig;

        /**
         * @return Lib\Vo\Configs\FacebookConfig
         */
        public function getFacebookConfig()
        {
            if (!$this->_facebookConfig) {
                $this->_facebookConfig = new \Processus\Lib\Vo\Configs\FacebookConfig();
                $this->_facebookConfig->setData($this->getConfig("Facebook"));
            }

            return $this->_facebookConfig;
        }
    }
}

?>