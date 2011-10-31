<?php

    namespace Processus\Abstracts\JsonRpc
    {
        use Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest;
        use Processus\Abstracts\JsonRpc\AbstractJsonRpcServer;

        /**
         *
         * @example {"id":"112","method":"Pub.User.listing","params":[{"name":"Tino"}], "extended":[{}]}
         *
         */
        abstract class AbstractJsonRpcGateway
        {
            // #########################################################


            /**
             * @var AbstractJsonRpcRequest
             */
            protected $_request;

            /**
             * @var AbstractJsonRpcServer
             */
            protected $_server;

            /**
             * @var array
             */
            protected $_config;

            /**
             * @return bool
             */
            public function isEnabled()
            {
                if ($this->getConfigValue('enabled') === TRUE) {
                    return TRUE;
                }

                // throw new Exception
                return FALSE;
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
                return FALSE;
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
                return FALSE;
            }

            // #########################################################


            /**
             * @return bool
             */
            public function isValidRequest()
            {
                if ($this->isEnabled() && $this->hasNamespace() && $this->isValidDomain()) {
                    return TRUE;
                }

                // throw new Exception
                return FALSE;
            }

            // #########################################################


            /**
             * @return Core\Abstracts\AbstractJsonRpcServer
             */
            public function getServer()
            {
                $serverClassName = $this->getServerClassName();

                /** @var $server  AbstractJsonRpcServer */
                $server = new $serverClassName();
                $server->setRequest($this->getRequest());

                return $server;
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

            /**
             *
             */
            protected function _run()
            {
                $server = $this->getServer();
                $server->run();
            }

            // #########################################################

            /**
             * @return AbstractJsonRpcRequest
             */
            private function getRequest()
            {
                if (!$this->_request) {

                    $requestClassName = $this->getRequestClassName();

                    /** @var $_request AbstractJsonRpcRequest */
                    $this->_request = new $requestClassName;
                    $this->_request->setSpecifiedNamespace($this->getConfigValue('namespace'));
                }

                return $this->_request;
            }

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
             * @param null $key
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