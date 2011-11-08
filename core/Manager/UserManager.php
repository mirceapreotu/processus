<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Manager
{
    
    use Processus\Abstracts\Manager\ComConfig;
    
    use Processus\Application;
    
    use Processus\Abstracts\Manager\AbstractManager;

    class UserManager extends AbstractManager
    {

        /**
         * @param array $friendsList
         */
        private function insertFriends (array $friendsList)
        {
            $com = new ComConfig();
            $com->setSqlTableName("fbuser_friends")
                ->setConnector(MySQL::getInstance())
                ->setSqlParams($friendsList);
            
            $this->insert($com);
        }

        /**
         * @param string $friendsList
         * @return mixed|null
         */
        public function filterAppFriends (array $friendsList)
        {
            $friendsList = join(',', $friendsList);
            
            $com = new ComConfig();
            
            $memId = "filterAppFriends_" . $this->getApplication()
                ->getUserBo()
                ->getFacebookUserId();
            
            $com->setConnector(MySQL::getInstance())
                ->setSqlStmt("SELECT fbu.id FROM fbusers AS fbu WHERE fbu.id IN (" . $friendsList . ")")
                ->setMemId($memId);
            
            return $this->fetchAll($com);
        }

        /**
         * @param array $filteredFriends
         * @return bool
         */
        public function updateUserFriends (array $filteredFriends)
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