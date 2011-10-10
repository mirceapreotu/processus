<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:45
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Exception extends Exception
{

    CONST ERROR_UNKNOWN = "UNKNOWN";
    CONST ERROR_REDIS_CONNECTION_FAILED = "REDIS_CONNECTION_FAILED";
    CONST ERROR_REDIS_REQUEST_FAILED = "REDIS_REQUEST_FAILED";
    CONST ERROR_REDIS_RESPONSE_INVALID = "REDIS_RESPONSE_INVALID";
    CONST ERROR_CONFIGURATION_INVALID = "CONFIGURATION_INVALID";
    CONST ERROR_MODEL_KEYPREFIX_INVALID = "MODEL_KEYPREFIX_INVALID";
    CONST ERROR_SERIALIZER_ENCODE_FAILED = "SERIALIZER_ENCODE_FAILED";

    /**
     * @var string|null
     */
    protected $_host;
    /**
     * @var string|null
     */
    protected $_port;
    /**
     * @var string|null
     */
    protected $_type=SELF::ERROR_UNKNOWN;

    /**
     * @var array|null
     */
    protected $_fault;


    /**
     * @param  string $type
     * @return void
     */
    public function setType($type)
    {
        if (!is_string($type)) {
            $type = self::ERROR_UNKNOWN;
        }

        $this->_type = (string)$type;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return (string)$this->_type;
    }
    /**
     * @param  string $host
     * @param  string $port
     * @return void
     */
    public function setServer($host, $port)
    {
        $this->_host = (string)$host;
        $this->_port = (string)$port;
    }
    /**
     * @return string
     */
    public function getHost()
    {
        return (string)$this->_host;
    }
    /**
     * @return string
     */
    public function getPort()
    {
        return (string)$this->_port;
    }

    /**
     * @param  string|object $class
     * @param  string $method
     * @param  array|$details
     * @return void
     */
    public function createFault($class, $method, $details)
    {
        $fault = array(
            "class" => null,
            "method" => null,
            "details" => array(),
        );

        if ($class === null) {
            $class = (string)$class;
        }
        if (is_object($class)) {
            $class = (string)get_class($class);
        }

        $fault["class"] = (string)$class;
        $fault["method"] = (string)$method;
        $fault["details"] = (array)$details;

        $this->_fault = $fault;

    }

    /**
     * @return array
     */
    public function getFault()
    {
        $fault = $this->_fault;
        if (!is_array($fault)) {
            $fault = array(
                "class" => null,
                "method" => null,
                "details" => array(),
            );
        }
        return (array)$fault;
    }


}
