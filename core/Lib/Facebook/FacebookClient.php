<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Facebook
{
    class FacebookClient extends \Processus\Abstracts\AbstractClass
    {

        /**
         * @var \Processus\Contrib\Facebook\Facebook
         */
        private $_facebookSdk;

        /**
         * @return Ambigous <\Processus\multitype:, multitype:>
         */
        private $_facebookSdkConf;

        /**
         * @var mixed | array
         */
        private $_userFacebookData;

        /**
         * @var mixed | array
         */
        private $_facebookFriends;

        /**
         * @var \Processus\Lib\Facebook\Api\OpenGraph
         */
        private $_openGraphClient;

        /**
         * @var string
         */
        private $_userId;

        /**
         * @return string
         */
        public function getLoginUrl()
        {
            return $this->getFacebookSdk()->getLoginUrl();
        }

        /**
         * @return mixed
         */
        public function getAppId()
        {
            $fbConfig = $this->getFacebookClientConfig();
            return $fbConfig['appId'];
        }

        /**
         * @return mixed
         */
        protected function getFacebookClientConfig()
        {
            if (!$this->_facebookSdkConf) {
                /**  */
                $this->_facebookSdkConf = $this->getProcessusContext()
                    ->getRegistry()
                    ->getConfig("Facebook");
            }
            return $this->_facebookSdkConf;
        }

        /**
         * @return array|mixed
         */
        public function getUserFacebookData()
        {
            if (!$this->_userFacebookData) {

                try {

                    $this->_userFacebookData = $this->getFacebookSdk()->api("/me");

                }
                catch (\Exception $error) {
                    throw $error;
                }
            }

            return $this->_userFacebookData;
        }

        /**
         * @return string
         */
        public function isUserAuthorizedOnFacebook()
        {
            return $this->getFacebookSdk()->getAccessToken();
        }

        /**
         * @return string
         */
        public function getUserId()
        {
            if (!$this->_userId) {
                $this->_userId = $this->getFacebookSdk()->getUser();
            }
            return $this->_userId;
        }

        /**
         * @param null $userFbId
         *
         * @return mixed
         */
        public function getUserFriends($userFbId = null)
        {
            $defaultCache    = $this->getProcessusContext()->getDefaultCache();
            $fbNum           = (int)$userFbId > 0 ? $userFbId : $this->getUserId();

            $memKey          = "FacebookClient_getUserFriends_" . $fbNum;
            $facebookFriends = $defaultCache->fetch($memKey);

            if (!$facebookFriends && is_null($userFbId)) {
                $rawData         = $this->getFacebookSdk()->api("/me/friends");
                $facebookFriends = $rawData['data'];

                $defaultCache->insert($memKey, $facebookFriends, 60 * 60 * 3);

                // update users_relations table with his friends
                $managerUserRelations = new \Application\Manager\UsersRelations\UsersRelationsManager();
                $managerUserRelations->updateUserFriends();
            }

            return $facebookFriends;
        }

        /**
         * @return \Processus\Contrib\Facebook\Facebook
         */
        public function getFacebookSdk()
        {
            if (!$this->_facebookSdk) {
                $this->_facebookSdk = new \Processus\Contrib\Facebook\Facebook($this->getFacebookClientConfig()->toArray());
            }

            return $this->_facebookSdk;
        }

        /**
         * @return array
         */
        public function getFriendsIdList()
        {
            $friendsList = $this->getUserFriends();

            $idList = array();

            foreach ($friendsList as $item) {
                $idList[] = $item['id'];
            }

            return $idList;
        }

        /**
         * @param string $facebookUserId
         *
         * @return array|mixed
         */
        public function getUserDataById(string $facebookUserId)
        {
            try {
                $userData = $this->getFacebookSdk()->api("/" . $facebookUserId);
            }
            catch (\Exception $error) {
                throw $error;
            }

            return $userData;
        }

        /**
         * @return \Processus\Lib\Facebook\Api\OpenGraph
         */
        public function getOpenGraphClient()
        {
            if ($this->_openGraphClient) {
                $this->_openGraphClient = new \Processus\Lib\Facebook\Api\OpenGraph();
            }

            return $this->_openGraphClient;
        }
    }
}
?>