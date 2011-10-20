<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:42
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Server extends Redisek_ServerAbstract
{


    // ++++++++++++++++++ commands ++++++++++++++++++++++++++

    /**
     * @param  string $method
     * @param array $params
     * @return array|int|null|string
     */
    public function request($method, array $params)
    {
        $result = $this->_request($method, $params);
        return $result;
    }

    /**
     * Append a value to a key
     * @param  string $key
     * @param  mixed $value
     * @return array|int|null|string
     */
    public function append($key, $value)
    {
        $method = "APPEND";

        $connection = $this->getConnection();
        $modelKeyPrefix = $this->getModelKeyPrefix();
        $serializer = $this->getSerializer();
        // may transform value
        $value = $serializer->encodeValueIfEnabledByKeyPrefixModel(
            $modelKeyPrefix, $value
        );
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
            $value,
        );
        $result = $this->_request($method, $params);

        return $result;
    }


    /**
     * Authenticate to the server
     * @param  string $password
     * @return array|int|null|string
     */
    public function auth($password)
    {
        $method = "AUTH";
        $connection = $this->getConnection();

        $params = array(
            $password,
        );
        $result = $this->_request($method, $params);
        return $result;
    }



    /**
     * @param  $key
     * @return bool
     */
    public function exists($key)
    {
        $method = "EXISTS";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
        );
        $result = $this->_request($method, $params);

        return ($result===1);
    }

    /**
     * Get the type of a key
     * @param string $key
     * @return string
     */
    public function type($key)
    {
        $method = "TYPE";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
        );
        $result = $this->_request($method, $params);


        return $result;
    }


    /**
     * Get the value of a key
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        $method = "GET";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        $serializer = $this->getSerializer();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
        );
        $result = $this->_request($method, $params);

        //may transform result value
        $result = $serializer->decodeValueIfEnabledByKeyPrefixModel(
            $modelKeyPrefix, $result
        );

        return $result;
    }


    /**
     * Inserts value at the head of the list stored at key.
     * If key does not exist, it is created as empty list
     * before performing the push operation.
     * When key holds a value that is not a list, an error is returned.
     * @param string $key
     * @param array $value
     * @return int
     */
    public function lPush($key, array $valueList)
    {
        $method = "LPUSH";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        $serializer = $this->getSerializer();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // may transform value(s)
        $valueList = $serializer->encodeValueListIfEnabledByKeyPrefixModel(
            $modelKeyPrefix, $valueList
        );

        $params = array(
            $key
        );

        foreach($valueList as $value) {
            $params [] = $value;
        }

        $result = $this->_request($method, $params);
        return $result;
    }

    /**
     * @param  $key
     * @param  $offset
     * @param  $limit
     * @return array|int|null|string
     */
    public function lRange($key, $offset, $limit)
    {
        $method = "LRANGE";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        $serializer = $this->getSerializer();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // may transform value(s)
        //$valueList = $this->_encodeValueListIfEnabled($valueList);

        $params = array(
            $key,
            $offset,
            $limit
        );
        $result = (array)$this->_request($method, $params);

        // may transform values in resultList
        $result = $serializer->decodeValueListIfEnabledByKeyPrefixModel(
            $modelKeyPrefix, $result
        );


        return $result;
    }


    /**
     * Set key to hold the string value.
     * If key already holds a value, it is overwritten, regardless of its type.
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set($key, $value)
    {
        $method = "SET";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        $serializer = $this->getSerializer();
        // may transform value
        $value = $serializer->encodeValueIfEnabledByKeyPrefixModel(
            $modelKeyPrefix, $value
        );
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
            $value,
        );
        $result = $this->_request($method, $params);

        return (bool)($result==="OK");
    }



    /**
     * @param  $pattern
     * @return array
     */
    public function keys($pattern)
    {
        $method = "KEYS";
        $modelKeyPrefix = $this->getModelKeyPrefix();

        $pattern = (string)$pattern;

        $pattern=$modelKeyPrefix->addKeyPrefix($pattern);

        // request
        $params = array(
            $pattern
        );
        $result = (array)$this->_request($method, $params);

        $result = $modelKeyPrefix->removeKeyPrefixFromKeyList($result);


        return $result;
    }

    /**
     * returns number of keys removed
     * @param array $keyList
     * @return int
     */
    public function del(array $keyList)
    {
        $method = "DEL";
        $modelKeyPrefix = $this->getModelKeyPrefix();

        $keyList = $modelKeyPrefix->addKeyPrefixToKeyList($keyList);

        // request
        $params = array(

        );
        foreach($keyList as $key) {
            $params [] = $key;
        }

        $result = $this->_request($method, $params);

        return $result;
    }



    /**
     * Get the ttl of a key
     * @param string $key
     * @return string
     */
    public function ttl($key)
    {
        $method = "TTL";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
        );
        $result = $this->_request($method, $params);

        return $result;
    }
    /**
     * Set key to hold the string value.
     * If key already holds a value, it is overwritten, regardless of its type.
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function expire($key, $timeout)
    {
        $method = "EXPIRE";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
            $timeout,
        );
        $result = $this->_request($method, $params);

        return (bool)($result===1);
    }
    /**
     * Set key to hold the string value.
     * If key already holds a value, it is overwritten, regardless of its type.
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function expireAt($key, $timestamp)
    {
        $method = "EXPIREAT";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
            $timestamp,
        );
        $result = $this->_request($method, $params);

        return (bool)($result===1);
    }


    /**
     * @param  string $key
     * @return array|int|null|string
     */
    public function objectIdleTime($key)
    {
        $method = "OBJECT IDLETIME";

        $modelKeyPrefix = $this->getModelKeyPrefix();
        // may transform key
        $key = $modelKeyPrefix->addKeyPrefix($key);

        // request
        $params = array(
            $key,
        );
        $result = $this->_request($method, $params);

        return $result;
    }


}
