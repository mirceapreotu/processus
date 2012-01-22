<?php

namespace Processus\Abstracts\Vo {
    use \string;

    /**
     * User: francis
     * Date: 7/29/11
     * Time: 12:39 PM
     * To change this template use File | Settings | File Templates.
     */
    abstract class AbstractMVO extends AbstractVO
    {

        /**
         * @var string
         */
        protected $_memId;

        /**
         * @var string
         */
        protected $_saltValue;

        /**
         * @var string
         */
        protected $_hashAlgo;

        /**
         * @var Memcached
         */
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
        public function getMemId()
        {
            return $this->_memId;
        }

        /**
         * @return int
         */
        public function valueSize()
        {
            return sizeof($this->_data);
        }

        /**
         * @param string $mId
         *
         * @return AbstractMVO
         */
        public function setMemId(string $mId)
        {
            $this->_memId = $mId;
            return $this;
        }

        /**
         * @return int
         *
         * @throws \Processus\Exceptions\MvoException
         */
        public function saveInMem()
        {
            if (!$this->getMemId()) {

                $errorData            = array();
                $errorData['message'] = "Mvo has no Id ";
                $errorData['stack']   = debug_backtrace();

                $mvoException = new \Processus\Exceptions\MvoException();
                $mvoException->setClass(__CLASS__)
                    ->setMessage(json_encode($errorData))
                    ->setMethod(__METHOD__)
                    ->setExtendData(json_encode($this->getData()));

                throw $mvoException;
            }

            $resultCode = $this->getMemcachedClient()->insert($this->getMemId(), $this->getData(), $this->getExpiredTime());
            $this->_checkResultCode($resultCode);
            return $resultCode;
        }

        /**
         * @param int $resultCode
         *
         * @todo checking more result code from memcached and throw exception is something wrong
         */
        private function _checkResultCode(\int $resultCode)
        {
            switch ($resultCode)
            {
                case \Memcached::RES_BAD_KEY_PROVIDED:
                    break;
                case \Memcached::RES_FAILURE:
                    break;
                case \Memcached::RES_WRITE_FAILURE:
                    break;
                default:
                    break;
            }
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
            $this->_data = $this->getMemcachedClient()->fetch($this->getMemId());
            return $this->_data;
        }

        /**
         * @throws Exception
         * @return \Processus\Lib\Db\Memcached
         */
        public function getMemcachedClient()
        {
            if (!$this->_memcachedClient) {
                try {

                    $this->_memcachedClient = \Processus\Lib\Server\ServerFactory::memcachedFactory($this->getMembaseHost(), $this->getDataBucketPort());

                }
                catch (\Exception $error) {

                    throw $error;

                }
            }
            return $this->_memcachedClient;
        }

        /**
         * @return int
         */
        protected function getExpiredTime()
        {
            return 0;
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
            return "11211";
        }

        /**
         * @param \Processus\Interfaces\InterfaceDatabase $memcached
         *
         * @return \Processus\Abstracts\Vo\AbstractMVO
         */
        public function setAdapter(\Processus\Interfaces\InterfaceDatabase $memcached)
        {
            $this->_memcachedClient = $memcached;
            return $this;
        }
    }
}

?>