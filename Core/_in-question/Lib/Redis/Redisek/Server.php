<?php
/**
 * Lib_Redis_Redisek_Server Class
 *
 * @EXPERIMENTAL
 *
 * @package Lib_Redis_Redisek
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Redis_Redisek_Server
 *
 *
 * @package Lib_Redis_Redisek
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

require_once PATH_CORE."/contrib/Redisek/src/lib/Redisek/Redisek.php";

class Lib_Redis_Redisek_Server
{


    // ++++++++++++++++ config /DI ++++++++++++++++++++++++++++++

    /**
    * @var Zend_Config
    */
    protected $_config;
    /**
    * override in subclass or inject using Zend_Config
    * @var array
    */
    protected $_configDefault = array(

        "connection" => array(
                   "host" => "localhost",
                   "port" => "6379",
                   "isPersistent" => false,
            ),
        "model" => array(
            "keyPrefix" => array(
                "app" => null, // autodetect //"com.example.HelloWorld",
                "bucket" => "Fb.User", // default bucket
                "version" => 1,
                "class" => null, //autodetect
                "dsn"=> "{app}:{class}:v{version}:{bucket}:{key}",
            ),
        ),
         "serializer" => array(
             "enabled" => true,
             "bucket" => array(
                 // enable auto-serialize value for buckets
                 "flags" => null,
                 "whitelist" => array(
                    //"Fb.User",
                    //"Fb.Api.Request",
                     "Fb.*",
                 ),
                 "blacklist" => array(

                 ),
             )
        ),


    );


    /**
    * @return Zend_Config
    */
    public function getConfig()
    {
       if (($this->_config instanceof Zend_Config) !== true) {
           $this->_parseConfig();
           if ($this->_config instanceof Zend_Config) {
               $this->_onConfigParsed();
           } else {
               throw new Exception(
                   "Method returns invalid result at ".__METHOD__
               );
           }
       }
       return $this->_config;
    }


    public function setConfig(Zend_Config $config)
    {
       $this->_config = $config;

    }



    /**
    * @return Zend_Config
    */
    public function getConfigDefault()
    {
       return new Zend_Config((array)$this->_configDefault, true);
    }



    /**
     * @return void
     */
    protected function _parseConfig()
    {
       $configDefault = $this->getConfigDefault();
    /*
       $zendConfig = Lib_Utils_Config_Parser::loadByClassName(
           get_class($this)
       );
    */
       //loadByClassOrSuperClasses($instance)
       $zendConfig = Lib_Utils_Config_Parser::loadByClassOrSuperClasses(
           $this
       );
       $config = Lib_Utils_Config_Parser::merge(
           array(
                $configDefault,
                $zendConfig
           )
       );

       $this->_config = $config;

       $this->_onConfigParsed();

    }


    /**
    * your hooks here
    * @return void
    */
    protected function _onConfigParsed()
    {


    }



    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @var Redisek_Server
     */
    protected $_server;

    public function getServer()
    {
        if (($this->_server instanceof Redisek_Server)!==true) {
            $server = new Redisek_Server();

            $config = $this->getConfig()->toArray();

            if (is_array($config)!==true) {
                //throw new Exception("foo");
            }

            $redisekConfig = array(
                "connection" => $config["connection"],
                "model" => $config["model"],
                "serializer" => $config["serializer"],
            );


            $model = $config["model"];
            $modelKeyPrefix = Lib_Utils_Array::getProperty($model, "keyPrefix");
            if (is_array($modelKeyPrefix)!==true) {
                $modelKeyPrefix = array();
            }
            $class = Lib_Utils_Array::getProperty($modelKeyPrefix, "class");
            if ($class === null) {
                $modelKeyPrefix["class"] = get_class($this);
            }
            $redisekConfig["model"]["keyPrefix"] = $modelKeyPrefix;

            $app = Lib_Utils_Array::getProperty($modelKeyPrefix, "app");
            if ($app === null) {
                $modelKeyPrefix["app"] = Bootstrap::getRegistry()
                        ->getApplicationPrefix();
            }
            $redisekConfig["model"]["keyPrefix"] = $modelKeyPrefix;


            $server->setConfig($redisekConfig);
            $this->_server = $server;

        }
        return $this->_server;
    }




}
