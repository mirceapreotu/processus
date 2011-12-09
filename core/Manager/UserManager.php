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
         */
        public function insertNewUser(\Processus\Interfaces\InterfaceUser $user)
        {
            $com = new \Processus\Abstracts\Manager\ComConfig();

            $com->setConnector($this->getProcessusContext()->getMasterMySql())
                ->setSqlTableName("fbusers")
                ->setSqlParams(array("id"     => $user->getId(),
                                    "created" => $user->getCreated()));


            $feedVo = new \Application\Vo\Feed\BaseFeedVo();
            $feedVo->setValueByKey('fbUserId', $user->getId());
            $manager = new \Application\Manager\Feed\NewUser();
            $manager->setFeedItemData($feedVo);

            $this->insert($com);
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

            $memId = "filterAppFriends_" . $this->getProcessusContext()
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