<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Manager
{
    class UserManager extends \Processus\Abstracts\Manager\AbstractManager
    {

        /**
         * @param \Processus\Interfaces\InterfaceUser $user
         *
         * @return \Zend\Db\Statement\Pdo
         */
        public function insertNewUser(\Processus\Interfaces\InterfaceUser $user)
        {
            $com = new \Processus\Abstracts\Manager\ComConfig();
            $com->setConnector(\Processus\Lib\Db\MySQL::getInstance())
                ->setSqlTableName("fbusers")
                ->setSqlParams(array("fb_id"     => $user->getId(),
                                    "created"    => time()
                               ));

            $feedVo = new \Application\Vo\Feed\BaseFeedVo();
            $feedVo->setValueByKey('fbUserId', $user->getId());

            $manager = new \Application\Manager\Feed\NewUser();
            $manager->setFeedItemData($feedVo);

            $leaderboardManager = new \Application\Manager\Leaderboard\AddNewUser();
            $leaderboardManager->addUser();

            return $this->insert($com);
        }


        /**
         * @param array $friendsList
         *
         * @return array|string
         */
        public function filterAppFriends(array $friendsList)
        {
            $friendsList = join(',', $friendsList);

            $com = new \Processus\Abstracts\Manager\ComConfig();

            $memId = "FilterAppFriends_" . $this->getProcessusContext()
                ->getUserBo()
                ->getFacebookUserId();


            $com->setConnector(\Processus\Lib\Db\MySQL::getInstance())
                ->setSqlStmt("SELECT fbu.id AS id FROM fbusers AS fbu WHERE fbu.id IN (" . $friendsList . ")")
                ->setMemId($memId);

            return $this->fetchAll($com);
        }

        /**
         * @param array $friendsList
         */
        private function insertFriends(array $friendsList)
        {
            $com = new \Processus\Abstracts\Manager\ComConfig();
            $com->setConnector(\Processus\Lib\Db\MySQL::getInstance())
                ->setSqlTableName("fbuser_friends")
                ->setSqlParams($friendsList);

            $this->insert($com);
        }

        /**
         * @param array $filteredFriends
         *
         * @return bool
         */
        public function updateUserFriends(array $filteredFriends)
        {
            $fbuserId = $this->getProcessusContext()
                ->getFacebookClient()
                ->getUserId();

            foreach ($filteredFriends as $item)
            {
                $items = array();

                $items['from_fbuser_id'] = $fbuserId;
                $items['with_fbuser_id'] = $item->id;

                $this->insertFriends($items);
            }

            return TRUE;
        }

    }
}
?>