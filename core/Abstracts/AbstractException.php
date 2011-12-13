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
            'userMessage'      => 'Something went wrong! Please reload the site!',
            'userMessageTitle' => 'Error',
            'userErrorCode'    => '1900',
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
         * @return mixed
         */
        public function getExtendData()
        {
            return $this->_errorData['extended'];
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

        /**
         * @param string $title
         *
         * @return AbstractException
         */
        public function setUserMessageTitle(\string $title)
        {
            $this->_errorData["userMessageTitle"] = $title;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getUserMessageTitle()
        {
            return $this->_errorData["userMessageTitle"];
        }

        /**
         * @param string $errorCode
         *
         * @return AbstractException
         */
        public function setUserErrorCode(\string $errorCode)
        {
            $this->_errorData['userErrorCode'] = $errorCode;
            return $this;
        }


        /**
         * @return mixed
         */
        public function getUserErrorCode()
        {
            return $this->_errorData['userErrorCode'];
        }

        /**
         * @param string $details
         *
         * @return AbstractException
         */
        public function setUserDetailError(\string $details)
        {
            $this->_errorData['userErrorDetail'] = $details;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getUserDetailError()
        {
            return $this->_errorData['userErrorDetail'];
        }

        /**
         * @param string $message
         *
         * @return AbstractException
         */
        public function setMessage(\string $message)
        {
            $this->message = $message;
            return $this;
        }

        /**
         * @param string $message
         * @param int    $code
         * @param int    $severity
         * @param string $filename
         * @param int    $lineno
         * @param array  $previous
         */
        public function __construct($message = "", $code = 1000, $severity = 10, $filename = __FILE__, $lineno = __LINE__, $previous = array())
        {
            $previous = error_get_last();
            parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
        }
    }
}