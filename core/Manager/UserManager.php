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
        
        public function getUserFriends()
        {
            $bo = Application::getInstance()->getUserBo();
            
        }

        /**
         * @param array $friendsList
         */
        public function insertFriends(array $friendsList)
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

            $sqlStmt = "SELECT fbu.id FROM fbusers AS fbu WHERE fbu.id IN (".$friendsList.")";

            $com->setConnector(\Processus\Lib\Db\MySQL::getInstance())
                ->setSqlStmt($sqlStmt);

            return $this->fetchAll($com);
        }
        
        public function updateUserFriends()
        {
            
        }
        
    }
}
?>