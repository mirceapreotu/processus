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
    abstract class AbstractWorker extends \Processus\Abstracts\AbstractTask
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

            }

        }

        public function run()
        {
            $job = $this->getPheanstalk()->watch($this->getTube())->reserve();
            trace(var_export($this->_getStats()));
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
            return \Pheanstalk\Pheanstalk::DEFAULT_HOST;
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
            return \Pheanstalk\Pheanstalk::DEFAULT_PORT;
        }
    }
}