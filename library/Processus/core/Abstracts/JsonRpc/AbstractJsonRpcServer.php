<?php

    namespace Processus\Abstracts\JsonRpc
    {

        use Zend\Json\Server\Server;

        /**
         *
         */
        abstract class AbstractJsonRpcServer extends Server
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
            public function isValidMethod()
            {
                if (!empty($this->_config['validMethods']) &&
                        in_array($this->_request->getMethod(),
                            $this->_config['validMethods'])
                ) {
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
                if ($this->hasNamespace() && $this->isValidMethod()) {
                    return TRUE;
                }

                return FALSE;
            }

            // #########################################################

            /**
             * public run method
             */
            public function run()
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
            protected function _run()
            {
                // set class
                $this->setClass($this->getRequest()->getSpecifiedServiceClassName());
                // Handle the request:
                $this->handle();
            }

            /**
             * @return \Core\Abstracts\JsonRpc\AbstractJsonRpcRequest
             */
            public function getRequest()
            {
                return parent::getRequest();
            }
        }
    }

?>