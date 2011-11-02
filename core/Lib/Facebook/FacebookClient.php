<?php

/** 
 * @author francis
 * 
 * 
 */

namespace Processus\Lib\Facebook
{
    
    use Processus\Registry;
    
    use Processus\Contrib\Facebook\Facebook;

    class FacebookClient
    {

        /**
         * @var Facebook
         */
        private static $_instance;

        /**
         * @var Facebook
         */
        private $_facebookSdk;

        /**
         * @return Ambigous <\Processus\multitype:, multitype:>
         */
        private $_facebookSdkConf;

        private $_userFacebookData;

        /**
         * @return \Processus\Lib\Facebook\Facebook
         */
        public static function getInstance()
        {
            if (! self::$_instance) {
                self::$_instance = new FacebookClient();
            }
            
            return self::$_instance;
        }

        /**
         * @return Ambigous <\Processus\multitype:, multitype:>
         */
        protected function getFacebookClientConfig()
        {
            if (! $this->_facebookSdkConf) {
                /**  */
                $this->_facebookSdkConf = Registry::getInstance()->getConfig("Facebook");
            }
            return $this->_facebookSdkConf;
        }

        /**
         * @return Ambigous <\Processus\Contrib\Facebook\mixed, mixed>
         */
        public function getUserFacebookData()
        {
            if (! $this->_userFacebookData) {
                $this->_userFacebookData = $this->getFacebookSdk()->api("/me");
            }
            
            return $this->_userFacebookData;
        }
        
        /**
         * @return string
         */
        public function isUserAuthorizedOnFacebook()
        {
            return $this->getFacebookSdk()->getAccessToken();
        }
        
        /**
         * @return string
         */
        public function getUserId()
        {
            return $this->_facebookSdk->getUser();
        }

        /**
         * @return \Processus\Contrib\Facebook\Facebook
         */
        protected function getFacebookSdk()
        {
            if (! $this->_facebookSdk) {
                $this->_facebookSdk = new Facebook($this->getFacebookClientConfig());
            }
            
            return $this->_facebookSdk;
        }
    
    }
}
?>