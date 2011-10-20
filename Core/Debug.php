<?php
/**
 * Core_Debug Class
 *
 * @category	meetidaaa.com
 * @package		App
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * Core_Debug
 *
 * @category	meetidaaa.com
 * @package		App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 */
class Core_Debug
{


    /**
     * @static
     * @var App_Debug
     */
    private static $_instance;

    /**
     * @static
     * @return App_Debug
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self) !== true) {

            $instance= new self();
            $instance->test();
            self::$_instance = $instance;
        }
        return self::$_instance;
    }

     /**
     * @var Zend_Config
     */
    protected $_config;


    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(

        "enabled" => true,
        "dumpVar" => array(
            "enabled" => true,
        ),
        "firebug" => array(
            "enabled" => true,
        ),

        "developers" => array(
            "enabled" => true, // (false ... noone is a developer)
            // if "clients" === null (anyone is a developer)
            "clients" => array(
                // define a client
                /*
                array(
                    "enabled" => true,

                    // (ip: null - dont check ip,
                    //  any ip will be treated as developer)
                    "ip" => array(
                        '123.220.99.191',
                        "456.79.188.214",

                    ),
                    // (ua: null - dont check ua,
                    // any ua will be treated as developer)
                    "userAgent" => null,//e.g. 'x-meetidaaa-developer'
                ),

                // define a client
                array(
                    "enabled" => true,
                    // stage is mandatory
                    // (ip: null - dont check ip,
                    //  any ip will be treated as developer)
                    "ip" => null,
                    // (ua: null - dont check ua,
                    // any ua will be treated as developer)
                    "userAgent" => null,//e.g. 'x-meetidaaa-developer'
                ),
                */
            ),

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

    /**
     * @return Zend_Config
     */
    public function getConfigDefault()
    {
        return new Zend_Config((array)$this->_configDefault, true);
    }



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


    }


    /**
     * your hooks here
     * @return void
     */
    protected function _onConfigParsed()
    {
        $config = $this->getConfig();

    }


    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @return void
     */
    public function setAsGlobal()
    {
        $debug = $this;
        Bootstrap::getRegistry()->setDebug($debug);
    }


    // AND FINALLY .... isDebugMode ?
    /**
     * @var bool
     */
    protected $_isDebugMode;








    /**
     * Is debugging enabled at all?
     * @return bool
     */
    public function isEnabled()
    {

        return (bool)($this->getConfig()->enabled === true);

    }


    /**
     * @return string
     */
    public function getServerStageName()
    {
        return Bootstrap::getRegistry()->getServerStage()->stage;
    }

    /**
     * @return bool
     */
    public function isServerStageProduction()
    {
        return (bool)Bootstrap::getRegistry()->isServerStage(
            Bootstrap::SERVER_STAGE_PRODUCTION
        );
    }

    /**
     * @return bool
     */
    public function isServerStageTesting()
    {
        return (bool)Bootstrap::getRegistry()->isServerStage(
            Bootstrap::SERVER_STAGE_TESTING
        );
    }

    /**
     * @return bool
     */
    public function isServerStageDevelopment()
    {
        return (bool)Bootstrap::getRegistry()->isServerStage(
            Bootstrap::SERVER_STAGE_DEVELOPMENT
        );
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++




    /**
     * @return null|Zend_Config
     */
    public function getDevelopers()
    {
        $developers = $this->getConfig()->developers;
        if (($developers instanceof Zend_Config) !== true) {
            $developers = null;
        }
        return $developers;
    }





    /**
     * @var bool
     */
    protected $_isDeveloper;



    /**
     * @param Zend_Config $client
     * @return bool
     */
    protected function _checkIsClientDeveloperByIp(Zend_Config $client)
    {
        $result = false;

        $userIp = null;
        if (isset($_SERVER)) {
            $userIp = Lib_Utils_Array::getProperty($_SERVER,"REMOTE_ADDR");
        }

        if (is_string($userIp)!==true) {
            return $result;
        }

        $config = $client->ip;
        if (($config instanceof Zend_Config)!==true) {
           return $result;
        }

        /**
        * @var Zend_Config $config
        */
        $whitelist = $config->whitelist;
        $blacklist = $config->blacklist;

        $filter = new Lib_Fnmatch_Filter();
        $filter->setWhitelist($whitelist);
        $filter->setBlacklist($blacklist);

        $isWhitelisted = $filter->isWhitelisted($userIp, null);
        $isBlacklisted = $filter->isBlacklisted($userIp, null);

        $result = (bool)(($isWhitelisted===true) && ($isBlacklisted !== true));
        return $result;

    }


        /**
     * @param Zend_Config $client
     * @return bool
     */
    protected function _checkIsClientDeveloperByUserAgent(Zend_Config $client)
    {
        $result = false;

        $userAgent = null;
        if (isset($_SERVER)) {
            $userAgent = Lib_Utils_Array::getProperty(
                $_SERVER,"HTTP_USER_AGENT"
            );
        }

        if (is_string($userAgent)!==true) {
            return $result;
        }

        $config = $client->userAgent;
        if (($config instanceof Zend_Config)!==true) {
           return $result;
        }

        /**
        * @var Zend_Config $config
        */
        $whitelist = $config->whitelist;
        $blacklist = $config->blacklist;

        $filter = new Lib_Fnmatch_Filter();
        $filter->setWhitelist($whitelist);
        $filter->setBlacklist($blacklist);

        $isWhitelisted = $filter->isWhitelisted($userAgent, null);
        $isBlacklisted = $filter->isBlacklisted($userAgent, null);

        $result = (bool)(($isWhitelisted===true) && ($isBlacklisted !== true));
        return $result;

    }




    /**
     * @param Zend_Config $client
     * @return bool
     */

    protected function _checkIsClientDeveloperByKey(
        Zend_Config $client, $key, $value
    )
    {
        $result = false;


        $config = $client->$key;
        if (($config instanceof Zend_Config)!==true) {
           return $result;
        }

        /**
        * @var Zend_Config $config
        */
        $whitelist = $config->whitelist;
        $blacklist = $config->blacklist;

        $filter = new Lib_Fnmatch_Filter();
        $filter->setWhitelist($whitelist);
        $filter->setBlacklist($blacklist);

        $isWhitelisted = $filter->isWhitelisted($value, null);
        $isBlacklisted = $filter->isBlacklisted($value, null);

        $result = (bool)(($isWhitelisted===true) && ($isBlacklisted !== true));
        return $result;

    }



    /**
     * @param Zend_Config $client
     * @return bool
     */
    protected function _checkIsClientDeveloper(Zend_Config $client)
    {
        $result = false;
        if ($client->enabled !== true) {
            return $result;
        }

        $isDeveloperByIp = false;
        $isDeveloperByUserAgent = false;


        // check client.ip

        if (isset($_SERVER)) {
            $isDeveloperByIp = $this->_checkIsClientDeveloperByIp($client);
            $isDeveloperByUserAgent = $this->_checkIsClientDeveloperByUserAgent(
                $client
            );
        }


        $result = (bool)(
                ($isDeveloperByIp===true)
                && ($isDeveloperByUserAgent===true)
        );

        return $result;

    }


    /**
     * @return bool
     */
    public function isDeveloper()
    {
        if (is_bool($this->_isDeveloper)) {
            return $this->_isDeveloper;
        }

        $this->_isDeveloper = false;

        $developers = $this->getDevelopers();
        if (($developers instanceof Zend_Config)!==true) {
            return false;
        }
        if ($developers->enabled !== true) {
            return false;
        }


        /**
         * @var Zend_Config $developers
         */
        $clients = $developers->clients;
        if (($clients instanceof Zend_Config)!==true) {
            return false;
        }

        /**
         * @var Zend_Config $clients
         */


        foreach($clients as $client)
        {

            if (($client instanceof Zend_Config)!==true) {
                continue;
            }

            /**
            * @var Zend_Config $client
            */

            if ($client->enabled !== true) {
                continue;
            }


            if ($this->_checkIsClientDeveloper($client)===true) {
                $this->_isDeveloper = true;
                return ($this->_isDeveloper === true);
            }


        }


        return false;


    }







    // +++++++++++++++++++++++++++++++++++++++++++++++++++++



    /**
     * @see: serverStage and developer clients config
     * @return bool
     */
    public function isDebugMode()
    {
        if ($this->isEnabled() !== true) {
            return false;
        }
        if (is_bool($this->_isDebugMode)) {
            return $this->_isDebugMode;
        }

        $isDeveloper = $this->isDeveloper();
        $this->_isDebugMode = (bool)($isDeveloper === true);

        return $this->_isDebugMode;
    }


    /**
     * @return bool
     */
    public function isFirebugEnabled()
    {
        if ($this->isDebugMode() !== true) {
            return false;
        }
        return (bool)($this->getConfig()->firebug->enabled === true);
    }


    /**
     * @return bool
     */
    public function isDumpVarEnabled()
    {
        if ($this->isDebugMode() !== true) {
            return false;
        }
        return (bool)($this->getConfig()->dumpVar->enabled === true);
    }

    /**
     * @param string $method
     * @param  mixed $var
     * @param bool $exit
     * @return void
     */
    public function dumpVar($method, $var, $exit = false)
    {

        if ($this->isDumpVarEnabled() !== true) {
            return;
        }

        $out = array(
                "METHOD" => $method,
                //"EXIT" => $exit,
                "var" => $var,
            );

        if ($exit === true) {
            $out["EXIT"] = $exit;
        }

        var_dump($out);

        /*
        $traces = debug_backtrace();
        foreach($traces as $trace) {
            var_dump($this->getStackTraceMethodQualifiedName($trace));
        }

         */


        if ($exit !== true) {
            return;
        }

        $exitInfo = array(
            "EXIT AT ".__METHOD__,
        );
        $traces = debug_backtrace();

        $exitInfo["stackTrace"] = array();
        foreach($traces as $trace) {
            //var_dump($this->getStackTraceMethodQualifiedName($trace));
            $method = $this->getStackTraceMethodQualifiedName($trace);
            if ($method !== null) {
                $exitInfo["stackTrace"][] = $method;
                continue;
            }


        }

        var_dump($exitInfo);
        exit;
    }




    /**
     * @param  $trace
     * @return null|string
     */
    public function getStackTraceMethodQualifiedName($trace)
    {
        if (is_array($trace) !== true) {
            return null;
        }


        $class = null;
        $function = null;
        $object = null;

        if (isset($trace['function'])) {
            $function = $trace['function'];
        }
        if (isset($trace['class'])) {
            $class = $trace['class'];
        }
        if (isset($trace['object'])) {
            $object = $trace['object'];
        }

        $objectClass = null;
        if (is_object($object)) {
            $objectClass = get_class($object);
            if (is_string($objectClass) !== true) {
                $objectClass = null;
            }
        }


        $method = "";
        if (is_string($objectClass)) {
            $method .= $objectClass;
        } else {
            if (is_string($class)) {
                $method .= $class;
            }
        }
        if (is_string($function)) {
            $method .= "::".$function;
        }


        if (strlen($method)>0) {
            return $method;
        }


        return null;
    }




    /**
     * @throws Exception
     * @return void
     */
    public function test()
    {
        try {
            $isDeveloper = $this->isDeveloper();
            $isDebugMode = $this->isDebugMode();
            $enabled = $this->isEnabled();
        } catch(Exception $e) {
            throw new Exception(
                "App_Debug.test() failed at ".__METHOD__
                ." for ".get_class($this)
            );
        }
    }

}
