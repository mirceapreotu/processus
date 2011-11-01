<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/31/11
     * Time: 7:31 PM
     * To change this template use File | Settings | File Templates.
     */
    namespace Application\JsonRpc\V1\Pub
    {
        use Processus\Interfaces\InterfaceAuthModule;

        class Auth implements InterfaceAuthModule
        {

            private $_isAuthorized = FALSE;

            /**
             * @param $authData \Application\JsonRpc\V1\Pub\Request
             */
            public function setAuthData($authData)
            {
                $this->_isAuthorized = TRUE;
            }

            /**
             * @return bool
             */
            public function isAuthorized()
            {
                return $this->_isAuthorized;
            }
        }
    }
