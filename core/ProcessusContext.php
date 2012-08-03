<?php

namespace Processus
{
    class ProcessusContext implements \Processus\Interfaces\InterfaceApplicationContext
    {
        /**
         * @var \Processus\Lib\Profiler\ProcessusProfiler
         */
        private $_profiler;

        /**
         * @var \Processus\ProcessusRegistry
         */
        private $_registry;

        /**
         * @var \Processus\Lib\Facebook\FacebookClient
         */
        private $_facebookClient;

        /**
         * @var \Processus\Lib\Bo\UserBo
         */
        private $_userBo;

        /**
         * @var \Processus\Lib\Db\Memcached
         */
        private $_memcached;

        /**
         * @var \Processus\Lib\Db\MySQL
         */
        private $_mysql;

        /**
         * @var \Processus\ProcessusContext
         */
        private static $_instance;

        /**
         * @var \Zend\Log\Logger
         */
        private $_errorLogger;

        /**
         * @var \Zend\Log\Logger
         */
        private $_debugLogger;

        /**
         * @var \Zend\Log\Logger
         */
        private $_profilingLogger;

        /**
         * @var \Processus\ProcessusBootstrap
         */
        private $_bootstrap;

        /**
         * @static
         * @return \Processus\ProcessusContext
         */
        public static function getInstance()
        {
            if (!self::$_instance) {
                self::$_instance = new ProcessusContext();
            }

            return self::$_instance;
        }

        /**
         * @param \Processus\ProcessusBootstrap $bootstrap
         *
         * @return \Processus\ProcessusContext
         */
        public function setBootstrap(ProcessusBootstrap $bootstrap)
        {
            $this->_bootstrap = $bootstrap;
            return $this;
        }

        /**
         * @return \Processus\ProcessusBootstrap
         */
        public function getBootstrap()
        {
            return $this->_bootstrap;
        }

        /**
         * @return Lib\Db\Memcached
         */
        public function getDefaultCache()
        {
            if (!$this->_memcached) {

                $config = $this->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getCouchbasePortByDatabucketKey("default");

                $this->_memcached = \Processus\Lib\Server\ServerFactory::memcachedFactory(
                    $config['host'], $config['port']
                );
            }

            return $this->_memcached;

        }

        /**
         * @return \Processus\Lib\Db\MySQL
         */
        public function getMasterMySql()
        {
            if (!$this->_mysql) {
                $this->_mysql = \Processus\Lib\Db\MySQL::getInstance();
            }

            return $this->_mysql;

        }

        /**
         * @return ProcessusRegistry
         */
        public function getRegistry()
        {
            if (!$this->_registry) {
                $this->_registry = new ProcessusRegistry();
                $this->_registry->init();
            }
            return $this->_registry;
        }

        /**
         * @param ProcessusRegistry $registry
         * @return ProcessusContext
         */
        public function setRegistry(ProcessusRegistry $registry)
        {
            $this->_registry = $registry;
            return $this;
        }

        /**
         * @return Lib\Facebook\FacebookClient
         */
        public function getFacebookClient()
        {
            if (!$this->_facebookClient) {
                $this->_facebookClient = new \Processus\Lib\Facebook\FacebookClient();
            }
            return $this->_facebookClient;
        }

        // #########################################################

        /**
         * @return Lib\Bo\UserBo
         */
        public function getUserBo()
        {
            if (!$this->_userBo) {
                $this->_userBo = new \Processus\Lib\Bo\UserBo();
            }
            return $this->_userBo;
        }

        /**
         * @param Lib\Bo\UserBo $bo
         *
         * @return ProcessusContext
         */
        public function setUserBo(\Processus\Lib\Bo\UserBo $bo)
        {
            $this->_userBo = $bo;
            return $this;
        }

        // #########################################################

        /**
         * @return \Processus\Lib\Profiler\ProcessusProfiler
         */
        public function getProfiler()
        {
            if (!$this->_profiler) {
                $this->_profiler = new \Processus\Lib\Profiler\ProcessusProfiler();
            }

            return $this->_profiler;
        }
    }
}

?>