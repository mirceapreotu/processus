<?php

namespace Processus\Abstracts\JsonRpc
{

    /**
     *
     */
    abstract class AbstractJsonRpcRequest extends \Zend\Json\Server\Request\Http
    {

        /**
         * @var mixed
         */
        protected $_request;

        /**
         * @var string
         */
        protected $_namespace;

        /**
         * @var string
         */
        protected $_domain;

        /**
         * @var string
         */
        protected $_class;

        /**
         * @var string
         */
        protected $_specifiedNamespace;

        /**
         * @var mixed | array
         */
        protected $_extended;

        /**
         * @var array
         */
        protected $_validatorList = array();

        // #########################################################

        public function __construct()
        {
            parent::__construct();
            list ($this->_domain, $this->_class, $this->_method) = explode('.',
                parent::getMethod());
        }

        /**
         * @param \Processus\Interfaces\InterfaceValidator $validator
         *
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest
         */
        public function addValidator(\Processus\Interfaces\InterfaceValidator $validator)
        {
            $validator->setValidationData($this->getRawJson());
            $this->_validatorList[] = $validator;
            return $this;
        }

        /**
         * @return bool
         */
        public function isValidRequest()
        {
            /** @var $validator \Processus\Interfaces\InterfaceValidator */
            foreach ($this->_validatorList as $validator)
            {
                return $validator->isValid() == TRUE ? TRUE : FALSE;
            }

            return TRUE;
        }

        // #########################################################

        /**
         * @return string
         */
        public function getClass()
        {
            return $this->_class;
        }

        // #########################################################

        /**
         * @return string
         */
        public function getMethod()
        {
            return $this->_method;
        }

        // #########################################################


        /**
         * @return array|mixed
         */
        public function getExtended()
        {
            return $this->_extended;
        }

        // #########################################################


        /**
         * @param array $extended
         */
        public function setExtended(array $extended)
        {
            $this->_extended = $extended;
        }

        // #########################################################


        /**
         * @return mixed
         */
        public function getNamespace()
        {
            return $this->_namespace;
        }

        // #########################################################


        /**
         * @return mixed
         */
        public function getDomain()
        {
            return $this->_domain;
        }

        // #########################################################


        /**
         * @return string
         */
        public function getFunctionName()
        {
            return $this->_method;
        }

        // #########################################################


        /**
         * @return string
         */
        public function getSpecifiedServiceClassName()
        {
            return $this->getSpecifiedNamespace() . "\\Service\\" . $this->_class;
        }

        // #########################################################


        /**
         * @param $specNs string
         */
        public function setSpecifiedNamespace($specNs)
        {
            $this->_specifiedNamespace = $specNs;
        }

        /**
         * @return string
         */
        public function getSpecifiedNamespace()
        {
            return $this->_specifiedNamespace;
        }

    }
}

?>