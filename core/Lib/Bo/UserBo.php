<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Lib\Bo
{

    use Processus\Abstracts\AbstractClass;

    use Processus\Manager\UserManager;

    use Processus\Lib\Mvo\FacebookUserMvo;

    use Processus\Lib\Mvo\UserMvo;

    class UserBo extends AbstractClass
    {

        /**
         * @var FacebookUserMvo
         */
        private $_userMvo;

        /**
         * @var UserManager
         */
        private $_userManager;

        /**
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function getFacebookUserMvo()
        {
            if (!$this->_userMvo) {
                $this->_userMvo = new FacebookUserMvo();
                $this->_userMvo->setMemId($this->getApplication()
                        ->getFacebookClient()
                        ->getUserId());

                $this->_userMvo->getFromMem();
            }

            return $this->_userMvo;
        }

        /**
         * @return UserManager
         */
        public function getUserManager()
        {
            if (!$this->_userManager) {
                $this->_userManager = new UserManager();
            }

            return $this->_userManager;
        }

        /**
         * @return multitype:Ambigous <multitype:, multitype:NULL >
         */
        public function getAppFriends()
        {
            // get friends from facebook
            $fbClient = $this->getApplication()->getFacebookClient();
            $friendsIdList = $fbClient->getFriendsIdList();

            // match appUsers with friendsList from facebook
            /** @var $userManager UserManager */
            $userManager = $this->getUserManager();
            $filterFriends = $userManager->filterAppFriends($friendsIdList);

            $mvoFriendsList = array();

            // get friends from membase || or add them
            foreach ($filterFriends as $item) {

                $mvo = new FacebookUserMvo();
                $mvo->setMemId($item->id);
                $data = $mvo->getFromMem();

                if (!$data) {
                    $data = $this->getApplication()
                            ->getFacebookClient()
                            ->getUserDataById($item->id);

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
            return $this->getApplication()
                    ->getFacebookClient()
                    ->getUserId();
        }

        /**
         * @return boolean
         */
        public function isAuthorized()
        {
            $fbClient = $this->getApplication()->getFacebookClient();
            $fbUserId = $this->getFacebookUserId();

            if ($fbUserId) {
                $userData = $this->getFacebookUserMvo()->getData();

                if (!$userData) {

                    $fbData = $fbClient->getUserDataById($fbUserId);
                    $this->getFacebookUserMvo()->setData($fbData);
                    $this->getFacebookUserMvo()->saveInMem();

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