<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Facebook
{

    use Processus\Abstracts\AbstractClass;

    use Processus\Contrib\Facebook\Facebook;

    class FacebookClient extends AbstractClass
    {

        /**
         * @var Facebook
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
         * @return string
         */
        public function getLoginUrl()
        {
            return $this->getFacebookSdk()->getLoginUrl();
        }

        /**
         * @return Ambigous <\Processus\multitype:, multitype:>
         */
        protected function getFacebookClientConfig()
        {
            if (!$this->_facebookSdkConf) {
                /**  */
                $this->_facebookSdkConf = $this->getApplication()
                    ->getRegistry()
                    ->getConfig("Facebook");
            }
            return $this->_facebookSdkConf;
        }

        /**
         * @return Ambigous <\Processus\Contrib\Facebook\mixed, mixed>
         */
        public function getUserFacebookData()
        {
            if (!$this->_userFacebookData) {
                $this->_userFacebookData = $this->getFacebookSdk()->api("/me");
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
            return $this->getFacebookSdk()->getUser();
        }

        /**
         * @return \Processus\Lib\Facebook\mixed
         */
        public function getUserFriends()
        {
            $defaultCache = $this->getApplication()->getDefaultCache();
            $fbNum        = $this->getUserId();
            $memKey       = __CLASS__ . "\\" . $fbNum;

            $facebookFriends = $defaultCache->fetch($memKey);

            if (!$facebookFriends) {
                $rawData         = $this->getFacebookSdk()->api("/me/friends");
                $facebookFriends = $rawData['data'];

                $defaultCache->insert($memKey, $facebookFriends, 60 * 60 * 3);
            }

            return $facebookFriends;
        }

        /**
         * @return \Processus\Contrib\Facebook\Facebook
         */
        protected function getFacebookSdk()
        {
            if (!$this->_facebookSdk) {
                $this->_facebookSdk = new Facebook($this->getFacebookClientConfig());
            }

            return $this->_facebookSdk;
        }

        /**
         * @return multitype:unknown
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
         * @return Ambigous <\Processus\Contrib\Facebook\mixed, mixed>
         */
        public function getUserDataById(string $facebookUserId)
        {
            $userData = $this->getFacebookSdk()->api("/" . $facebookUserId);
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