<?php

namespace Application\JsonRpc\V1\App
{
    class Auth extends \Processus\Lib\Auth\FacebookAuth
    {
        /**
         * @var bool
         */
        private $_isAuthorized = TRUE;

        /**
         * @return bool
         */
        public function isAuthorized()
        {
            return $this->_isAuthorized;
        }
    }
}

?>