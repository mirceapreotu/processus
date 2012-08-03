<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:28 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Beanstalkd
{
    abstract class AbstractWorker extends \Processus\Abstracts\Manager\AbstractManager
    {
        /**
         * @var \Pheanstalk\Pheanstalk
         */
        private $_pheanstalk;

        public function AbstractWorker()
        {
            try
            {
                $this->run();
            }
            catch (\Exception $error)
            {
                $this->_logErrorToMySql($error);
            }

        }

        /**
         * @param $error
         *
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

        public function run()
        {
            /** @var $job \Pheanstalk\Job */
            $job = $this->getPheanstalk()->watch($this->getTube())->ignore("default")->reserve();
            echo "Get Data" . PHP_EOL;
            var_export($job->getData(), TRUE);
            $this->getPheanstalk()->delete($job);
            //trace(var_export($this->_getStats()));
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
            if (!$this->_pheanstalk)
            {
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
         *
         */
        protected function _logToMySql()
        {
            $sqlTable  = "log_task";
            $sqlParams = array();

            $this->insert($this->ccFactory()
                    ->setSqlTableName($sqlTable)
                    ->setSqlParams($sqlParams)
            );
        }
    }
}