<?php

namespace Processus\Abstracts\Vo
{
    
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 7/29/11
     * Time: 12:39 PM
     * To change this template use File | Settings | File Templates.
     */
    use Processus\Lib\Server\ServerFactory;

    abstract class AbstractMVO extends AbstractVO
    {

        /** @var string */
        protected $_memId;

        /** @var string */
        protected $_saltValue;

        /** @var string */
        protected $_hashAlgo;

        /** @var  */
        protected $_memcachedClient;

        /**
         * @var mixed
         */
        protected $_memDataProvider;

        /**
         * @return string
         */
        protected function getSaltValue()
        {
            return $this->_saltValue;
        }

        /**
         * @return string
         */
        protected function getHashAlgo()
        {
            return $this->_hashAlgo;
        }

        /**
         * @return string
         */
        protected function getMemId()
        {
            return $this->_memId;
        }

        /**
         * @param $mId
         * @return void
         */
        public function setMemId($mId)
        {
            $this->_memId = $mId;
        }

        /**
         * @return bool
         */
        public function saveInMem()
        {
            if (! $this->getMemId()) {
                return false;
            }
            return $this->getMemcachedClient()->set($this->getMemId(), $this->getData(), 0);
        }

        /**
         * @return bool
         */
        public function deleteFromMem()
        {
            $this->getMemcachedClient()->delete($this->getMemId());
            return true;
        }

        /**
         * @return object
         */
        public function getFromMem()
        {
            $this->_data = $this->_memDataProvider = $this->getMemcachedClient()->get($this->getMemId());
            return $this->_memDataProvider;
        }

        /**
         * @throws Exception
         * @return \Processus\Lib\Db\Memcached
         */
        public function getMemcachedClient()
        {
            if (! $this->_memcachedClient) {
                try {
                    
                    $memcachedConfig = array();
                    $memcachedConfig['port'] = $this->getDataBucketPort();
                    $memcachedConfig['host'] = $this->getMembaseHost();
                    
                    $this->_memcachedClient = ServerFactory::memcachedFactory($memcachedConfig);
                
                }
                catch (\Exception $error) {
                    
                    throw $error;
                
                }
            }
            return $this->_memcachedClient;
        }

        /**
         * @return string
         */
        protected function getMembaseHost()
        {
            return "127.0.0.1";
        }

        /**
         * @return string
         */
        protected function getDataBucketPort()
        {
            return "11280";
        }
    }
}

?>