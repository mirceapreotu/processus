<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 7/8/11
 * Time: 11:24 AM
 * To change this template use File | Settings | File Templates.
 */
abstract class Core_Abstracts_AbstractManager extends Core_Abstracts_AbstractClass
{
    /**
     * @var Memcached
     */
    private $_memcachedClient = NULL;

    /**
     * mysql database connector
     */
    private $_database = NULL;

    /**
     * redis client
     */
    private $_redisClient;

    /** @var Zend_Db_Adapter_Abstract */
    private $_trackDB;

    /** @var Lib_Db_Xdb_Client */
    protected $_dbClient;

    /**
     * @var Mongo
     */
    private $_mongoClient;


    // #########################################################


    /**
     * @return string
     */
    protected function getClassName()
    {
        return __CLASS__ . "_";
    }


    // #########################################################


    /**
     * @return Zend_Db_Adapter_Abstract
     */
    protected function getTrackDatabase()
    {
        if ( ! $this->_trackDB)
        {
            /** @var $_database Zend_Db_Adapter_Abstract  */
            $this->_trackDB = Bootstrap::getRegistry()->getProperty("DB_TRACK");
        }

        return $this->_trackDB;
    }


    // #########################################################


    /**
     * @return string
     */
    protected function getDatabaseName()
    {
        return "crowdpark";
    }


    // #########################################################


    /**
     * @return Zend_Db_Adapter_Abstract
     */
    protected function getDatabase()
    {
        if ( ! $this->_database)
        {
            /** @var $_database Zend_Db_Adapter_Abstract  */
            $this->_database = Bootstrap::getRegistry()->getProperty("DB");
        }

        return $this->_database;
    }


    // #########################################################


    /**
     * @return
     */
    public function getRedisClient()
    {
        if ( ! $this->_redisClient)
        {
            $this->_redisClient = new Redis();
            $this->_redisClient->connect('127.0.0.1');
        }

        return $this->_redisClient;
    }


    // #########################################################


    /**
     * @return Mongo
     */
    public function getMongoDBClient()
    {
        if ( ! $this->_mongoClient)
        {
            $this->_mongoClient = new Mongo();
            $this->_mongoClient->connect();
        }

        return $this->_mongoClient;
    }


    // #########################################################


    /**
     * @param $method
     * @return string
     */
    protected function getMemKey($method)
    {
        return $this->getClassName() . $method;
    }


    // #########################################################


    /**
     * @return Memcached
     */
    public function getMemcachedClient()
    {
        if ( ! $this->_memcachedClient)
        {
            try
            {
                $this->_memcachedClient = Bootstrap::getRegistry()->getProperty("MEMCACHED");
            }
            catch (Exception $error)
            {
                $couchDoc = new stdClass();
                $couchDoc->type = 'fatal';
                $couchDoc->created = time();
                $couchDoc->error = $error;

                $this->getCouchDBLogger()
                    ->setData($couchDoc)
                    ->setLogType("[FATAL]" . __CLASS__ . __METHOD__)
                    ->writeLog();

                return $error;
            }
        }

        return $this->_memcachedClient;
    }


    // #########################################################


    /**
     * @var Couch_Client
     */
    private $_couchDBClient = NULL;

    /**
     * @return Couch_Client
     */
    public function getCouchDBClient($dbName = 'monitoring_crowdpark')
    {
        if ( ! $this->_couchDBClient)
        {
            $this->_coucDBClient = new Couch_Client(Bootstrap::getConfig()->couchDB->host, $dbName);
        }

        return $this->_coucDBClient;
    }


    // #########################################################


    /**
     * @param $value
     * @return int
     */
    protected function getExpiredTimeInSec($value)
    {
        return $value;
    }


    // #########################################################


    /**
     * @param $value
     * @return int
     */
    protected function getExpiredTimeInMin($value)
    {
        $expired = 60 * $value;
        return $expired;
    }


    // #########################################################


    /**
     * @param $value
     * @return int
     */
    protected function getExpiredTimeInHour($value)
    {
        $expired = (60 * 60) * $value;
        return $expired;
    }


    // #########################################################


    /**
     * @param $value
     * @return int
     */
    protected function getExpiredTimeInDay($value)
    {
        $expired = (60 * 60 * 24) * $value;
        return $expired;
    }


    // #########################################################


    /**
     * get data from database
     * @param $config App_GaintS_Vo_Core_GetDataConfigVo
     */
    public function getRowsAndDontValidateParamsMissing($config)
    {
        if ( ! $config instanceof Core_GaintS_Vo_Core_GetDataConfigVo)
        {
            throw new Exception('wrong class');
        }

        $rawData = NULL;
        $memKey = $config->getMemKey();
        $sqlStmt = $config->getSQLStmt();
        $sqlParams = $config->getSQLParamData();
        $expiredTime = $config->getExpiredTime();
        $getFromCache = $config->getFromCache();

        if($getFromCache)
        {
            $rawData = $this->getMemcachedClient()->get($memKey);
        }

        if ( ! $rawData)
        {
            $rawData = $this->getDbClient()->getRowsAndDontValidateParamsMissing($sqlStmt, $sqlParams);
            $this->getMemcachedClient()->set($memKey, $rawData, $expiredTime);
        }

        return $rawData;
    }


    // #########################################################


    /**
     * @param string $table
     * @param mixed $rowInsert
     * @return NULL|string
     */
    public function insert($table, $rowInsert)
    {
        return $this->getDbClient()->insert($table, $rowInsert, TRUE);
    }


    // #########################################################


    /**
     * Insert data into table
     * @param string $table
     * @param mixed $rowInsert
     * @param mixed $rowUpdate
     */
    public function insertOrUpdate($table, $rowInsert, $rowUpdate)
    {
        return $this->getDbClient()->insertOrUpdate($table, $rowInsert, $rowUpdate);
    }


    // #########################################################


    /**
     * @throws Exception
     * @param App_GaintS_Vo_Core_GetDataConfigVo $config
     * @return array|mixed|NULL
     */
    public function getRows($config)
    {
        if ( ! $config instanceof Core_GaintS_Vo_Core_GetDataConfigVo)
        {
            throw new Exception('wrong class');
        }

        $rawData = NULL;
        $memKey = $config->getMemKey();
        $sqlStmt = $config->getSQLStmt();
        $sqlParams = $config->getSQLParamData();
        $expiredTime = $config->getExpiredTime();
        $getFromCache = $config->getFromCache();

        if($getFromCache)
        {
            $rawData = $this->getMemcachedClient()->get($memKey);
        }

        if ( ! $rawData)
        {
            $rawData = $this->getDbClient()->getRows($sqlStmt, $sqlParams);
            $this->getMemcachedClient()->set($memKey, $rawData, $expiredTime);
        }

        return $rawData;
    }


    // #########################################################


    /**
     * @throws Exception
     * @param App_GaintS_Vo_Core_GetDataConfigVo $config
     * @return array|mixed|NULL
     */
    public function getRow($config)
    {
        if ( ! $config instanceof Core_GaintS_Vo_Core_GetDataConfigVo)
        {
            throw new Exception('wrong class');
        }

        $rawData = NULL;
        $memKey = $config->getMemKey();
        $sqlStmt = $config->getSQLStmt();
        $sqlParams = $config->getSQLParamData();
        $expiredTime = $config->getExpiredTime();
        $getFromCache = $config->getFromCache();

        if($getFromCache)
        {
            $rawData = $this->getMemcachedClient()->get($memKey);
        }

        if ( ! $rawData)
        {
            $rawData = $this->getDbClient()->getRow($sqlStmt, $sqlParams);
            $this->getMemcachedClient()->set($memKey, $rawData, $expiredTime);
        }

        $rawData['debug'] = $config;

        return $rawData;
    }


    // #########################################################


    /**
     * @return Lib_Db_Xdb_Client
     */
    protected function getDbClient()
    {
        if ( ! $this->_dbClient)
        {
            /** @var $_dbClient Lib_Db_Xdb_Client */
            $this->_dbClient = Core_Facebook_Application::getInstance()->getDbClient();
        }

        return $this->_dbClient;
    }


    // #########################################################


    /**
     * @param $sqlStmt
     * @param $sqmParamData
     * @param $method
     * @return App_GaintS_Vo_Core_GetDataConfigVo
     */
    protected function getDataConfig($sqlStmt, $method, $sqmParamData = NULL)
    {
        $config = new Core_GaintS_Vo_Core_GetDataConfigVo();

        $config->setSQLStmt($sqlStmt)
            ->setFromCache(TRUE)
            ->setSQLParamData($sqmParamData)
            ->setMemKey($this->getMemKey($method));

        return $config;
    }
}
