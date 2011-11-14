<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Vo\Configs
{
    
    use Processus\Abstracts\Vo\AbstractVO;

    class ProcessusConfig extends AbstractVO
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