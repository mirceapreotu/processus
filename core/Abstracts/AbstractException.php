<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/17/11
 * Time: 4:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Processus\Abstracts
{
    class AbstractException extends \ErrorException
    {

        private $_errorData = array(
            'userMessage' => 'Something went wrong! Please reload the site!'
        );

        /**
         * @param object $extended
         *
         * @return AbstractException
         */
        public function setExtendData(\object $extended)
        {
            $this->_errorData['extended'] = $extended;
            return $this;
        }

        /**
         * @param string $method
         *
         * @return AbstractException
         */
        public function setMethod(\string $method)
        {
            $this->_errorData['method'] = $method;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getMethod()
        {
            return $this->_errorData['method'];
        }

        /**
         * @param string $class
         *
         * @return AbstractException
         */
        public function setClass(\string $class)
        {
            $this->_errorData['class'] = $class;
            return $this;
        }

        /**
         * @param string $message
         *
         * @return AbstractException
         */
        public function setUserMessage(\string $message)
        {
            $this->_errorData['userMessage'] = $message;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getUserMessage()
        {
            return $this->_errorData['userMessage'];
        }
    }
}