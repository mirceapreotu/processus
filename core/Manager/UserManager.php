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
            $pdo = $this->insert($this->ccFactory()
                    ->setSqlTableName("users")
                    ->setSqlParams(array("fb_id"     => $user->getId(),
                                        "created"    => time()
                                   )
                )
            );
            return $pdo;
        }


        /**
         * @param array $friendsList
         *
         * @return mixed|null
         */
        public function filterAppFriends(array $friendsList)
        {
            $friendsList = join(',', $friendsList);

            $memId = "FilterAppFriends_" . $this->getProcessusContext()
                ->getUserBo()
                ->getFacebookUserId();

            return $this->fetchAll($this->ccFactory()
                    ->setSqlStmt("SELECT fb_id AS id FROM users WHERE fb_id IN (" . $friendsList . ")")
                    ->setMemId($memId)
                    ->setFromCache(FALSE)
            );
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