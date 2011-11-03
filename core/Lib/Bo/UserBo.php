<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Bo
{
    
    use Processus\Manager\UserManager;
    
    use Processus\Lib\Mvo\FacebookUserMvo;
    
    use Processus\Lib\Mvo\UserMvo;

    class UserBo
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
            if (! $this->_userMvo) {
                $this->_userMvo = new FacebookUserMvo();
            }
            
            return $this->_userMvo;
        }

        /**
         * @return UserManager
         */
        public function getUserManager()
        {
            if (! $this->_userManager) {
                $this->_userManager = new UserManager();
            }
            
            return $this->_userManager;
        }
        
        /**
         * @return array | mixed
         */
        public function getUserFriends()
        {
            return $this->getUserManager()->getUserFriends();
        }

    }
}
?>