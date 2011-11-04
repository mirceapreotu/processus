<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Manager
{
    
    use Processus\Application;
    
    use Processus\Abstracts\Manager\AbstractManager;

    class UserManager extends AbstractManager
    {

        /**
         * @param array $friendsList
         */
        private function insertFriends(array $friendsList)
        {
            $com = new \Processus\Abstracts\Manager\ComConfig();
            $com->setSqlTableName("fbuser_friends")
                ->setSqlParams($friendsList)
                ->setConnector(\Processus\Lib\Db\MySQL::getInstance());
            
            $this->insert($com);
        }

        /**
         * @param string $friendsList
         * @return mixed|null
         */
        public function filterAppFriends(string $friendsList)
        {
            $com = new \Processus\Abstracts\Manager\ComConfig();
            
            $sqlStmt = "SELECT fbu.id FROM fbusers AS fbu WHERE fbu.id IN (" . $friendsList . ")";
            
            $com->setConnector(\Processus\Lib\Db\MySQL::getInstance())->setSqlStmt($sqlStmt)->setMemId(__METHOD__ . "_" . $this->getApplication()->getUserBo()->getFacebookUserId());
            
            return $this->fetchAll($com);
        }

        /**
         * @param array $filteredFriends
         * @return bool
         */
        public function updateUserFriends(array $filteredFriends)
        {
            $fbNum = $this->getApplication()
                ->getFacebookClient()
                ->getUserId();
            
            foreach ($filteredFriends as $item) {
                $items = array();
                
                $items['from_fbuser_id'] = $fbNum;
                $items['with_fbuser_id'] = $item->id;
                
                $this->insertFriends($items);
            }
            
            return TRUE;
        }
    
    }
}
?>