<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:42
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_ServerAbstract
{

    /**
     * @var array
     */
    protected $_log;

    /**
     * @var bool
     */
    protected $_logEnabled;

    /**
     * @var Redisek_Connection
     */
    protected $_connection;


    /**
     * @var Redisek_Model_KeyPrefix
     */
    protected $_modelKeyPrefix;


    /**
     * @var Redisek_Serializer
     */
    protected $_serializer;


    /**
     * @var array|null
     */
    protected $_config;


    // ++++++++++++++++++++ config ++++++++++++++++++++++++++++++

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @param array $config
     * @return void
     */
    public function setConfig(array $config)
    {
        $this->_config = $config;
    }


    // ++++++++++++++++++++ log +++++++++++++++++++++++++++++++
    /**
     * @return array
     */
    public function getLog()
    {
        if (!is_array($this->_log)) {
            $this->_log = array();
        }
        return $this->_log;
    }

    /**
     * @return array|null
     */
    public function getLogLastItem()
    {
        $log = $this->getLog();
        return Redisek_Util_Array::getProperty($log, 0);
    }
    /**
     * @return bool
     */
    public function getIsLogEnabled()
    {
        return (bool)($this->_logEnabled===true);
    }

    /**
     * @return void
     */
    public function setIsLogEnabled($value)
    {
        $this->_logEnabled=($value===true);
    }

    // +++++++++++++++++++++++ connection +++++++++++++++++++++++

    public function getConfigConnection()
    {
        $config = $this->getConfig();


        if (!is_array($this->_config)) {
            $e = new Redisek_Exception(
                "Error invalid server.config"
            );
            $e->setType(Redisek_Exception::ERROR_CONFIGURATION_INVALID);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                ));
            throw $e;
        }


        $configConnection = Redisek_Util_Array::getProperty(
            $config, "connection"
        );

        if (!is_array($configConnection)) {
            $e = new Redisek_Exception(
                "Error invalid server.config.connection"
            );
            $e->setType(Redisek_Exception::ERROR_CONFIGURATION_INVALID);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                ));
            throw $e;
        }


        return $configConnection;

    }


    /**
     * @return Redisek_Connection
     */
    public function getConnection()
    {
        if (($this->_connection instanceof Redisek_Connection)!==true) {
            $connection = new Redisek_Connection();

            $configConnnection = $this->getConfigConnection();
            if (is_array($configConnnection)) {
                $connection->applyConfig($configConnnection);
            }
            $this->_connection = $connection;
        }

        return $this->_connection;
    }


    // +++++++++++++++++++++ Model.KeyPrefix +++++++++++++++++++++
    /**
     * @throws Redisek_Exception
     * @return array|null
     */
    public function getConfigModelKeyPrefix()
    {

        $result = null;

        $config = $this->getConfig();


        if (!is_array($this->_config)) {
            $e = new Redisek_Exception(
                "Error invalid server.config"
            );
            $e->setType(Redisek_Exception::ERROR_CONFIGURATION_INVALID);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                ));
            throw $e;
        }


        $configModel = Redisek_Util_Array::getProperty(
            $config, "model"
        );
        $configModelKeyPrefix = Redisek_Util_Array::getProperty(
            $configModel, "keyPrefix"
        );
        if (is_array($configModelKeyPrefix)) {
            return $configModelKeyPrefix;
        }

        return $result;

    }

    /**
     * @return Redisek_Model_KeyPrefix
     */
    public function getModelKeyPrefix()
    {
        if (
                ($this->_modelKeyPrefix instanceof Redisek_Model_KeyPrefix)
                !==true) {

            $model = new Redisek_Model_KeyPrefix();

            $config = $this->getConfigModelKeyPrefix();
            if (is_array($config)) {

                if (Redisek_Util_Array::hasProperty($config, "class")) {
                    $class = Redisek_Util_Array::getProperty($config, "class");
                    if ($class === null) {
                        $class = get_class($this);
                    }
                    $config["class"] = $class;
                }

                $model->applyConfig($config);
            }
            $this->_modelKeyPrefix = $model;
        }
        return $this->_modelKeyPrefix;
    }


    /**
     * @param  string|null $bucketName
     * @return void
     */
    public function setBucket($bucketName)
    {
        if ($bucketName!==null) {
            // check if we have a dsn that supports buckets
            $dsn = (string)$this->getModelKeyPrefix()->getDsn();
            if (strpos($dsn, "{bucket}")===false) {
                $e = new Redisek_Exception(
                    "Current keyprefixModel does not support buckets."
                    ." Marker {bucket} missing at keyprefix dsn"
                );
                $e->setType(Redisek_Exception::ERROR_MODEL_KEYPREFIX_INVALID);
                $e->createFault(
                    $this,
                    __METHOD__,
                    array(
                    ));
                throw $e;
            }
        }

        $this->getModelKeyPrefix()->setBucket($bucketName);
    }
    /**
     * @return null|string
     */
    public function getBucket()
    {
        //var_dump($this->getModelKeyPrefix()->getConfig());
        //var_dump($this->getModelKeyPrefix()->getBucket());
        //var_dump($this->getModelKeyPrefix()->getConfig());exit;

        return $this->getModelKeyPrefix()->getBucket();
    }


    /**
     * @return null|string
     */
    public function getKeyPrefixDsn()
    {
        return $this->getModelKeyPrefix()->getDsn();
    }

    /**
     * @param  string $dsn
     * @return void
     */
    public function setKeyPrefixDsn($dsn)
    {
        $this->getModelKeyPrefix()->setDsn($dsn);
    }
    /**
     * @return null|string
     */
    public function getKeyPrefix()
    {
        return $this->getModelKeyPrefix()->getPrefix();
    }


    // ++++++++++++++++++ serializer ++++++++++++++++++++++


    /**
     * @return Redisek_Serializer
     */
    public function getSerializer()
    {
        if (($this->_serializer instanceof Redisek_Serializer)!==true) {


            $serializer = new Redisek_Serializer();

            $config = $this->getConfigSerializer();
            if (is_array($config)) {
                $serializer->applyConfig($config);
            }
            


            $this->_serializer = $serializer;

        }

        return $this->_serializer;
    }


    /**
     * @throws Redisek_Exception
     * @return array|null
     */
    public function getConfigSerializer()
    {

        $result = null;

        $config = $this->getConfig();


        if (!is_array($this->_config)) {
            $e = new Redisek_Exception(
                "Error invalid server.config"
            );
            $e->setType(Redisek_Exception::ERROR_CONFIGURATION_INVALID);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                ));
            throw $e;
        }


        $configSerializer = Redisek_Util_Array::getProperty(
            $config, "serializer"
        );
        if (is_array($configSerializer)) {
            return $configSerializer;
        }

        return $result;

    }

    /**
     * @return bool
     */
    public function getIsSerializeEnabled()
    {
        $model = $this->getModelKeyPrefix();
        return $this->getSerializer()->isEnabledByKeyPrefixModel($model);
    }





    // ++++++++++++++++++ commands ++++++++++++++++++++++++++


    /**
     * @throws Exception
     * @param  string $method
     * @param array $params
     * @return array|int|null|string
     */
    protected function _request($method, array $params)
    {
        if ($this->getIsLogEnabled() !== true) {
            $result = $this->getConnection()->request($method, $params);
            return $result;
        }

        // track request and response

        $log = $this->getLog();
        $logItem = array(
            "request" => array(
                "method" => $method,
                "params" => $params,
            ),
            "response" => array(
                "result" => null,
                "error" => null,
            ),
        );

        $result = null;
        try {
            $result = $this->getConnection()->request($method, $params);
            $logItem["response"]["result"] = $result;
            array_unshift($log, $logItem);
            $this->_log = $log;


        } catch (Exception $e) {

            $logItem["response"]["error"] = array(
                "class" => get_class($e),
                "message" => $e->getMessage(),
            );
            array_unshift($log, $logItem);
            $this->_log = $log;
            throw $e;
        }


        return $result;
    }


}
