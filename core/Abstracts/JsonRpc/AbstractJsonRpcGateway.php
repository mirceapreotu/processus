<?php

namespace Processus\Abstracts\JsonRpc
{
    /**
     *
     * @example {"id":"112","method":"Pub.User.listing","params":[{"name":"Tino"}], "extended":[{}]}
     *
     */
    abstract class AbstractJsonRpcGateway extends \Processus\Abstracts\AbstractClass
    {

        // #########################################################

        /**
         * @var \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest
         */
        protected $_request;

        /**
         * @var \Processus\Abstracts\JsonRpc\AbstractJsonRpcServer
         */
        protected $_server;

        /**
         * @var array
         */
        protected $_config;

        /**
         * @var \Processus\Interfaces\InterfaceAuthModule
         */
        protected $_authModule;

        /**
         * @return bool
         */
        public function isEnabled()
        {
            if ($this->getConfigValue('enabled') === TRUE) {
                return TRUE;
            }

            // throw new Exception
            throw new \Exception("Not Enabled!", 1);
        }

        // #########################################################


        /**
         * @return bool
         */
        public function hasNamespace()
        {
            if ($this->validateConfigKey('namespace')) {
                return TRUE;
            }

            // throw new Exception
            throw new \Exception("No Namespace!", 1);
        }

        // #########################################################


        /**
         * @return bool
         */
        public function isValidDomain()
        {
            if ($this->validateConfigKey('validDomains') && in_array($this->getRequest()->getDomain(), $this->getConfigValue('validDomains'))) {
                return TRUE;
            }

            // throw new Exception
            throw new \Exception("Not a valid request!", 1);
        }

        // #########################################################


        /**
         * @return bool
         */
        public function isValidRequest()
        {
            $authModule = $this->getAuthModule();

            if ($authModule instanceof \Processus\Interfaces\InterfaceAuthModule) {

                if ($authModule->isAuthorized() === FALSE) {
                    throw new \Exception("Authorisation Required");
                }
            }

            if ($this->isEnabled() && $this->hasNamespace() && $this->isValidDomain()) {
                return TRUE;
            }

            // throw new Exception
            throw new \Exception("Not a valid request!", 1);
        }

        // #########################################################

        /**
         * @return AbstractJsonRpcServer
         */
        public function getServer()
        {
            if (!$this->_server) {
                $serverClassName = $this->getServerClassName();

                /** @var $server  AbstractJsonRpcServer */
                $this->_server = new $serverClassName();
                $this->_server->setRequest($this->getRequest());
            }

            return $this->_server;
        }

        // #########################################################


        /**
         * @return string
         */
        protected function getServerClassName()
        {
            return $this->getConfigValue('namespace') . '\\' . 'Server';
        }

        // #########################################################


        /**
         *
         */
        public function run()
        {
            // if valid request run server
            if ($this->isValidRequest() === TRUE) {
                $this->_run();
            }
        }

        // #########################################################

        protected function _run()
        {
            /** @var $server \Processus\Abstracts\JsonRpc\AbstractJsonRpcServer */
            $server = $this->getServer();
            $server->run();
        }

        // #########################################################


        /**
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest
         */
        public function getRequest()
        {
            if (!$this->_request) {

                $requestClassName = $this->getRequestClassName();

                /** @var $_request \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest */
                $this->_request = new $requestClassName();
                $this->_request->setSpecifiedNamespace($this->getConfigValue('namespace'));
            }

            return $this->_request;
        }

        // #########################################################

        /**
         * @return string
         */
        protected function getRequestClassName()
        {
            return $this->getConfigValue('namespace') . '\\' . 'Request';
        }

        // #########################################################


        /**
         * @return mixed
         */
        private function getConfig()
        {
            return $this->_config;
        }

        // #########################################################


        /**
         * @param $key
         *
         * @return mixed | bool
         */
        private function getConfigValue($key)
        {
            if (array_key_exists($key, $this->getConfig())) {

                return $this->_config[$key];

            }

            return false;
        }

        // #########################################################


        /**
         * @return null | \Processus\Interfaces\InterfaceAuthModule
         */
        public function getAuthModule()
        {
            $authClass  = $this->getConfigValue('namespace') . "\\" . "Auth";
            $authFile   = str_replace("\\", "/", $this->getConfigValue('namespace') . "\\" . "Auth");
            $classExist = file_exists(PATH_APP . "/" . $authFile . '.php');

            if ($classExist) {

                try {

                    /** @var $_authModule \Processus\Interfaces\InterfaceAuthModule */
                    $this->_authModule = new $authClass();
                    $this->_authModule->setAuthData($this->getRequest());

                    return $this->_authModule;
                }
                catch (\Exception $error)
                {
                    throw $error;
                }

            }

            return null;
        }

        // #########################################################


        /**
         * @param null $key
         *
         * @return bool
         */
        private function validateConfigKey($key = NULL)
        {
            if (array_key_exists($key, $this->getConfig())) {
                return TRUE;
            }

            return FALSE;
        }
    }
}

?>