<?php
/**
 * Bootstrap Class
 *
 * @category    lib
 * @package     Default
 * @copyright   Copyright (c) 2010 exitb GmbH (http://exitb.de)
 * @license     http://exitb.de/license/default     Default License
 * @version     $Id$
 */

require_once dirname(__FILE__).'/functions.php';

/**
 * Bootstrap Class
 *
 * @category    lib
 * @package     Default
 * @copyright   Copyright (c) 2010 exitb GmbH (http://exitb.de)
 * @license     http://exitb.de/license/default     Default License
 * @version     $Id$
 */
class Bootstrap
{

    const ERROR_SHUTDOWN = "ERROR_BOOTSTRAP_SHUTDOWN";


	const LOG_TYPE_HTTPD    = 'httpd';
	const LOG_TYPE_CLI      = 'cli';

    // server stages
    const SERVER_STAGE_PRODUCTION = "production"; // live
    const SERVER_STAGE_TESTING = "testing"; // payground
    const SERVER_STAGE_DEVELOPMENT = "development"; // local

	protected $_isInitialized = false;

    /**
     * @var Bootstrap
     */
    private static $_instance;



    /**
     * @var App_Registry
     */
    protected $_registry;

    /**
     * @static
     * @var App_Debug
     */
    //private static $_debug;

    /**
     * @static
     * @return App_Debug
     */
    /*
    public static function getDebug()
    {
        if ((self::$_debug instanceof App_Debug)!==true) {
            self::$_debug = App_Debug::getInstance();
        }

        if ((self::$_debug instanceof App_Debug)!==true) {
            die("Bootstrap.getDebug failed!");
        }

        return self::$_debug;
    }

    */
    /**
     * @static
     * @throws Exception
     * @param  null|App_Debug $debug
     * @return
     */
    /*
    public static function setDebug($debug)
    {
        if ($debug === null) {
            self::$_debug = null;
            return;
        }

        if (($debug instanceof App_Debug)!==true) {
            throw new Exception("Parameter 'debug' invalid at ".__METHOD__);
        }

        self::$_debug = $debug;
    }
    */



    /**
     * @static
     * @return Bootstrap
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    /**
     * @static
     * @return App_Registry
     */
    public static function getRegistry()
    {

        $instance = self::getInstance();

        if (($instance->_registry instanceof App_Registry) !== true) {
            $instance->_registry = new App_Registry();
        }

        $result = $instance->_registry;
        return $result;
    }


	/**
	 * Initializes the logging subsystem
	 *
	 * @param string $type LOG_TYPE_HTTPD|LOG_TYPE_CLI
 	 * @return boolean true on success
	 *
	 */
	private function _initLogging($type)
	{

		$writer_default = new Lib_Log_Writer_DailyStream(
			SRC_PATH.'/../var/log/app/'.$type.'_default.log'
		);
		$writer_critical = new Lib_Log_Writer_DailyStream(
			SRC_PATH.'/../var/log/app/'.$type.'_critical.log'
		);

		$format =
			'%timestamp%;%pid%;%priorityName%;%priority%;%message%'.PHP_EOL;

		$formatter = new Zend_Log_Formatter_Simple($format);

		$writer_default->setFormatter($formatter);
		$writer_critical->setFormatter($formatter);

		$filter_critical = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
		$writer_critical->addFilter($filter_critical);

		$logger = new Zend_Log();
		$logger->addWriter($writer_default);
		$logger->addWriter($writer_critical);

		// to better distinguish entries of different processes, add the pid:
		$logger->setEventItem('pid', getmypid());

		Zend_Registry::set('LOG',$logger);

		if ($type == self::LOG_TYPE_HTTPD) {

			$writer = new Zend_Log_Writer_Firebug();
			$logger_firebug = new Zend_Log($writer);
			Zend_Registry::set('FIREBUG',$logger_firebug);
		}

		return true;
	}

	/**
	 * Initializes the system
	 *
	 * @param string $mode the mode for which to initialize
	 *
	 * @return boolean true on success
	 *
	 */
	public static function init($mode='DEFAULT')
	{

        try {
            if (self::getInstance()->_isInitialized){
                return;
            }

            self::getInstance()->_isInitialized=true;

            ini_set('display_errors', true);
            error_reporting(E_ALL | E_STRICT);
            set_error_handler(array('Bootstrap', 'handleError'));
            register_shutdown_function(array('Bootstrap', 'handleShutdown'));
            set_exception_handler(array('Bootstrap','handleUncaughtException'));
            // we have the all error handlers initialized ...
            ini_set('display_errors', false);

            define('SRC_PATH', realpath(dirname(__FILE__)));
            define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));

            // setup include path:

            $origIncludePath = get_include_path();

            set_include_path(
                SRC_PATH . '/contrib' . PATH_SEPARATOR .
                SRC_PATH . '/application'
            );

            // setup autoloader:
            spl_autoload_register(array('Bootstrap', 'autoLoad'));

            // load basic config and put it in registry

            $config = new Zend_Config(require SRC_PATH.'/../etc/config.php');
            Zend_Registry::set('CONFIG',$config);

            // setup locale:

            setlocale(LC_ALL, $config->locale->default->lc_all);

            date_default_timezone_set(
                $config->locale->default->timezone
            );

            $instance = self::getInstance();

            switch ($mode) {

                case 'INSTALL':

                    break;

                case 'TEST':

                    set_include_path(
                        get_include_path().PATH_SEPARATOR.
                        $origIncludePath
                    );
                    break;

                case 'TASK':
                    // setup logger und put in registry

                    //$instance->_initLogging(self::LOG_TYPE_CLI);
                    break;

                case 'DEFAULT':
                default:

                    // setup logger und put in registry
                    //$instance->_initLogging(self::LOG_TYPE_HTTPD);

                    break;
            }

            // setup database connection and put it in registry
            if ($config->database instanceof Zend_Config) {
                $instance->_initDefaultDb($config);
            }

            return true;

        } catch (Exception $e) {

            var_dump($e);

            die("".__METHOD__." FAILED.");
        }


	}



    /**
     * @throws Lib_Application_Exception
     * @param Zend_Config $config
     * @return void
     */
    private function _initDefaultDb(Zend_Config $config)
    {
        // setup database connection and put it in registry


        if (($config->database instanceof Zend_Config)!==true) {
            $e = new Lib_Application_Exception(
                "Invalid config.database at ".__METHOD__
            );
            $e->setMethod(__METHOD__);
            throw $e;
        }

        $db = Zend_Db::factory($config->database->adapter,
                               $config->database->params->toArray());

        Zend_Registry::set('DB',$db);

        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }

    /**
     *
     * @param int $errorNumber
     * @param string $errorString
     * @param string $errorFile
     * @param int $errorLine
     * @param array|null $errorContext
     */
    public static function handleError(
        $errorNumber,
        $errorString,
        $errorFile,
        $errorLine,
        $errorContext = null)
    {

        if (!class_exists('Zend_Registry')) {
            throw new Exception(
                "FATAL ERROR. Class Zend_Registry does not exist at "
                        . __METHOD__
            );
        }



        switch ($errorNumber) {

            case E_USER_ERROR:
                $errorType = Zend_Log::ERR;
                break;

            case E_USER_WARNING:
                $errorType = Zend_Log::WARN;
                break;

            case E_USER_NOTICE:
                $errorType = Zend_Log::NOTICE;
                break;

            case E_NOTICE:
                $errorType = Zend_Log::NOTICE;
                break;

            default:
                $errorType = Zend_Log::EMERG;
                break;
        }

        // output error:

        try {
             /** @var $log Zend_Log */
            $log = Zend_Registry::get('FIREBUG');
            /*
            $log->log(
                "$errorString on line $errorLine in file $errorFile",
                $errorType
            );
            */
        } catch (Exception $e) {

        }


        // output backtrace:
/*
        ob_start();
        debug_print_backtrace();
        $msg = explode("\n", ob_get_clean());
        array_shift($msg);
        array_pop($msg);
        foreach ($msg as $k => $m) {

            $msg[$k] = str_replace(ROOT_PATH,'', $m);
        }
        $log->log($msg, $errorType);
*/



        // dirty hack for white pages issues

        if (
                ( ((int)$errorNumber) ===8)
                && (fnmatch("Undefined variable*",$errorString, false))
        ) {

            // the bug appears if you d sth like...
            /*

             $x=$bla->blug(); // where bla is undefined
             */
            try {
                $e = new Lib_Application_Exception($errorString);
                $e->setFault(array(
                          $errorNumber,
                $errorString,
                $errorFile,
                $errorLine
                             ));
                throw $e;

            }catch(Exception $e) {
                //var_dump($e->getMessage());
                self::handleUncaughtException($e);
                die("DIED at ".__METHOD__.__LINE__); // just in case
            }
        }






        //echo "----------------- ob -----------------";
        //echo ob_get_contents();
        //ob_flush();


        //ob_end_clean();

/*
        $e=new Exception("foo");
        self::handleUncaughtException($e);
        throw new Exception("foo");
*/
        // Don't execute PHP internal error handler

        // convert to exception and throw that shit
        // that is required for rpc global exception handlers
        // we need to pass a json-rpc-style exception IN ANY CASE !
        throw new ErrorException(
            $errorString,
            0,
            $errorNumber,
            $errorFile,
            $errorLine
        );

        //return true; // Don't execute PHP internal error handler
    }

    /**
     * Hardcoded shutdown handler to detect critical PHP errors.
     * @static
     * @return void
     */
    public static function handleShutdown()
    {

        $error = error_get_last();

        if ($error === null) {
             // no error, we have a "normal" shut down (script is finished).
            return;
        }
//var_dump(__METHOD__);
//var_dump($error);
        // an error occurred and forced the shut down
        $e = new Lib_Application_Exception(self::ERROR_SHUTDOWN);
        $e->setMethod(__METHOD__);
        $e->setFault(array(
                        "lastError" => $error,
                     ));


        self::handleUncaughtException($e);
    }

    /**
     * Quicker autoload implementation
     * @static
     * @param string $className
     * @return void
     */
    public static function autoLoad($className)
    {
        $pathParts = explode('_', $className);
        if (in_array($pathParts[0], array('App', 'Lib'))) {
            array_unshift($pathParts, 'application');
        } else {
            array_unshift($pathParts, 'contrib');
        }
        $classFile =
            SRC_PATH . DIRECTORY_SEPARATOR
            . implode(DIRECTORY_SEPARATOR, $pathParts)
            . '.php';


        if (!file_exists($classFile)) {
            return;
        }

        require_once $classFile;
    }


    /**
     * @static
     * @param  Exception|null $exception
     * @return void
     */
    public static function handleUncaughtException($exception)
    {

        try {

            $outputBuffer = ob_get_contents();
            ob_end_clean();

            try {
                //if (headers_sent()!==true) {
                    //header("HTTP/1.0 500"); // 500 makes trouble in vz env!
                //}
            } catch (Exception $e) {
            }



            if (($exception instanceof Exception) !== true) {
                die(__METHOD__ . " FAILED! Invalid Parameter 'exception'.");
            }

            $isDebugMode = false;
            $isDeveloper = false;
            try {
                $isDeveloper = self::getRegistry()->getDebug()->isDeveloper();
            } catch (Exception $e) {
                die(__METHOD__ . " FAILED! Invalid 'isDebugMode'.");
            }

            try {
                $isDebugMode = self::getRegistry()->getDebug()->isDebugMode();
            } catch (Exception $e) {
                die(__METHOD__ . " FAILED! Invalid 'isDebugMode'.");
            }

            $showDebugInfo = (
            ($isDebugMode === true)
                //    || ($isDeveloper === true)
            );

            if ($showDebugInfo !== true) {
                die("An Error occured. Please retry lateron! (E0021)");
            }


            $errorInfo = array(
                "class" => get_class($exception),
                "message" => $exception->getMessage(),
                "method" => null,
                "methodLine" => null,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "stackTrace" => $exception->getTrace(),
                "stackTraceAsString" => $exception->getTraceAsString(),
                "fault" => null,
                "lastError" => array(
                    "type" => null,
                    "file" => null,
                    "line" => null,
                    "message" => null,
                ),
            );

            if (defined("ROOT_PATH")) {
                $errorInfo["file"] = str_replace(ROOT_PATH, '', $errorInfo['file']);
                $errorInfo["stackTraceAsString"] = str_replace(
                    ROOT_PATH, '', $errorInfo['stackTraceAsString']
                );
                $outputBuffer = str_replace(ROOT_PATH, '', $outputBuffer);
            }


            if ($exception instanceof Lib_Application_Exception) {
                /**
                 * @var Lib_Application_Exception $exception
                 */
                $errorInfo["method"] = $exception->getMethod();
                $errorInfo["fault"] = $exception->getFault();
                $errorInfo["methodLine"] = $exception->getMethodLine();
                $errorInfo["fault"] = $exception->getFault();
            }

            $isShutdownError = false;
            if (
                ($exception instanceof Lib_Application_Exception)
                && ($exception->getMessage() === self::ERROR_SHUTDOWN)
            ) {
                $isShutdownError = true;
            }


            // parse last error
            $lastError = error_get_last();
            if (is_array($lastError)) {

                foreach ($lastError as $key => $value) {
                    $errorInfo["lastError"][$key] = $value;
                }

            }

            if (defined("ROOT_PATH")) {
                $errorInfo["lastError"]["file"] = str_replace(
                    ROOT_PATH, '', $errorInfo["lastError"]['file']
                );
            }





            // +++++++++++++++++ simple wildfire output ++++++++++++++++++
            try {

                //if (headers_sent()!==true) {
                    header('X-Wf-Protocol-1:     http://meta.wildfirehq.org/Protocol/JsonStream/0.2');
                    header('X-Wf-1-Plugin-1:     http://meta.firephp.org/Wildfire/Plugin/FirePHP/Library-FirePHPCore/0.3');
                    header('X-Wf-1-Structure-1:  http://meta.firephp.org/Wildfire/Structure/FirePHP/FirebugConsole/0.1');

                    $description = $errorInfo['message'] . ' in ' . $errorInfo['file'] . ' line ' . $errorInfo['line'];

                    //$msg = '[{"Type":"LOG","File":"'.$error['file'].'","Line":'.$error['line'].'},"'.$description.'"]';

                    //NOTICE msg must not have newlines
                    $msg = array(
                        "Description" => $description,
                        "Type" => "LOG",
                        "File" => $errorInfo['file'],
                        "Line" => $errorInfo['line'],
                    );
                    $msg = json_encode($msg);

                    header('X-Wf-1-1-1-1: ' . strlen($msg) . '|' . $msg . '|');
                //}
            } catch (Exception $e) {

                // e.g.: "HEADERS ALREADY SENT"
            }

            // ++++++++++++++++++ html nice output ++++++++++++++++++++

            $outHtmlText = '';

            if ($isShutdownError === true) {
                $outHtmlText .= '<b>----------- FATAL ERROR (SHUTDOWN)!  ------------ </b>';
            } else {
                $outHtmlText .= '<b>----------- FATAL ERROR (UNCAUGHT EXCEPTION CATCHED)! ------------ </b>';
            }

            $outHtmlText .= ""
                            . '<br /><br />'
                            . '[' . __METHOD__ . ' '
                            . ' isDebugMode=' . json_encode($isDebugMode)
                            . ' isDeveloper=' . json_encode($isDeveloper)
                            . " ]"
                            . '<br /><br />'

                            . '<b>' . $errorInfo["class"] . "</b>"
                            . ' ' . htmlentities($errorInfo["message"])
                            . '<br /><br />'
                            . ' ' . $errorInfo["method"] . " " . $errorInfo["methodLine"]
                            . ' ' . $errorInfo["file"] . " " . $errorInfo["line"]

                            . '<br /><br />'
                            . htmlentities($errorInfo["stackTraceAsString"])
                            . '<br /><br />';





            ob_start();
            var_dump($errorInfo["lastError"]);
            $lastErrorDump = ob_get_contents();
            ob_clean();
            if (defined("ROOT_PATH")) {
                $lastErrorDump = str_replace(ROOT_PATH, '', $lastErrorDump);
            }
            $outHtmlText .= ''
                            . '<b>------------- LAST ERROR DUMP --------------------</b>'
                            . '<br /><br />'
                            . htmlentities($lastErrorDump)
                            . '<br /><br />';

            ob_start();
            var_dump($errorInfo["fault"]);
            $faultDump = ob_get_contents();
            ob_clean();
            if (defined("ROOT_PATH")) {
                $faultDump = str_replace(ROOT_PATH, '', $faultDump);
            }

            $outHtmlText .= ''
                            . "<b>----------- FAULT DUMP ------------------ </b>"
                            . '<br /><br />'
                            . htmlentities($faultDump)
                            . '<br /><br />';


            if (!$errorInfo["stackTrace"]) {
                $errorInfo["stackTrace"] = debug_backtrace();
            }

            $trace = $errorInfo["stackTrace"];
            foreach ($trace as $key => $stackPoint) {
                // I'm converting arguments to their type
                // (prevents passwords from ever getting logged as anything other than 'string')

                $args = array();
                if (isset($trace[$key]['args'])) {
                    $args = $trace[$key]['args'];
                }



                $_args = array();
                foreach ($args as $arg) {
                    $argValue = $arg;
                    $argType = gettype($arg);
                    $argText = "" . $argType;
                    if (is_object($arg)) {
                        $argClass = get_class($arg);
                        if ($argClass) {
                            $argText .= " " . $argClass;
                        }
                    }

                    $_args[] = $argText;
                }
                //$trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
                $trace[$key]['args'] = $_args;
            }


            ob_start();
            var_dump($trace);
            $stackTraceDump = ob_get_contents();
            ob_clean();
            if (defined("ROOT_PATH")) {
                $stackTraceDump = str_replace(ROOT_PATH, '', $stackTraceDump);
            }

            $outHtmlText .= ''
                            . '<br /><br />'
                            . "<b>--------------- STACKTRACE DUMP--------------</b>"
                            . '<br /><br />'
                            . htmlentities($stackTraceDump)
                            . '<br /><br />';



            if (defined("ROOT_PATH")) {
                $outputBuffer = str_replace(ROOT_PATH, '', $outputBuffer);
            }
            $outHtmlText .= ''
                            . "<b>------------- OUTPUTBUFFER DUMP ------------------</b>"
                            . '<br /><br />'
                            . htmlentities($outputBuffer)
                            . '<br /><br />';



            echo
                    '<div style="font-size: 16px; padding: 16px 32px 16px 32px; margin: 16px; border: solid red 3px; background: #000000; color: red;"><pre>'
                    . $outHtmlText
                    . '</pre></div>';



        } catch (Exception $e) {
            //var_dump($e);

            $errorInfo = array(
                "class" => get_class($exception),
                "message" => $exception->getMessage(),
                "method" => null,
                "methodLine" => null,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "stackTrace" => $exception->getTrace(),
                "stackTraceAsString" => $exception->getTraceAsString(),
                "fault" => null,
                "lastError" => array(
                    "type" => null,
                    "file" => null,
                    "line" => null,
                    "message" => null,
                ),
            );

            $couchDoc = (object)$errorInfo;
            echo json_encode($errorInfo);

            $couchLogger = new App_GaintS_Lib_Logger_CouchDBLogger();
            $couchLogger->setData($couchDoc);
            $couchLogger->setLogType("handleUncaughtException");
            $couchLogger->writeLog();

            return;
        }

         die("DIED AT ".__METHOD__);

    }
}
