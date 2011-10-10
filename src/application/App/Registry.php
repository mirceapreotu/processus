<?php
/**
 * App_Registry
 *
 *
 *
 * @category    meetidaaa.com
 * @package        App
 *
 * @copyright    Copyright (c) 2011 meetidaaa.com
 * @license        http://meetidaaa.com/license/default
 * @version        $Id:$
 */

/**
 * App_Registry
 *
 *
 *
 * @category    meetidaaa.com
 * @package        App
 *
 * @copyright    Copyright (c) 2011 meetidaaa.com
 * @license        http://meetidaaa.com/license/default
 * @version        $Id:$
 */

class App_Registry extends Lib_Application_Registry
{


    /**
     * @var App_Model_Bo_User
     */
    protected $_viewerBO;

    /**
     * @var App_Model_Bo_CmsUser
     */
    protected $_cmsViewerBO;

    /**
     * @var bool
     */
    protected $_isCmsContext = false;

    /**
     * @var App_GaintS_Config_GaintSConfig
     */
    protected $_gaintSConfig;

    /**
     * @return App_GaintS_Config_GaintSConfig
     */
    public function getGaintSConfig()
    {
        if (!$this->_gaintSConfig) {
            $this->_gaintSConfig = new App_GaintS_Config_GaintSConfig();
            $this->_gaintSConfig->setData((array)Zend_Registry::get('CONFIG')->gaintSConfig);
        }

        return $this->_gaintSConfig;
    }

    // +++++++++++++++ Date & Time ++++++++++++++++++++++

    /**
     * @var string
     */
    protected $_currentDateMocked;

    /**
     * @return string|null
     */
    public function getCurrentDate()
    {
        $format = 'Y-m-d H:i:s'; //DO NOT CHANGE THIS!
        $currentDate = $this->getRealDate();

        // mocking ?
        $mockCurrentDateEnabled = false;

        if ($this->isDebugMode() !== true) {
            $mockCurrentDateEnabled = false;
        }


        if ($mockCurrentDateEnabled !== true) {
            return $currentDate;
        }

        $mockedDate = $this->_currentDateMocked;
        if (Lib_Utils_String::isEmpty($mockedDate) !== true) {
            return $mockedDate;
        }

        // try load current date from db
        $dao = App_Model_Dao_AppConfig::getInstance();
        $mockedDate = $dao->loadValue(
            App_Model_Dao_AppConfig::KEY_CURRENTDATE
        );
        if (Lib_Utils_Date::exists($mockedDate, $format)) {
            $this->_currentDateMocked = $mockedDate;
            return $this->_currentDateMocked;
        }

        // fallback: return the real date
        return $currentDate;
    }


    /**
     * @return string
     */
    public function getRealDate()
    {
        $format = 'Y-m-d H:i:s'; //DO NOT CHANGE THIS!
        $currentDate = date($format);
        return $currentDate;
    }


    // ++++++++++++++ Application ++++++++++++++++++++++++

    /**
     * @param  string $path
     * @return string
     */
    public function getSrcPath($path)
    {
        //throw new Exception("THIS METHOD HAS NOT BEEN TESTED BEFORE! ".__METHOD__);
        $result = SRC_PATH;
        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;
        return $result;
    }

    /**
     * @param  string $path
     * @return string
     */
    public function getHtdocsPath($path)
    {
        //throw new Exception("THIS METHOD HAS NOT BEEN TESTED BEFORE! ".__METHOD__);
        $srcPath = $this->getSrcPath("");
        $result = dirname($srcPath) . "/htdocs";
        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;

        return $result;
    }

    /**
     * @param  string $path
     * @return string
     */
    public function getVarPath($path)
    {
        //throw new Exception("THIS METHOD HAS NOT BEEN TESTED BEFORE! ".__METHOD__);
        $srcPath = $this->getSrcPath("");
        $result = dirname($srcPath) . "/var";
        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;

        return $result;
    }

    /**
     * @param  string $path
     * @return string
     */
    public function getVarUploadPath($path)
    {
        //throw new Exception("THIS METHOD HAS NOT BEEN TESTED BEFORE! ".__METHOD__);
        $varPath = $this->getVarPath("");


        $result = $varPath . "/upload";

        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;

        return $result;
    }

    /**
     * override
     * @return string
     */
    public function getApplicationPrefix()
    {
        $prefix = $this->getConfig()->applicationPrefix;
        if (Lib_Utils_String::isEmpty($prefix)) {
            throw new Exception("Method returns invalid result! " . __METHOD__);
        }

        $prefix = str_replace(".", "___", $prefix);

        if (strpos($prefix, " ") !== false) {
            throw new Exception(
                "Method returns invalid result! No whitespace allowed at "
                . __METHOD__);
        }


        return $prefix;
    }

    /**
     * @throws Exception
     * @return object
     */
    public function getServerStage()
    {
        $stageConfig = $this->getConfig()->serverStage;
        $stage = $stageConfig->stage;

        if (Lib_Utils_String::isEmpty($stage)) {
            throw new Exception("Method returns invalid result! " . __METHOD__);
        }
        return $stageConfig;
    }

    /**
     * @throws Exception
     * @return object
     */
    public function getServerStageHost()
    {
        $stageConfig = $this->getConfig()->serverStage;
        $stage = $stageConfig->stage;

        if (Lib_Utils_String::isEmpty($stage)) {
            throw new Exception("Method returns invalid result! " . __METHOD__);
        }


        return $stageConfig->host;
    }


    /**
     * @param  string $stageName
     * @return
     */
    public function isServerStage($stageName)
    {
        return ($this->getServerStage()->stage === $stageName);
    }


    /**
     * @param  string $url
     * @return string
     */
    public function getUrl($url)
    {
        // TODO: make protocoll dynamic
        $protocol = 'http';
        $host = $this->getServerStage()->host;

        $url = trim($url);
        if (Lib_Utils_String::startsWith($url, "/", true)) {
            return $protocol . "://" . $host . "" . $url;
        }
        return $protocol . "://" . $host . "/" . $url;

    }


    // +++++++++++++++++ Memcached ++++++++++++++++++++++++

    /**
     * The default memcached
     * @return Lib_Cache_Impl_Memcached
     */
    public function getMemcached()
    {
        $key = 'Memcached';
        $value = $this->_getProperty($key);

        if (($value instanceof Lib_Cache_Impl_Memcached) !== true) {

            $config = $this->getConfig();
            $params = null; // use defaults

            if (isset($config->cache)) {

                $params = $config->cache->params->backend->toArray();
            }

            $value = new Lib_Cache_Impl_Memcached($params);
            $this->_setProperty($key, $value);
        }
        $value = $this->_getProperty($key);
        return $value;
    }

    /**
     * The default memcached
     * @return Lib_Cache_Impl_Session
     */
    public function getMemcachedSession()
    {
        $key = 'MemcachedSession';
        $value = $this->_getProperty($key);

        if (($value instanceof Lib_Cache_Impl_Session) !== true) {

            $config = $this->getConfig();
            $params = null; // use defaults

            if (isset($config->cache)) {

                $params = $config->cache->params->backend->toArray();
            }

            $value = new Lib_Cache_Impl_Session($params);

            $this->_setProperty($key, $value);
        }
        $value = $this->_getProperty($key);
        return $value;
    }

    // ++++++++++++++++++++ Session ++++++++++++++++++++++++

    /**
     * @return Lib_Application_Session
     */
    public function getSession()
    {
        $key = 'Session';
        $value = $this->_getProperty($key);

        if (($value instanceof Lib_Application_Session) !== true) {

            $value = new Lib_Application_Session();
            $this->_setProperty($key, $value);
            $value->init();
            $value->start();
        }
        $value = $this->_getProperty($key);
        return $value;
    }

    // +++++++++++++++++ Debugger ++++++++++++++++++++++++

    /**
     * @var App_Debug
     */
    protected $_debug;

    /**
     * @return App_Debug
     */
    public function getDebug()
    {
        if (($this->_debug instanceof App_Debug) !== true) {
            $this->_debug = App_Debug::getInstance();
        }
        return $this->_debug;
    }

    /**
     * @throws Exception
     * @param  null|App_Debug $debug
     * @return
     */
    public function setDebug($debug)
    {
        if ($debug === null) {
            $this->_debug = null;
            return;
        }

        if (($debug instanceof App_Debug) !== true) {
            throw new Exception("Invalid parameter 'debug' at " . __METHOD__);
        }

        // check core functions work ...
        try {
            $debug->test();

        } catch (Exception $e) {
            throw new Exception(
                "Error while injecting debug class! " . get_class($debug)
                . " at " . __METHOD__
            );
        }

        $this->_debug = $debug;
    }


    /**
     * e.g. FOR RPC DEFAULT ERROR HANDLING/LOGGING
     * @TODO: check userAgent
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->getDebug()->isDebugMode();
    }

    // +++++++++++++++++++++++++ MISC / Application +++++++++++++++


    // ++++++++++++++++++++++++ DB ++++++++++++++++++

    /**
     * @return Lib_Db_Xdb_Client
     */
    public function getXdbClient()
    {
        $key = 'XDB';
        $value = $this->_getProperty($key);

        if (($value instanceof Lib_Db_Xdb_Client) !== true) {

            $value = new Lib_Db_Xdb_Client();
            $this->_setProperty($key, $value);
        }
        $value = $this->_getProperty($key);
        return $value;
    }

    /**
     *
     * @return Zend_Db_Adapter_Pdo_Mysql|null
     */
    public function getDb()
    {
        $key = 'DB';
        $value = $this->_getProperty($key);
        return $value;

    }

    /**
     *
     * @return Zend_Db_Adapter_Pdo_Mysql|null
     */
    public function getSlaveDb()
    {
        $key = 'DBSLAVE';
        $value = $this->_getProperty($key);

        if ($value === null) {

            // return master, if no slave is set
            return $this->getDb();
        }

        return $value;

    }


    // +++++++++++ Model +++++++++++++++++
    /**
     * @return App_Model_Bo_User
     */
    public function getViewerBO()
    {
        if (($this->_viewerBO instanceof App_Model_Bo_User) !== true) {
            $this->_viewerBO = new App_Model_Bo_User();
        }
        return $this->_viewerBO;
    }

    /**
     * @param App_Model_Bo_User $userBO
     * @return void
     */
    public function setViewerBO(App_Model_Bo_User $userBO)
    {
        $this->_viewerBO = $userBO;
    }

    /**
     * @return App_Model_Bo_CmsUser
     */
    public function getCmsViewerBO()
    {
        if (($this->_cmsViewerBO instanceof App_Model_Bo_CmsUser) !== true) {
            $this->_cmsViewerBO = new App_Model_Bo_CmsUser();
        }
        return $this->_cmsViewerBO;
    }

    /**
     * @param App_Model_Bo_User $cmsUserBO
     * @return void
     */
    public function setCmsViewerBO(App_Model_Bo_CmsUser $cmsUserBO)
    {
        $this->_cmsViewerBO = $cmsUserBO;
    }

    /**
     * @return bool
     */
    public function isCmsContext()
    {
        return (bool)($this->_isCmsContext === true);
    }

    /**
     * @throws Exception
     * @param  bool $value
     * @return void
     */
    public function setIsCmsContext($value)
    {
        if (is_bool($value) !== true) {
            throw new Exception(
                "Parameter value must be a bool at " . __METHOD__
            );
        }
        $this->_isCmsContext = true;
    }


    public function getMembaseDataBucketByName($bucketName)
    {
        var_dump($this->getGaintSConfig());
    }
}
