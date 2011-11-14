<?php

namespace Processus
{
    use Processus\Lib\Db\MySQL;
    
    use Processus\Lib\Db\Memcached;
    
    use Processus\Lib\Server\ServerFactory;
    
    use Processus\Lib\Facebook\FacebookClient;
    
    use Processus\Lib\Bo\UserBo;
    
    use Processus\Lib\Profiler\Profiler;

    class Application
    {

        /**
         * @var Application
         */
        private static $_instance;

        /**
         * @var Profiler
         */
        private $_profiler;

        /**
         * @var Registry
         */
        private $_registry;

        /**
         * @var FacebookClient
         */
        private $_facebookClient;

        /**
         * @var UserBo
         */
        private $_userBo;

        /**
         * @var Memcached
         */
        private $_memcached;

        /**
         * @var MySQL
         */
        private $_mysql;

        // #########################################################
        

        /**
         * @return Memcached
         */
        public function getDefaultCache ()
        {
            if (! $this->_memcached) {
                
                $config = $this->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getCouchbasePortByDatabucketKey("default");
                
                $this->_memcached = ServerFactory::memcachedFactory($config['host'], $config['port']);
            }
            
            return $this->_memcached;
        
        }

        // #########################################################
        

        /**
         * @return MySQL
         */
        public function getDefaultMysql ()
        {
            if (! $this->_mysql) {
                $this->_mysql = MySQL::getInstance();
            }
            
            return $this->_mysql;
        
        }

        // #########################################################
        

        /**
         * @static
         * @return Application
         */
        public static function getInstance ()
        {
            if (self::$_instance instanceof self !== TRUE) {
                self::$_instance = new Application();
            }
            
            return self::$_instance;
        }

        // #########################################################
        

        /**
         * @return Registry
         */
        public function getRegistry ()
        {
            if (! $this->_registry) {
                $this->_registry = new Registry();
                $this->_registry->init();
            }
            return $this->_registry;
        }

        // #########################################################
        

        /**
         * @return FacebookClient
         */
        public function getFacebookClient ()
        {
            if (! $this->_facebookClient) {
                $this->_facebookClient = new FacebookClient();
            }
            return $this->_facebookClient;
        }

        // #########################################################
        

        /**
         * @return UserBo
         */
        public function getUserBo ()
        {
            if (! $this->_userBo) {
                $this->_userBo = new UserBo();
            }
            return $this->_userBo;
        }

        // #########################################################
        

        /**
         * @return Profiler
         */
        public function getProfiler ()
        {
            if (! $this->_profiler) {
                $this->_profiler = new Profiler();
            }
            
            return $this->_profiler;
        }
    }
}

?>