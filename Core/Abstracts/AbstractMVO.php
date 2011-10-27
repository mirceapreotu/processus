<?php

namespace Core\Abstracts
{
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 7/29/11
     * Time: 12:39 PM
     * To change this template use File | Settings | File Templates.
     */
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
            if (!$this->getMemId()) {
                return false;
            }
            return $this->getMemcachedClient()->set($this->getMemId(),
                $this->getData(), 0);
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
            $this->_data = $this->_memDataProvider = $this->getMemcachedClient()->get(
                $this->getMemId());
            return $this->_memDataProvider;
        }

        /**
         * @throws Exception
         * @return Memcached
         */
        public function getMemcachedClient()
        {
            if (!$this->_memcachedClient) {
                try {
                    $memId = 'default';
                    $this->_memcachedClient = new Memcached($memId);
                    $this->_memcachedClient->addServer($this->getMembaseHost(),
                        $this->getDataBucketPort());
                } catch (Exception $error) {
                    $couchDoc = new stdClass();
                    $couchDoc->type = 'fatal';
                    $couchDoc->created = time();
                    $couchDoc->error = $error;
                    $this->getCouchDBLogger()
                            ->setLogType("[FATAL]" . __CLASS__ . __METHOD__)
                            ->setData($couchDoc)
                            ->writeLog();
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