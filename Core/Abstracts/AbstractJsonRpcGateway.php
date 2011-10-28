<?php

namespace Core\Abstracts
{
    use Zend\Json\Server\Server;

    /**
     *
     */
    abstract class AbstractJsonRpcGateway extends Server
    {
        protected $_config;
        protected $_domain;
        protected $_class;
        protected $_method;


        // #########################################################


        /**
         *
         */
        public function getRequest()
        {
            parent::getRequest();
            list($this->_domain, $this->_class, $this->_method) = explode('.', $this->_request->getMethod());
            $this->_request->setMethod($this->_method);
        }


        // #########################################################


        /**
         * @return bool
         */
        public function isEnabled()
        {
            if ($this->_config['enabled'] === TRUE)
            {
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
            if ( ! empty($this->_config['namespace']))
            {
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
            if ( ! empty($this->_config['validDomains']) && in_array($this->_domain, $this->_config['validDomains']))
            {
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
            if ($this->isEnabled() && $this->hasNamespace() && $this->isValidDomain())
            {
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
            $server = $this->_config['namespace'] . '\\' . 'Server';
            return new $server($this->_request, $this->_config['namespace'], $this->_domain, $this->_class);
        }


        // #########################################################


        /**
         *
         */
        public function run()
        {
            // get request details
            $this->getRequest();

            // if valid request run server
            if ($this->isValidRequest() === TRUE)
            {
                $this->_run();
            }
        }


        // #########################################################


        /**
         * @return bool
         */
        protected function _run()
        {
            $server = $this->getServer();
            $server->run();
        }
    }
}

?>