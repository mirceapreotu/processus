<?php

namespace Core\Abstracts
{
    use Zend\Json\Server\Server;

    /**
     *
     */
    abstract class AbstractJsonRpcServer extends Server
    {
        protected $_config;
        protected $_request;
        protected $_namespace;
        protected $_domain;
        protected $_class;


        // #########################################################


        public function __construct(Request $request, $namespace, $domain, $class)
        {
            $this->_request = $request;
            $this->_namespace = $namespace;
            $this->_domain = $domain;
            $this->_class = $class;
        }


        // #########################################################


        /**
         * @return bool
         */
        public function hasNamespace()
        {
            if (  ! empty($this->_namespace))
            {
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
            if ( ! empty($this->_config['validMethods']) && in_array($this->_request->getMethod(), $this->_config['validMethods']))
            {
                return TRUE;
            }

            return FALSE;
        }


        // #########################################################


        /**
         * @return string
         */
        public function getClassNamespace()
        {
            $namespace = array($this->_namespace);
            $namespace[] = 'Service';
            $namespace[] = $this->_class;

            return join('\\', $namespace);
        }


        // #########################################################


        /**
         * @return bool
         */
        public function isValidRequest()
        {
            if ($this->hasNamespace() && $this->isValidMethod())
            {
                return TRUE;
            }

            return FALSE;
        }


        // #########################################################


        public function run()
        {
            // if valid request let it handle via Zend\Json\Server\Server
            if ($this->isValidRequest() === TRUE)
            {
                $this->_run();
            }
        }


        // #########################################################


        protected function _run()
        {
            $server = new Server();

            // set correct request obj
            $server->_request = $this->_request;

            // set requested class
            $server->setClass($this->getClassNamespace());

            // Handle the request:
            $server->handle();
        }
    }
}

?>