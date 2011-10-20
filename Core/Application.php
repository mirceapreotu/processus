<?php
/**
 * App_Application
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

class Core_Application extends Lib_Application
{

    // +++++++++++++ singleton factory +++++++++++++++++++

    /**
     * @var App_Application
     */
    private static $_instance;

    /**
     * @static
     * @return App_Application
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            $instance = new self();
            self::$_instance = $instance;
            $instance->init();
        }
        return self::$_instance;
    }


    /**
     * @return void
     */
    public function init()
    {

    }


    // +++++++++++++++++++ profiler ++++++++++++++++++++++++++++

    /**
     * @var Lib_Profiler_Profiler
     */
    protected $_profiler;

    /**
     * @var Lib_Profiler_Profiler
     */
    protected $_profilerRoot;

    /**
     * @return Lib_Profiler_Profiler
     */
    public function getProfilerRoot()
    {
        if (($this->_profilerRoot instanceof Lib_Profiler_Profiler)!==true) {
            $profiler = new Lib_Profiler_Profiler(__METHOD__);
            $this->_profilerRoot = $profiler;
            //$profiler->start();
            //$profiler->stop();
        }

        return $this->_profilerRoot;
    }

    /**
     * @return Lib_Profiler_Profiler
     */
    public function getProfiler()
    {
        $root = $this->getProfilerRoot();

        if (($this->_profiler instanceof Lib_Profiler_Profiler)!==true) {
            $this->_profiler = $root;
        }
        return $this->_profiler;
    }
    public function setProfiler(Lib_Profiler_Profiler $value)
    {
        $this->_profiler = $value;
    }

    /**
     * @param  string $method
     * @return Lib_Profiler_Profiler
     */
    public function profileMethodStart($method)
    {
        $profiler = $this->getProfiler()->createAndAddChild($method, true);
        $this->setProfiler($profiler);
        return $profiler;
    }

    // ++++++++++++++ db +++++++++++++++++++++++++++++

    /**
     * @return Lib_Db_Xdb_Client
     */
    public function getDbClient()
    {
        //return Bootstrap::getRegistry()->getXdbClient();
        throw new Exception("Implement in subclass ".__METHOD__);
    }

    // ++++++++++++ date +++++++++++++++++++++++++++++
    /**
     * @return null|string
     */
    public function getCurrentDate()
    {
        return Bootstrap::getRegistry()->getCurrentDate();
    }
    /**
     * @return string
     */
    public function getRealDate()
    {
        return Bootstrap::getRegistry()->getRealDate();
    }

    // ++++++++++ session +++++++++++++++++++++++++++++


    /**
     * @return App_Vz_Session
     */
    public function getSession()
    {
        throw new Exception("Implement in subclass! ".__METHOD__);
    }


    // ++++++++++ debug ++++++++++++++++++++++++++++++++++

    /**
     * @return App_Debug
     */
    public function getDebug()
    {
        return Bootstrap::getRegistry()->getDebug();
    }

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->getDebug()->isDebugMode();
    }

    // +++++++++ log +++++++++++++++++++++++++++++++++++++
    /**
     * @return Zend_Log
     */
    public function getLogger()
    {
        return Zend_Registry::get('LOG');
    }


    // ++++++++++ url +++++++++++++++++++++++++++++++++++

     /**
	 * @param  string $url
	 * @return string
	 */
	public function getUrl($url)
	{
		return $this->getGlobalUrl($url);
	}

    /**
     * @param  string $url
     * @return string
     */
    public function getUrlGlobal($url)
    {

        $protocol = Lib_Url_Uri::SCHEME_HTTP;
        /*
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'
          ? 'https'
          : 'http';

        */

		$hostHttp = $this->getUrlHostHttpFromConfig();
        $hostHttps = $this->getUrlHostHttpsFromConfig();

        $host = $hostHttp;


        $url = trim("".$url);
        if (Lib_Utils_String::startsWith($url,"/",true)) {
            $url = $protocol."://".$host."".$url;
        } else {
            $url = $protocol."://".$host."/".$url;
        }


        $zendUri = new Lib_Url_Uri();
        if ($zendUri->isValidUri($url)!==true) {
            throw new Exception(
                "Invalid parameter 'url' is invalid uri at ".__METHOD__
            );
        }

        $zendUri->setUri($url);


        $useHttps=false;

        $protocolFromRequest = isset(
                               $_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'
          ? 'https'
          : 'http';
        $hostFromRequest = null;
        if ((isset($_SERVER)) && (isset($_SERVER["HTTP_HOST"]))) {
            $hostFromRequest = $_SERVER["HTTP_HOST"];
        }

        $protocol = $protocolFromRequest;
        //$hostHttps = "ssl-fb-five-gum-app.exitb.de";
        if (
                (trim(strtolower($hostFromRequest)))
                === (trim(strtolower($hostHttps)))
        ) {
            $protocol = "https";
        }

        if (Lib_Utils_String::isEmpty($hostFromRequest)!==true) {
            $zendUri->setHost($hostFromRequest);
        }
        $url = $zendUri->toString($protocol);

        //throw new Exception("url=".$url);
        return $url;


        //return $url;
    }

    /**
     * @param  string $url
     * @return void
     */
    public function getUrlLocal($url)
    {
        throw new Exception("Implement in subclass at ".__METHOD__);
    }

    /**
     * @return string
     */
    public function geUrlCurrent()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'
          ? 'https://'
          : 'http://';

        $currentUrl = $protocol
                      . $_SERVER['HTTP_HOST']
                      . $_SERVER['REQUEST_URI'];
        //$currentUrlParts = parse_url($currentUrl);
        return $currentUrl;
    }


    /**
     * @return string|null
     */
    public function getUrlHostHttpFromConfig()
    {


        $result= Bootstrap::getRegistry()->getServerStage()->host;
        if ($result === null) {
            return $result;
        }
        if (Lib_Utils_String::isEmpty($result)) {
            throw new Exception(
                "Invalid config.serverStage.host at ".__METHOD__
            );
        }
        $result = trim($result);
        return $result;
    }
    /**
     * @return string|null
     */
    public function getUrlHostHttpsFromConfig()
    {


        $result= Bootstrap::getRegistry()->getServerStage()->hostHttps;
        if ($result === null) {
            return $result;
        }
        if (Lib_Utils_String::isEmpty($result)) {
            throw new Exception(
                "Invalid config.serverStage.hostHttps at ".__METHOD__
            );
        }
        $result = trim($result);
        return $result;
    }


    /**
     * @throws Exception
     * @param  $url
     * @return string
     */
    public function newUrlHttps($url)
    {
        if (is_string($url)!==true) {
            throw new Exception("Invalid parameter 'url' at ".__METHOD__);
        }

        $zendUri = new Lib_Url_Uri();
        if ($zendUri->isValidUri($url)!==true) {
            throw new Exception(
                "Invalid parameter 'url' is not a valid uri at ".__METHOD__
            );
        }

        $hostHttp = $this->getUrlHostHttpFromConfig();
        $hostSSL = $this->getUrlHostHttpsFromConfig();
        $host = $zendUri->getHost();
        if (Lib_Utils_String::isEmpty($hostSSL)!==true) {

            $zendUri->setHost($hostSSL);
        }

        return $zendUri->toString(Lib_Url_Uri::SCHEME_HTTPS);
    }

    /**
     * @throws Exception
     * @param  $url
     * @return string
     */
    public function newUrlHttp($url)
    {
         if (is_string($url)!==true) {
            throw new Exception("Invalid parameter 'url' at ".__METHOD__);
         }

         $zendUri = new Lib_Url_Uri();
         if ($zendUri->isValidUri($url)!==true) {
             throw new Exception(
                 "Invalid parameter 'url' is not a valid uri at ".__METHOD__
             );
         }

         $hostHttp = $this->getUrlHostHttpFromConfig();
         $hostSSL = $this->getUrlHostHttpsFromConfig();
         $host = $zendUri->getHost();
         if (Lib_Utils_String::isEmpty($hostHttp)!==true) {

             $zendUri->setHost($hostHttp);
         }

         return $zendUri->toString(Lib_Url_Uri::SCHEME_HTTP);
    }



    // ++++++++++ directories +++++++++++++++++++++++++++++

    /**
     * @param  string|null $path
     * @return string
     */
    public function getSrcPath($path = null)
    {
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


    // +++++++++++ browser info +++++++++++++++++++++++++++++
    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return new Browser();
    }

    /**
     * @return string
     */
    public function getBrowserName()
    {
        return $this->getBrowser()->getBrowser();
    }


    // +++++++++++ mvc +++++++++++++++++++++++++++++++++++++
    /**
     * @return App_Dispatcher
     */
    public function getDispatcher()
    {
        $dispatcher = new App_Dispatcher();
        return $dispatcher;
    }








}
