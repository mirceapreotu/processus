<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 25.09.11
 * Time: 08:02
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Model_KeyPrefix
{


    /**
     * @var array|null
     */
    protected $_config;


    // ++++++++++++++ working with key prefixes/buckets ++++++++++

    /**
     * e.g.: "com.example.HelloWorld:Fb.User:"
     * @var string|null
     */
    protected $_prefix;



    // ++++++++++++++++++ working with config +++++++++++++++++++
    /**
     * @return array|null
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @param  string $name
     * @return mixed|null
     */
    public function getConfigProperty($name)
    {
        $config = $this->getConfig();
        return Redisek_Util_Array::getProperty($config, $name);
    }
    /**
     * @param  $name
     * @param  $value
     * @return void
     */
    public function setConfigProperty($name, $value)
    {
        $config = $this->getConfig();
        if (!is_array($config)) {
            $config = array();
        }
        $config[$name] = $value;
        $this->applyConfig($config);
    }



    /**
     * @param array $config
     * @return void
     */
    public function applyConfig(array $config)
    {
        $this->_config = $config;
        $this->_onConfigChanged();
    }


    /**
     * @return void
     */
    protected function _onConfigChanged()
    {
        // invalidate key prefix
        $this->_prefix = null;
    }

    // ++++++++++++++ working with key prefixes/buckets ++++++++++



    /**
     * @param  string|null $value
     * @return
     */
    public function setBucket($value)
    {
        $this->setConfigProperty("bucket", $value);
    }

    /**
     * @return string|null
     */
    public function getBucket()
    {
        return $this->getConfigProperty("bucket");
    }



    /**
     * @param  string $dsn
     * @return bool
     */
    public function isValidDsn($dsn)
    {
        $result = false;
        if (!is_string($dsn)) {
            return $result;
        }

        if (!fnmatch("*{key}", $dsn)) {
            return $result;
        }

        return true;
    }

    /**
     * @throws Redisek_Exception
     * @return void
     */
    public function requireValidDsn()
    {
        $dsn = $this->getDsn();
        $this->_requireValidDsn($dsn);
    }


    /**
     * @throws Redisek_Exception
     * @return void
     */
    protected function _requireValidDsn($dsn)
    {
        if (!$this->isValidDsn($dsn)) {
            $e = new Redisek_Exception(
                            "Invalid keyPrefix for dsn."
                            ." Dsn must finish with marker 'key'"
                            ." The build rule is '*{key}' "
                        );

                        $e->setType(Redisek_Exception::ERROR_CONFIGURATION_INVALID);
                        $e->createFault($this, __METHOD__, array(
                                                 "dsn" => $dsn
                                                           ));
                        throw $e;

        }

    }
    /**
     * @throws Redisek_Exception
     * @param  string $value
     * @return
     */

    public function setDsn($value)
    {
        $this->_requireValidDsn($value);
        $this->setConfigProperty("dsn", $value);
        $this->requireValidDsn();
        // invalidate key prefix
        $this->_prefix = null;
    }

    /**
     * @return null|string
     */

    public function getDsn()
    {
        return $this->getConfigProperty("dsn");
    }

    /**
     * @return null|string
     */
    public function getPrefix()
    {
        $prefix = $this->_prefix;
        if ($prefix !== null) {
            return $prefix;
        }

        $prefix = $this->_newPrefix();
        $this->_prefix = $prefix;
        return $this->_prefix;
    }


    /**
     * @throws Redisek_Exception
     * @return string
     */
    protected function _newPrefix()
    {
        $this->requireValidDsn();

        $template = $this->getDsn();

        $prefixTemplate = str_replace("{key}","", $template);

        // do not cast as string, we want errors thrown if there are some
        $prefixTemplateData = array();

        $config = (array)$this->getConfig();
        foreach($config as $key => $value) {
            switch($key) {
                case "dsn": {
                    // ignore (that is our template! )
                    break;
                }
                default: {
                    $prefixTemplateData[$key] = $value;
                    break;
                }
            }
        }



        $prefixTemplateParsed=
                (string)Redisek_Util_String::parseTemplate(
                    $prefixTemplate, $prefixTemplateData, false
                );

        $isValid = true;
        if (strpos($prefixTemplateParsed,"{")!==false) {
            $isValid = false;
        }
        if (strpos($prefixTemplateParsed,"}")!==false) {
            $isValid = false;
        }
        if (!$isValid) {

            $e = new Redisek_Exception(
                "Error while parsing keyprefix template.
                Contains invalid chars '{' or '} "
            );
            $e->setType(Redisek_Exception::ERROR_MODEL_KEYPREFIX_INVALID);
            $e->createFault($this, __METHOD__, array());
            throw $e;
        }


        return $prefixTemplateParsed;


    }


    /**
     * @param  string $key
     * @return string
     */
    public function addKeyPrefix($key)
    {
        $key = (string)$key;

        $prefix = $this->getPrefix();

        $result = $prefix.$key;

        return $result;
    }

    /**
     * @throws Exception
     * @param  string $key
     * @return string
     */
    public function removeKeyPrefix($key)
    {
        $key = (string)$key;

        $prefix = (string)$this->getPrefix();

        $result = Redisek_Util_String::removePrefixIfExist(
            $key, $prefix, false
        );
        if (!is_string($result)) {
            //that case actually can't happen
            throw new Exception("Hey Code Monkey, fix me! ".__METHOD__);
        }

        return $result;

    }





    /**
     * @param array $keyList
     * @return array
     */
    public function addKeyPrefixToKeyList(array $keyList)
    {

        $result = array();
        foreach($keyList as $index => $value) {
            $key = $value;
            $prefixedKey = $this->addKeyPrefix($key);
            $result[$index] = $prefixedKey;
        }

        return $result;
    }

    /**
     * @param array $keyList
     * @return array
     */
    public function removeKeyPrefixFromKeyList(array $keyList)
    {
        $result = array();
        foreach($keyList as $index => $value) {
            $prefixedKey = $value;
            $key = $this->removeKeyPrefix($prefixedKey);
            $result[$index] = $key;
        }

        return $result;
    }




    /**
     * @param array $keyList
     * @return array
     */
    public function addKeyPrefixToKeyDictionary(array $keyDictionary)
    {

        $result = array();
        foreach($keyDictionary as $index => $value) {
            $key = $index;
            $prefixedIndex = $this->addKeyPrefix($key);
            $result[$prefixedIndex] = $value;
        }

        return $result;
    }

    /**
     * @param array $keyList
     * @return array
     */
    public function removeKeyPrefixFromKeyDictionary(array $keyDictionary)
    {
        $result = array();
        foreach($keyDictionary as $index => $value) {
            $prefixedIndex = $index;
            $unPrefixedIndex = $this->removeKeyPrefix($prefixedIndex);
            $result[$unPrefixedIndex] = $value;
        }

        return $result;
    }



}
