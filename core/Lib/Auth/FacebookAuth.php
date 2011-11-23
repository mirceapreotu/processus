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
         */
        public function setAuthData ($authData)
        {
            $this->_isAuth = $this->getApplication()
                ->getUserBo()
                ->isAuthorized();
        }

        /**
         * @return mixed
         */
        public function isAuthorized ()
        {
            return $this->_isAuth;
        }
    }
}

