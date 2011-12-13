<?php

namespace Processus\Abstracts\JsonRpc
{

    abstract class AbstractJsonRpcServer extends \Zend\Json\Server\Server
    {

        protected $_config;

        // #########################################################


        /**
         * @return bool
         */
        public function hasNamespace()
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
        public function isValidClass()
        {
            if ($this->validateConfigKey('validClasses') && in_array($this->getRequest()->getClass(), $this->getConfigValue('validClasses'))) {
                return TRUE;
            }
            else
            {
                $exception = new \Processus\Exceptions\JsonRpc\ValidJsonRpcRequest("Is not a valid class!", "PRC-2001_" . __METHOD__, "10", __FILE__, __LINE__);
                throw $exception;
            }

            return FALSE;
        }

        // #########################################################


        /**
         * @return bool
         */
        public function isValidRequest()
        {
            if ($this->hasNamespace() && $this->isValidClass()) {
                return TRUE;
            }
            else
            {
                $exception = new \Processus\Exceptions\JsonRpc\ValidJsonRpcRequest("Is not a valid request!", "PRC-2000_" . __METHOD__, "10", __FILE__, __LINE__);
                throw $exception;
            }

            return FALSE;
        }

        // #########################################################


        /**
         * @throws \Processus\Exceptions\JsonRpc\ServerException
         */
        public function run()
        {
            // if valid request let it handle via Zend\Json\Server\Server
            if ($this->isValidRequest() === TRUE) {
                $this->_run();
            }
            else
            {
                $exception = new \Processus\Exceptions\JsonRpc\ServerException("Invalid Server Class.");
                $exception->setMethod(__METHOD__);
                throw $exception;
            }
        }

        // #########################################################


        /**
         * internal run method
         */
        protected function _run()
        {
            // set class
            $this->setClass($this->getRequest()->getSpecifiedServiceClassName());

            // Handle the request:
            $this->handle();
        }

        // #########################################################


        /**
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest
         */
        public function getRequest()
        {
            return parent::getRequest();
        }

        /**
         * @return mixed
         */
        public function getConfig()
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

        /**
         *
         * Get response object
         *
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcResponse
         */
        public function getResponse()
        {
            if (null === ($response = $this->_response)) {
                $this->setResponse(new AbstractJsonRpcResponse());
            }
            return $this->_response;
        }

        /**
         * @param bool $request
         *
         * @return \Zend\Json\Server\Zend\Json\Server\Response
         * @throws \Exception
         */
        public function handle($request = false)
        {
            if ((false !== $request) && (!$request instanceof Request)) {
                throw new \Exception('Invalid request type provided; cannot handle');
            } elseif ($request) {
                $this->setRequest($request);
            }

            // Handle request
            $this->_handle();

            // Get response
            $response = $this->_getReadyResponse();

            // Emit response?
            if ($this->autoEmitResponse()) {
                echo $response;
                exit();
            }

            // or return it?
            return $response;
        }

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