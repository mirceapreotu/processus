<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/1/11
 * Time: 2:27 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Auth
{
    class FacebookAuth extends \Processus\Abstracts\JsonRpc\AbstractJsonRpcAuth implements \Processus\Interfaces\InterfaceAuthModule
    {

        private $_isAuth;

        /**
         * @param $authData
         *
         * @return bool|string
         */
        public function setAuthData($authData)
        {
            return $this->_isAuth = $this->getProcessusContext()
                ->getUserBo()
                ->isAuthorized();
        }

        /**
         * @return mixed
         */
        public function isAuthorized()
        {
            return $this->_isAuth;
        }
    }
}

