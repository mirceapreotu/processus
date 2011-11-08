<?php

namespace Processus\Abstracts\JsonRpc
{
    
    use Zend\Json\Server\Server;

    abstract class AbstractJsonRpcServer extends Server
    {

        protected $_config;

        // #########################################################
        

        /**
         * @return bool
         */
        public function hasNamespace ()
        {
            if ($this->getRequest()->getSpecifiedNamespace()) {
                return TRUE;
            }
            
            return FALSE;
        }

        // #########################################################
        

        /**
         * @return bool
         */
        public function isValidClass ()
        {
            if ($this->validateConfigKey('validClasses') && in_array($this->getRequest()->getClass(), $this->getConfigValue('validClasses'))) {
                return TRUE;
            }
            
            return FALSE;
        }

        // #########################################################
        

        /**
         * @return bool
         */
        public function isValidRequest ()
        {
            if ($this->hasNamespace() && $this->isValidClass()) {
                return TRUE;
            }
            
            return FALSE;
        }

        // #########################################################
        

        /**
         * public run method
         */
        public function run ()
        {
            // if valid request let it handle via Zend\Json\Server\Server
            if ($this->isValidRequest() === TRUE) {
                $this->_run();
            }
        }

        // #########################################################
        

        /**
         * internal run method
         */
        protected function _run ()
        {
            // set class
            $this->setClass($this->getRequest()
                ->getSpecifiedServiceClassName());
            
            // Handle the request:
            $this->handle();
        }

        // #########################################################
        

        /**
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest
         */
        public function getRequest ()
        {
            return parent::getRequest();
        }

        /**
         * @return mixed
         */
        public function getConfig ()
        {
            return $this->_config;
        }

        // #########################################################
        

        /**
         * @param $key
         * @return mixed | bool
         */
        private function getConfigValue ($key)
        {
            if (array_key_exists($key, $this->getConfig())) {
                return $this->_config[$key];
            }
            
            return false;
        }

        // #########################################################
        

        /**
         * @param null $key
         * @return bool
         */
        private function validateConfigKey ($key = NULL)
        {
            if (array_key_exists($key, $this->getConfig())) {
                return TRUE;
            }
            
            return FALSE;
        }
    }
}

?>