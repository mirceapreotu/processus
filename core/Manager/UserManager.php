<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Manager
{

    use Processus\Lib\Db\MySQL;

    use Processus\Abstracts\Manager\ComConfig;

    use Processus\Application;

    use Processus\Abstracts\Manager\AbstractManager;

    use Processus\Interfaces\InterfaceUser;

    class UserManager extends AbstractManager
    {

        /**
         * @param \Processus\Interfaces\InterfaceUser $user
         */
        public function insertNewUser(InterfaceUser $user)
        {
            $com = new ComConfig();

            $com->setConnector($this->getApplication()->getMasterMySql())
                ->setSqlTableName("fbusers")
                ->setSqlParams(array("id" => $user->getId(), "created" => $user->getCreated()));

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

            $com = new ComConfig();

            $memId = "filterAppFriends_" . $this->getApplication()
                ->getUserBo()
                ->getFacebookUserId();

            $com->setConnector(MySQL::getInstance())
                ->setSqlStmt("SELECT fbu.id AS id FROM fbusers AS fbu WHERE fbu.id IN (" . $friendsList . ")")
                ->setMemId($memId);

            return $this->fetchAll($com);
        }

        /**
         * @param array $friendsList
         */
        private function insertFriends(array $friendsList)
        {
            $com = new ComConfig();
            $com->setConnector(MySQL::getInstance())
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
            $fbuserId = $this->getApplication()
                ->getFacebookClient()
                ->getUserId();

            foreach ($filteredFriends as $item) {
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