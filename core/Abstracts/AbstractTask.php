<?php

namespace Processus\Abstracts
{
    abstract class AbstractTask extends \Processus\Abstracts\Manager\AbstractManager
    {
        abstract public function run();

        /**
         * @var \Pheanstalk\Pheanstalk
         */
        private $_pheanstalk;

        /**
         * @return int
         */
        public function storeInQueue()
        {
            return $this->getPheanstalk()->useTube($this->getTube())->put(serialize($this));
        }

        /**
         * @return bool
         */
        protected function _saveInQueue()
        {
            return TRUE;
        }

        /**
         * @return object
         */
        protected function _getStats()
        {
            return $this->getPheanstalk()->stats();
        }

        /**
         * @return \Pheanstalk\Pheanstalk
         */
        protected function getPheanstalk()
        {
            if (!$this->_pheanstalk) {
                $this->_pheanstalk = new \Pheanstalk\Pheanstalk($this->getHost(), $this->getPort());
            }

            return $this->_pheanstalk;
        }

        /**
         * @return int
         */
        protected function getTimeOut()
        {
            return \Pheanstalk\Pheanstalk::DEFAULT_CONNECT_TIMEOUT;
        }

        /**
         * @return string
         */
        protected function getHost()
        {
            return $this->getProcessusContext()->getRegistry()->getProcessusConfig()->getBeanstalkdConfig()->getServerHost();
        }

        /**
         * @return string
         */
        protected function getTube()
        {
            return \Pheanstalk\Pheanstalk::DEFAULT_TUBE;
        }

        /**
         * @return int
         */
        protected function getPort()
        {
            return $this->getProcessusContext()->getRegistry()->getProcessusConfig()->getBeanstalkdConfig()->getServerPort();
        }

        /**
         * @param $error
         * @return bool
         */
        protected function _logErrorToMySql($error)
        {
            $pdo = $this->insert($this->ccFactory()
                    ->setSqlTableName($this->_getLogTable())
                    ->setSqlParams($this->_getSqlLogParams($error)
                )
            );
            return $pdo;
        }

        /**
         * @abstract
         * @return string
         */
        abstract protected function _getLogTable ();

        /**
         * @abstract
         * @param $rawObject
         * @return array
         */
        abstract protected function _getSqlLogParams ($rawObject);
    }
}

?>