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
         * @return \Zend\Log\Logger
         */
        public function getDebugLogger()
        {
            if (!$this->_debugLogger) {
                $streamWriter       = new \Zend\Log\Writer\Stream(PATH_ROOT . '/logs/application/debug/zend.debug.log');
                $this->_debugLogger = new \Zend\Log\Logger($streamWriter);
            }

            return $this->_debugLogger;
        }

        /**
         * @return \Zend\Log\Logger
         */
        public function getProfilingLogger()
        {
            if (!$this->_profilingLogger) {
                $streamWriter           = new \Zend\Log\Writer\Stream(PATH_ROOT . '/logs/application/profiling/zend.profiling.log');
                $this->_profilingLogger = new \Zend\Log\Logger($streamWriter);
            }

            return $this->_profilingLogger;
        }

        /**
         * @return \Zend\Log\Logger
         */
        public function getErrorLogger()
        {
            if (!$this->_errorLogger) {
                $streamWriter       = new \Zend\Log\Writer\Stream(PATH_ROOT . '/logs/application/error/zend.error.log');
                $this->_errorLogger = new \Zend\Log\Logger($streamWriter);
            }

            return $this->_errorLogger;
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

                $this->_memcached = \Processus\Lib\Server\ServerFactory::memcachedFactory($config['host'], $config['port']);
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
         * @return \Processus\Lib\Bo\UserBo
         */
        public function getUserBo()
        {
            if (!$this->_userBo) {
                $this->_userBo = new \Processus\Lib\Bo\UserBo();
            }
            return $this->_userBo;
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