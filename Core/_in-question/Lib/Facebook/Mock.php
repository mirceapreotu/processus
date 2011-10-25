<?php
/**
 * Lib_Facebook_Mock Class
 *
 * @package Lib_Facebook
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Mock
 *
 *
 * @package Lib_Facebook
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Mock
{


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
    * @var Zend_Config
    */
    protected $_config;
    /**
    * override in subclass or inject using Zend_Config
    * @var array
    */
    protected $_configDefault = array(

        "mock" => array(
            "enabled" => null,
            "session" => null, //'{"access_token":"161026780619431|2.lT6fHigIovyj_VDjR8QsQg__.3600.1300712400-100001680154141|-c9zLjVACoJUXaJ4qsOMfKLHxLw","expires":"1300712400","sig":"5d100e7536637b08dbc76042353cbee1","uid":"100001680154141"}';
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



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * @throws Exception
     * @return
     */
    public function getMockConfig()
    {
        $config = $this->getConfig()->mock;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.mock at ".__METHOD__);
        }
        return $config;
    }


    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }

    /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $debug = Bootstrap::getRegistry()->getDebug();
        // just use that on local host
        if ($debug->isServerStageDevelopment()!==true) {
            return false;
        }

        $result = $this->getMockConfig()->enabled;

        return (bool)($result===true);
    }

    /**
     * @return string|array|null
     */
    public function getSessionData()
    {
        $result = $this->getMockConfig()->session;

        if (
                ($result === null)
                ||(is_string($result))
                ||(is_array($result)
                ||($result instanceof Zend_Config)))
        {
            if ($result instanceof Zend_Config) {
                /**
                 * @var Zend_Config $result
                 */
                $result = $result->toArray();
            }
            return $result;
        }

        throw new Exception("Invalid config.mock.session at ".__METHOD__);
    }


    /**
     * @return bool
     */
    public function hasSessionData()
    {
        $sessionData = $this->getSessionData();
        if (Lib_Utils_Array::isEmpty($sessionData) !== true) {
            return true;
        }
        if (Lib_Utils_String::isEmpty($sessionData) !== true) {
            return true;
        }
        return false;
    }


    /**
     * @NOTICE: YOU MUST NOT access facebook.getUser() before applying mockData
     * @throws Exception
     * @return
     */
    public function applySessionData()
    {
        if ($this->isEnabled() !== true) {
            throw new Exception(
                "Mocking is not enabled! at "
                        . __METHOD__. " for ".get_class($this)
            );
        }

        $sessionData = $this->getSessionData();

        // that will set the session in the facebook client
        // if invalid - the facebook.setSession(null)
        $facebook = $this->getFacebook();
        //$facebook->getSessionRestore($sessionData);

        $signedRequest = Lib_Utils_Array::getProperty(
            $sessionData, "signed_request"
        );


        try {
            $signedRequestParsed = $this->getFacebook()->parseSignedRequest(
                  $signedRequest
            );

            // var_dump($signedRequestParsed);
        } catch (Exception $e) {

        }


        $facebook->setSignedRequest($signedRequest);

        return;

    }


	
}
