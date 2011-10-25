<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 25.09.11
 * Time: 08:58
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Serializer
{

    /**
     * @var bool
     */
    protected $_enabled;

    /**
     * @var array|null
     */
    protected $_config;

    public function applyConfig(array $config)
    {
        $this->_config = $config;

        $this->_enabled = (
                Redisek_Util_Array::getProperty($config, "enabled")===true
        );
    }

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return $this->_config;
    }


    /**
     * @param Redisek_Model_KeyPrefix $model
     * @return bool
     */
    public function isEnabledByKeyPrefixModel(Redisek_Model_KeyPrefix $model)
    {
        $result = false;
        if ($this->_enabled !== true) {
            return $result;
        }


        $config = $this->getConfig();
        if (!is_array($config)) {
            return $result;
        }

        $config = Redisek_Util_Array::getProperty($config, "bucket");
        if (!is_array($config)) {
            return $result;
        }

        $whitelist = (array)Redisek_Util_Array::getProperty(
            $config, "whitelist"
        );
        $blacklist = (array)Redisek_Util_Array::getProperty(
            $config, "blacklist"
        );
        $flags = Redisek_Util_Array::getProperty(
            $config, "flags"
        );

        $bucket = $model->getBucket();

        if (Redisek_Util_Fnmatch::isWhitelistedAndNotBlacklisted(
            $bucket, $whitelist, $blacklist, $flags
        )===true) {
            return true;
        }

        return $result;
    }


    public function getBucketWhitelist()
    {
       $result =null;

        $config = $this->getConfig();
        if (!is_array($config)) {
            return $result;
        }

        $config = Redisek_Util_Array::getProperty($config, "bucket");
        if (!is_array($config)) {
            return $result;
        }

        $whitelist = Redisek_Util_Array::getProperty(
            $config, "whitelist"
        );

        return $whitelist;
    }


    /**
     * @param Redisek_Model_KeyPrefix $model
     * @param  $value
     * @return string|mixed
     */
    public function encodeValueIfEnabledByKeyPrefixModel(
        Redisek_Model_KeyPrefix $model,
        $value
    )
    {
        $result = $value;
        if ($this->isEnabledByKeyPrefixModel($model)) {
            $result = $this->_encodeValue($value);
        }

        return $result;
    }

    /**
     * @param Redisek_Model_KeyPrefix $model
     * @param array $valueList
     * @return array
     */
    public function encodeValueListIfEnabledByKeyPrefixModel(
        Redisek_Model_KeyPrefix $model,
        array $valueList
    )
    {
        $result = $valueList;
        if (!$this->isEnabledByKeyPrefixModel($model)) {
            return $result;
        }

        $result = array();
        foreach($valueList as $value) {
            // may transform value
            $value = $this->_encodeValue($value);
            $result [] = $value;
        }

        return $result;
    }

    /**
     * @param Redisek_Model_KeyPrefix $model
     * @param array $valueList
     * @return array
     */
    public function decodeValueListIfEnabledByKeyPrefixModel(
        Redisek_Model_KeyPrefix $model,
        array $valueList
    )
    {
        $result = $valueList;
        if (!$this->isEnabledByKeyPrefixModel($model)) {
            return $result;
        }

        $result = array();
        foreach($valueList as $value) {
            // may transform value
            $value = $this->_decodeValue($value);
            $result [] = $value;
        }

        return $result;
    }

    /**
     * @param Redisek_Model_KeyPrefix $model
     * @param  string $value
     * @return mixed|null
     */
    public function decodeValueIfEnabledByKeyPrefixModel(
        Redisek_Model_KeyPrefix $model,
        $value
    )
    {
        $result = $value;
        if ($this->isEnabledByKeyPrefixModel($model)) {
            $result = $this->_decodeValue($value);
        }
        return $result;
    }




    /**
     * @throws Redisek_Exception
     * @param  mixed $value
     * @return string
     */
    protected function _encodeValue($value)
    {

        $jsonText = json_encode(
            array($value)
        );
        if (is_string($jsonText)!==true) {
            $e = new Redisek_Exception(
                "encodeValue failed!"
            );
            $e->setType(Redisek_Exception::ERROR_CONFIGURATION_INVALID);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                ));
            throw $e;
        }
        return $jsonText;
    }

    /**
     * @param  string $text
     * @return mixed|null
     */
    protected function _decodeValue($text)
    {
        $result = null;
        if (!is_string($text)) {
            return $result;
        }
        $data = json_decode($text, true);
        if (!is_array($data)) {
            return $result;
        }
        return Redisek_Util_Array::getProperty($data, 0);
    }


}
