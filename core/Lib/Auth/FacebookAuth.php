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
    use Processus\Abstracts\JsonRpc\AbstractJsonRpcAuth;
    
    use Processus\Interfaces\InterfaceAuthModule;

    class FacebookAuth extends AbstractJsonRpcAuth implements InterfaceAuthModule
    {

        private $_isAuth;

        /**
         *@see Processus\Interfaces.InterfaceAuthModule::setAuthData()
         */
        public function setAuthData ($authData)
        {
            $this->_isAuth = $this->getApplication()
                ->getUserBo()
                ->isAuthorized();
        }

        /**
         * @see Processus\Interfaces.InterfaceAuthModule::isAuthorized()
         */
        public function isAuthorized ()
        {
            return $this->_isAuth;
        }
    }
}

