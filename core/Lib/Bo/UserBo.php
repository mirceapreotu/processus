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
            $fbClient      = $this->getApplication()->getFacebookClient();
            $friendsIdList = $fbClient->getFriendsIdList();

            // match appUsers with friendsList from facebook
            /** @var $userManager UserManager */
            $userManager   = $this->getUserManager();
            $filterFriends = $userManager->filterAppFriends($friendsIdList);

            $mvoFriendsList = array();

            // get friends from membase || or add them
            foreach ($filterFriends as $item) {

                $mvo = new \Processus\Lib\Mvo\FacebookUserMvo();
                $mvo->setMemId($item->id);
                $data = $mvo->getFromMem();

                if (!$data) {
                    $data = $this->getApplication()->getFacebookClient()->getUserDataById($item->id);
                    $mvo->setData($data);
                    $mvo->saveInMem();
                }

                $mvoFriendsList[] = $mvo;
            }

            return $mvoFriendsList;
        }

        /**
         * @return string
         */
        public function getFacebookUserId()
        {
            return $this->getApplication()->getFacebookClient()->getUserId();
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

                    $fbClient          = $this->getApplication()->getFacebookClient();
                    $fbData            = $fbClient->getUserDataById($fbUserId);
                    $fbData['created'] = time();
                    $this->getFacebookUserMvo()->setData($fbData);
                    $this->getFacebookUserMvo()->saveInMem();

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