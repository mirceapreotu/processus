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
        
        public function updateUserFriends()
        {
            
        }
        
    }
}
?>