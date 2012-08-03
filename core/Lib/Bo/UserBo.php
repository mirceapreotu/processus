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
         * @var int
         */
        private $_mysqlId;

        /**
         * @return int
         */
        public function getMysqlId()
        {
            if (!$this->_mysqlId) {
                $sqlStmt   = "SELECT id FROM users WHERE fb_id = :fb_id";
                $sqlParams = array(
                    "fb_id" => $this->getFacebookUserId()
                );

                $this->_mysqlId = \Processus\Lib\Db\MySQL::getInstance()->fetchValue($sqlStmt, $sqlParams);
            }

            return $this->_mysqlId;
        }

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
            $friendsIdList = $this->getProcessusContext()->getFacebookClient()->getFriendsIdList();

            if (count($friendsIdList) <= 0) {
                return FALSE;
            }

            // match appUsers with friendsList from facebook
            /** @var $userManager \Processus\Manager\UserManager */
            $userManager   = $this->getUserManager();
            $filterFriends = $userManager->filterAppFriends($friendsIdList);

            $mvoFriendsList = array();

            $connector = $this->getProcessusContext()->getDefaultCache();

            $idList = prosc_array_prefixing("FacebookUserMvo_", $filterFriends);

            // improvement get keys
            $friendsCollections = $connector->getMultipleByKey($idList);

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
         * @return bool|string
         */
        public function isAuthorized()
        {

            $isInMySql = $this->_isInMySqlTable();

            if (count($isInMySql) == 1)
            {
                return TRUE;
            }

            $fbUserId = $this->getFacebookUserId();

            if ($fbUserId > 0)
            {

                $mvo      = $this->getFacebookUserMvo();
                $userData = $mvo->getData();

                $fbClient          = $this->getProcessusContext()->getFacebookClient();
                $fbData            = $fbClient->getUserDataById($fbUserId);
                $fbData['created'] = convertUnixTimeToIso(time());

                $resultCode = $mvo->setData($fbData)->saveInMem();
                $this->getUserManager()->insertNewUser($mvo);

                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }

        /**
         * @return array
         */
        protected function _isInMySqlTable()
        {
            $mysql     = $this->getProcessusContext()->getMasterMySql();
            $sqlStmt   = "SELECT id, fb_id FROM users WHERE fb_id=:fb_id";
            $sqlParams = array(
                "fb_id" => $this->getProcessusContext()->getUserBo()->getFacebookUserId(),
            );

            $userData = $mysql->fetchAll($sqlStmt, $sqlParams);
            return $userData;
        }
    }
}

?>