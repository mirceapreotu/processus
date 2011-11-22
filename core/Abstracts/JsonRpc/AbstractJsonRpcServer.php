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
         * @param null $fault
         * @param int  $code
         * @param null $data
         *
         * @return \Zend\Json\Server\Error
         */
        public function fault($fault = null, $code = 404, $data = null)
        {
            $error = new \Zend\Json\Server\Error($fault, $code, $data);
            $this->getResponse()->setError($error);
            return $error;
        }

        /**
         * Handle request
         *
         * @param  Zend\Json\Server\Request $request
         *
         * @return null|Zend\Json\Server\Response
         */
        public function handle($request = false)
        {
            if ((false !== $request) && (!$request instanceof Request)) {
                throw new Exception('Invalid request type provided; cannot handle');
            } elseif ($request) {
                $this->setRequest($request);
            }

            try
            {
                // Handle request
                $this->_handle();
            }
            catch (\Exception $exception)
            {
                var_dump($exception);
            }


            // Get response
            $response = $this->_getReadyResponse();

            // Emit response?
            if ($this->autoEmitResponse()) {
                echo $response;
                return;
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