<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Vo\Configs
{
    use Processus\Lib\Vo\Configs\Facebook;

    class ProcessusConfig extends \Processus\Abstracts\Vo\AbstractVO
    {

        /**
         * @var CouchbaseConfig
         */
        private $_couchbaseConfig;

        /**
         * @var BeanstalkdConfig
         */
        private $_beanstalkdConfig;

        /**
         * @var MysqlConfig
         */
        private $_mysqlConfig;

        /**
         * @var Facebook
         */
        private $_facebookConfig;

        /**
         * @return Facebook
         */
        public function getFacebookConfig()
        {
            if(!$this->_facebookConfig)
            {
                $this->_facebookConfig = new Facebook();
                $this->_facebookConfig->setData($this->getValueByKey("Facebook"));
            }

            return $this->_facebookConfig;
        }

        /**
         * @return CouchbaseConfig
         */
        public function getCouchbaseConfig()
        {
            if (! $this->_couchbaseConfig) {
                $this->_couchbaseConfig = new CouchbaseConfig();
                $this->_couchbaseConfig->setData($this->getValueByKey("couchbaseConfig"));
            }
            return $this->_couchbaseConfig;
        }

        /**
         * @return BeanstalkdConfig
         */
        public function getBeanstalkdConfig()
        {
            if (! $this->_beanstalkdConfig) {
                $this->_beanstalkdConfig = new BeanstalkdConfig();
                $this->_beanstalkdConfig->setData($this->getValueByKey("beanstalkd"));
            }
            
            return $this->_beanstalkdConfig;
        }

        /**
         * @return MysqlConfig
         */
        public function getMysqlConfig()
        {
            if (! $this->_mysqlConfig) {
                $this->_mysqlConfig = new MysqlConfig();
                $this->_mysqlConfig->setData($this->getValueByKey("mysql"));
            }
            
            return $this->_mysqlConfig;
        }
    
    }
}
?>