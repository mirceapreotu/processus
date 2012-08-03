<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:29 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Beanstalkd\Job;
abstract class AbstractJob extends \Processus\Abstracts\Manager\AbstractManager
    implements \Processus\Interfaces\InterfaceJob
{

    /**
     * @var float
     */
    protected $_startTime;

    /**
     * @var float
     */
    protected $_runTime;

    /**
     * @var boolean
     */
    protected $_success;

    /**
     * @return null
     * @throws \Processus\Exceptions\NotImplementedException
     */
    public function startJob()
    {
        throw new \Processus\Exceptions\NotImplementedException("Missing Implementation");
        return NULL;
    }

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
     * @var array
     */
    private $_data;

    /**
     * @param array $rawData
     *
     * @return AbstractJob
     */
    public function setData(array $rawData)
    {
        $this->_data = $rawData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
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
            $this->_pheanstalk = \Application\Factory\PheanstalkFactory::factory($this->getHost(), $this->getPort());
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
     *
     * @return bool
     */
    protected function _logErrorToMySql($error)
    {
        $pdo = $this->insert(
            $this->ccFactory()
                ->setSqlTableName($this->_getLogTable())
                ->setSqlParams(
                $this->_getSqlLogParams($error)
            )
        );
        return $pdo;
    }

    /**
     * @abstract
     * @return string
     */
    abstract protected function _getLogTable();

    /**
     * @abstract
     *
     * @param $rawObject
     *
     * @return array
     */
    abstract protected function _getSqlLogParams($rawObject);
}