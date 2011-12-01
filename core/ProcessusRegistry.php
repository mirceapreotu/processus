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
         * @return mixed
         */
        public function getConfig($key = NULL)
        {
            if (!is_null($key) && $this->config->$key) {
                return $this->config->$key;
            }
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
    }
}

?>