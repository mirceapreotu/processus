<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Lib\Vo\Configs
{
    class ProcessusConfig extends \Processus\Abstracts\Vo\AbstractVO
    {

        /**
         * @var \Processus\Lib\Vo\Configs\CouchbaseConfig
         */
        private $_couchbaseConfig;

        /**
         * @var \Processus\Lib\Vo\Configs\BeanstalkdConfig
         */
        private $_beanstalkdConfig;

        /**
         * @var \Processus\Lib\Vo\Configs\MysqlConfig
         */
        private $_mysqlConfig;

        /**
         * @var \Processus\Lib\Vo\Configs\Facebook\Facebook
         */
        private $_facebookConfig;

        /**
         * @var \Processus\Lib\Vo\Configs\ProfilerConfig
         */
        private $_profilerConfig;

        /**
         * @var \Processus\Lib\Vo\Configs\ApplicationConfig
         */
        private $_applicationConfig;

        /**
         * @return \Processus\Lib\Vo\Configs\Facebook\Facebook
         */
        public function getFacebookConfig()
        {
            if (!$this->_facebookConfig) {
                $this->_facebookConfig = new \Processus\Lib\Vo\Configs\Facebook\Facebook();
                $this->_facebookConfig->setData($this->getValueByKey("Facebook"));
            }

            return $this->_facebookConfig;
        }

        /**
         * @return \Processus\Lib\Vo\Configs\CouchbaseConfig
         */
        public function getCouchbaseConfig()
        {
            if (!$this->_couchbaseConfig) {
                $this->_couchbaseConfig = new CouchbaseConfig();
                $this->_couchbaseConfig->setData($this->getValueByKey("couchbaseConfig"));
            }
            return $this->_couchbaseConfig;
        }

        /**
         * @return \Processus\Lib\Vo\Configs\BeanstalkdConfig
         */
        public function getBeanstalkdConfig()
        {
            if (!$this->_beanstalkdConfig) {
                $this->_beanstalkdConfig = new BeanstalkdConfig();
                $this->_beanstalkdConfig->setData($this->getValueByKey("beanstalkd"));
            }

            return $this->_beanstalkdConfig;
        }

        /**
         * @return \Processus\Lib\Vo\Configs\MysqlConfig
         */
        public function getMysqlConfig()
        {
            if (!$this->_mysqlConfig) {
                $this->_mysqlConfig = new MysqlConfig();
                $this->_mysqlConfig->setData($this->getValueByKey("mysql"));
            }

            return $this->_mysqlConfig;
        }

        /**
         * @return \Processus\Lib\Vo\Configs\ProfilerConfig
         */
        public function getProfilerConfig()
        {
            if (!$this->_profilerConfig) {
                $this->_profilerConfig = new ProfilerConfig();
                $this->_profilerConfig->setData($this->getValueByKey('Profiler'));
            }

            return $this->_profilerConfig;
        }

        /**
         * @return \Processus\Lib\Vo\Configs\ApplicationConfig
         */
        public function getApplicationConfig()
        {
            if (!$this->_applicationConfig) {
                $this->_applicationConfig = new ApplicationConfig();
                $this->_applicationConfig->setData($this->getValueByKey('application'));
            }

            return $this->_applicationConfig;
        }

        /**
         * @var \Processus\Lib\Vo\Configs\ExpiredTimeConfig
         */
        private $_expiredTimeConfig;

        /**
         * @return ExpiredTimeConfig
         */
        public function getExpiredTimeConfig()
        {
            if (!$this->_expiredTimeConfig) {
                $this->_expiredTimeConfig = new ExpiredTimeConfig();
                $this->_expiredTimeConfig->setData($this->getApplicationConfig()->getValueByKey("expiredTime"));
            }

            return $this->_expiredTimeConfig;
        }

    }
}
?>