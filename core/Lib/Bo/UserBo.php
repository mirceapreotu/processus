<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Lib\Bo
{
    class UserBo extends \Processus\Abstracts\AbstractClass
    {

        /**
         * @var \Processus\Lib\Mvo\FacebookUserMvo
         */
        private $_userMvo;

        /**
         * @var \Processus\Manager\UserManager
         */
        private $_userManager;

        /**
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function getFacebookUserMvo()
        {
            if (!$this->_userMvo) {
                $this->_userMvo = new \Processus\Lib\Mvo\FacebookUserMvo();
                $this->_userMvo->setMemId($this->getFacebookUserId());
                $this->_userMvo->getFromMem();
            }

            return $this->_userMvo;
        }

        /**
         * @return \Processus\Manager\UserManager
         */
        public function getUserManager()
        {
            if (!$this->_userManager) {
                $this->_userManager = new \Processus\Manager\UserManager();
            }

            return $this->_userManager;
        }

        /**
         * @return array
         */
        public function getAppFriends()
        {
            // get friends from facebook
            $fbClient      = $this->getProcessusContext()->getFacebookClient();
            $friendsIdList = $fbClient->getFriendsIdList();

            // match appUsers with friendsList from facebook
            /** @var $userManager UserManager */
            $userManager   = $this->getUserManager();
            $filterFriends = $userManager->filterAppFriends($friendsIdList);

            $mvoFriendsList = array();

            $connector = $this->getProcessusContext()->getDefaultCache();

            $idList = prosc_array_prefixing("FacebookUserMvo_", $filterFriends);

            // improvement get keys
            $friendsCollections = $connector->getMulipleByKey($idList);

            // get friends from membase || or add them
            foreach ($friendsCollections as $item)
            {
                $mvo = new \Processus\Lib\Mvo\FacebookUserMvo();
                $mvo->setData($item);

                $mvoFriendsList[] = $mvo;
            }

            return $mvoFriendsList;
        }

        /**
         * @return string
         */
        public function getFacebookUserId()
        {
            return $this->getProcessusContext()->getFacebookClient()->getUserId();
        }

        /**
         * @return int
         */
        public function getFacebookHighScore()
        {
            return 1;
        }

        /**
         * @return boolean
         */
        public function isAuthorized()
        {
            $fbUserId = $this->getFacebookUserId();

            if ($fbUserId) {

                $userData = $this->getFacebookUserMvo()->getData();

                if (!$userData) {

                    $fbClient          = $this->getProcessusContext()->getFacebookClient();
                    $fbData            = $fbClient->getUserDataById($fbUserId);
                    $fbData['created'] = time();
                    $this->getFacebookUserMvo()->setData($fbData)
                        ->setMemId($this->getFacebookUserId())
                        ->saveInMem();

                    $this->getUserManager()->insertNewUser($this->getFacebookUserMvo());
                }

                return TRUE;
            }
            else {
                return FALSE;
            }
        }
    }
}
?>