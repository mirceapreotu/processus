<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/31/11
     * Time: 7:31 PM
     * To change this template use File | Settings | File Templates.
     */
    namespace Application\JsonRpc\V1\Admin
    {
        use Processus\Interfaces\InterfaceAuthModule;
        use Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest;
        use Processus\Lib\Auth\LowAuth;

        class Auth extends LowAuth
        {

            private $_isAuthorized = FALSE;

            /**
             * @var AbstractJsonRpcRequest
             */
            private $_authData;

            /**
             * @param \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest $authData
             * @return mixed
             */
            public function setAuthData($authData)
            {
                $this->_authData = $authData->getExtended();

                if($this->getAuthId() === FALSE)
                {
                    $this->_isAuthorized = FALSE;
                    return;
                }

                $sqlStmt = "SELECT * FROM users WHERE id=" . $this->_authData['id'];
                /** @var $mysql \Processus\Lib\Db\MySQL */
                $mysql = \Processus\Lib\Db\MySQL::getInstance();
                $user = $mysql->fetchOne($sqlStmt);

                if((boolean)$user->deauthorized === TRUE)
                {
                    $this->_isAuthorized = FALSE;
                    return;
                }

                $this->_isAuthorized = TRUE;
            }

            /**
             * @return bool|string
             */
            private function getAuthId()
            {
                if (!array_key_exists('id', $this->_authData))
                {
                    return FALSE;
                }
                if (!array_key_exists('fb', $this->_authData))
                {
                    return FALSE;
                }

                return (string)md5($this->_authData['fb'] . $this->_authData['id'] . 'CrowdPark4Ever!');
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
