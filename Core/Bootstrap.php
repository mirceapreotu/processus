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
class Bootstrap
{
    const ERROR_SHUTDOWN  = "ERROR_BOOTSTRAP_SHUTDOWN";
	const LOG_TYPE_HTTPD  = 'httpd';
	const LOG_TYPE_CLI    = 'cli';

    // server stages
    const SERVER_STAGE_PRODUCTION   = "production"; // live
    const SERVER_STAGE_TESTING  = "testing"; // payground
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


    // #########################################################


    /**
     * @static
     * @return Bootstrap
     */
    public static function getInstance()
    {
        if (self::$_instance instanceof self !== TRUE)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    // #########################################################


    /**
     * @static
     * @return App_Registry
     */
    public static function getRegistry()
    {
        $instance = self::getInstance();

        if ($instance->_registry instanceof Core_Registry !== TRUE)
        {
            $instance->_registry = new Core_Registry();
        }

        return $instance->_registry;
    }


    // #########################################################


	/**
	 * Initializes the system
	 *
	 * @param string $mode the mode for which to initialize
	 *
	 * @return boolean true on success
	 *
	 */
	public static function init($mode = 'DEFAULT')
	{
        try
        {
            if (self::getInstance()->_isInitialized)
            {
                return;
            }

            self::getInstance()->_isInitialized = TRUE;
            $instance = self::getInstance();

            // display erros for the following part
            ini_set('display_errors', TRUE);

            error_reporting(E_ALL | E_STRICT);
            set_error_handler(array('Bootstrap', 'handleError'));
            register_shutdown_function(array('Bootstrap', 'handleShutdown'));
            set_exception_handler(array('Bootstrap','handleUncaughtException'));

            // all error handlers are initialized
            ini_set('display_errors', FALSE);

            // cache current include path
            $origIncludePath = get_include_path();

            // set new include path
            set_include_path(
                PATH_CORE . '/Contrib' . PATH_SEPARATOR .
                PATH_CORE . '/GaintS' . PATH_SEPARATOR .
                PATH_APP
            );

            // setup autoloader
            spl_autoload_register(array('Bootstrap', '_autoLoad'));

            // load basic config and put it in registry
            $config = new Zend_Config(require PATH_APP.'/Config/config.php');
            self::getRegistry()->setProperty('CONFIG', $config);

            // setup locale
            setlocale(LC_ALL, $config->locale->default->lc_all);
            date_default_timezone_set($config->locale->default->timezone);

            // adjustments for different envs (etc. phpunit)
            switch ($mode)
            {
                case 'INSTALL':
                    break;

                case 'TEST':
                    set_include_path(
                        get_include_path() . PATH_SEPARATOR .
                        $origIncludePath
                    );

                    break;

                case 'TASK':
                    break;

                case 'DEFAULT':
                    break;

                default:
                    break;
            }

            // init DB and add it to registry
            if ($config->database instanceof Zend_Config)
            {
                $instance->_initDefaultDb($config);
            }

            return true;

        }
        catch (Exception $e)
        {
            var_dump($e);
            die("" . __METHOD__ . " FAILED.");
        }
	}


    // #########################################################


    /**
     * Custom class auto loader
     * @static
     * @param string $className
     * @return void
     */
    public static function _autoLoad($className)
    {
        $rootPath = NULL;
        $pathParts = explode('_', $className);

        switch ($pathParts[0])
        {
            case 'App':
                $rootPath = PATH_APP;
                array_shift($pathParts);
                break;

            case 'Lib':
                $rootPath = PATH_CORE;
                break;

            case 'Core':
                $rootPath = PATH_CORE;
                array_shift($pathParts);
                break;

            default:
                $rootPath = PATH_CORE;
                array_unshift($pathParts, 'Contrib');
                break;
        }

        $classFile =
            $rootPath . DIRECTORY_SEPARATOR
            . implode(DIRECTORY_SEPARATOR, $pathParts)
            . '.php';

        // echo $classFile.'<hr>';

        if ( ! file_exists($classFile))
        {
            return;
        }

        require_once $classFile;
    }

    // #########################################################


    /**
     * @throws Lib_Application_Exception
     * @param Zend_Config $config
     * @return void
     */
    private function _initDefaultDb(Zend_Config $config)
    {
        if ($config->database instanceof Zend_Config !== TRUE)
        {
            $e = new Lib_Application_Exception("Invalid config.database at " . __METHOD__);
            $e->setMethod(__METHOD__);
            throw $e;
        }

        $db = Zend_Db::factory($config->database->adapter, $config->database->params->toArray());
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        self::getRegistry()->setProperty('DB', $db);
    }


    // #########################################################


    /**
     * Initializes the logging subsystem
     *
     * @param string $type LOG_TYPE_HTTPD|LOG_TYPE_CLI
     * @return boolean true on success
     *
     */
    private function _initLogging($type)
    {
        $writer_default = new Lib_Log_Writer_DailyStream(PATH_CORE . '/../var/log/app/' . $type . '_default.log');
        $writer_critical = new Lib_Log_Writer_DailyStream(PATH_CORE . '/../var/log/app/' . $type . '_critical.log');

        $format = '%timestamp%;%pid%;%priorityName%;%priority%;%message%' . PHP_EOL;
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

        Zend_Registry::set('LOG', $logger);

        if ($type === self::LOG_TYPE_HTTPD)
        {
            $writer = new Zend_Log_Writer_Firebug();
            $logger_firebug = new Zend_Log($writer);
            Zend_Registry::set('FIREBUG', $logger_firebug);
        }

        return true;
    }


    // #########################################################


    /**
     *
     * @param int $errorNumber
     * @param string $errorString
     * @param string $errorFile
     * @param int $errorLine
     * @param array|null $errorContext
     */
    public static function handleError($errorNumber, $errorString, $errorFile, $errorLine, $errorContext = null)
    {
        if ( ! class_exists('Zend_Registry'))
        {
            throw new Exception("FATAL ERROR. Class Zend_Registry does not exist at " . __METHOD__);
        }

        switch ($errorNumber)
        {
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

        // output error

        try
        {
            $log = Zend_Registry::get('FIREBUG');
        }
        catch (Exception $e)
        {
        }

        // dirty hack for white pages issues

        if ((int) $errorNumber === 8 && fnmatch("Undefined variable*", $errorString, FALSE))
        {
            try
            {
                $e = new Lib_Application_Exception($errorString);

                $e->setFault(array(
                    $errorNumber,
                    $errorString,
                    $errorFile,
                    $errorLine
                ));

                throw $e;

            }
            catch(Exception $e)
            {
                self::handleUncaughtException($e);
                die("DIED at " . __METHOD__ . __LINE__);
            }
        }

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
    }


    // #########################################################


    /**
     * Hardcoded shutdown handler to detect critical PHP errors.
     * @static
     * @return void
     */
    public static function handleShutdown()
    {
        $error = error_get_last();

        if ($error === NULL)
        {
             // no error, we have a "normal" shut down (script is finished).
            return;
        }

        // an error occurred and forced the shut down
        $e = new Lib_Application_Exception(self::ERROR_SHUTDOWN);
        $e->setMethod(__METHOD__);
        $e->setFault(array("lastError" => $error));
        self::handleUncaughtException($e);
    }


    // #########################################################


    /**
     * @static
     * @param  Exception|null $exception
     * @return void
     */
    public static function handleUncaughtException($exception)
    {
        try
        {
            $outputBuffer = ob_get_contents();
            ob_end_clean();

            try
            {
                //if (headers_sent()!==true) {
                //header("HTTP/1.0 500"); // 500 makes trouble in vz env!
                //}
            }
            catch (Exception $e)
            {
            }

            if (($exception instanceof Exception) !== TRUE)
            {
                die(__METHOD__ . " FAILED! Invalid Parameter 'exception'.");
            }

            $isDebugMode = FALSE;
            $isDeveloper = FALSE;

            try
            {
                $isDeveloper = self::getRegistry()->getDebug()->isDeveloper();
            }
            catch (Exception $e)
            {
                die(__METHOD__ . " FAILED! Invalid 'isDebugMode'.");
            }

            try
            {
                $isDebugMode = self::getRegistry()->getDebug()->isDebugMode();
            }
            catch (Exception $e)
            {
                die(__METHOD__ . " FAILED! Invalid 'isDebugMode'.");
            }

            // check this!
            $showDebugInfo = $isDebugMode;

            // if ($showDebugInfo !== TRUE)
            // {
            //     die("An Error occured. Please retry lateron! (E0021)");
            // }

            $errorInfo = array(
                "class" => get_class($exception),
                "message" => $exception->getMessage(),
                "method" => NULL,
                "methodLine" => NULL,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "stackTrace" => $exception->getTrace(),
                "stackTraceAsString" => $exception->getTraceAsString(),
                "fault" => NULL,
                "lastError" => array(
                    "type" => NULL,
                    "file" => NULL,
                    "line" => NULL,
                    "message" => NULL,
                ),
            );

            if (defined("PATH_ROOT"))
            {
                $errorInfo["file"] = str_replace(PATH_ROOT, '', $errorInfo['file']);
                $errorInfo["stackTraceAsString"] = str_replace(PATH_ROOT, '', $errorInfo['stackTraceAsString']);
                $outputBuffer = str_replace(PATH_ROOT, '', $outputBuffer);
            }


            if ($exception instanceof Lib_Application_Exception)
            {
                /**
                 * @var Lib_Application_Exception $exception
                 */
                $errorInfo["method"] = $exception->getMethod();
                $errorInfo["fault"] = $exception->getFault();
                $errorInfo["methodLine"] = $exception->getMethodLine();
                $errorInfo["fault"] = $exception->getFault();
            }

            $isShutdownError = FALSE;

            if ($exception instanceof Lib_Application_Exception && $exception->getMessage() === self::ERROR_SHUTDOWN)
            {
                $isShutdownError = TRUE;
            }

            // parse last error
            $lastError = error_get_last();

            if (is_array($lastError))
            {
                foreach ($lastError as $key => $value)
                {
                    $errorInfo["lastError"][$key] = $value;
                }
            }

            if (defined("PATH_ROOT"))
            {
                $errorInfo["lastError"]["file"] = str_replace(PATH_ROOT, '', $errorInfo["lastError"]['file']);
            }

            // +++++++++++++++++ simple wildfire output ++++++++++++++++++

            try
            {
                header('X-Wf-Protocol-1:     http://meta.wildfirehq.org/Protocol/JsonStream/0.2');
                header('X-Wf-1-Plugin-1:     http://meta.firephp.org/Wildfire/Plugin/FirePHP/Library-FirePHPCore/0.3');
                header('X-Wf-1-Structure-1:  http://meta.firephp.org/Wildfire/Structure/FirePHP/FirebugConsole/0.1');

                $description = $errorInfo['message'] . ' in ' . $errorInfo['file'] . ' line ' . $errorInfo['line'];

                //NOTICE msg must not have newlines
                $msg = array(
                    "Description" => $description,
                    "Type" => "LOG",
                    "File" => $errorInfo['file'],
                    "Line" => $errorInfo['line'],
                );

                $msg = json_encode($msg);

                header('X-Wf-1-1-1-1: ' . strlen($msg) . '|' . $msg . '|');

            }
            catch (Exception $e)
            {
                // e.g.: "HEADERS ALREADY SENT"
            }

            // ++++++++++++++++++ html nice output ++++++++++++++++++++

            $outHtmlText = '';

            if ($isShutdownError === TRUE)
            {
                $outHtmlText .= '<b>----------- FATAL ERROR (SHUTDOWN)!  ------------ </b>';
            }
            else
            {
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

            if (defined("PATH_ROOT"))
            {
                $lastErrorDump = str_replace(PATH_ROOT, '', $lastErrorDump);
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

            if (defined("PATH_ROOT"))
            {
                $faultDump = str_replace(PATH_ROOT, '', $faultDump);
            }

            $outHtmlText .= ''
                            . "<b>----------- FAULT DUMP ------------------ </b>"
                            . '<br /><br />'
                            . htmlentities($faultDump)
                            . '<br /><br />';


            if ( ! $errorInfo["stackTrace"])
            {
                $errorInfo["stackTrace"] = debug_backtrace();
            }

            $trace = $errorInfo["stackTrace"];

            foreach ($trace as $key => $stackPoint)
            {
                // I'm converting arguments to their type
                // (prevents passwords from ever getting logged as anything other than 'string')

                $args = array();

                if (isset($trace[$key]['args']))
                {
                    $args = $trace[$key]['args'];
                }

                $_args = array();

                foreach ($args as $arg)
                {
                    $argValue = $arg;
                    $argType = gettype($arg);
                    $argText = "" . $argType;

                    if (is_object($arg))
                    {
                        $argClass = get_class($arg);
                        if ($argClass)
                        {
                            $argText .= " " . $argClass;
                        }
                    }

                    $_args[] = $argText;
                }

                $trace[$key]['args'] = $_args;
            }

            ob_start();
            var_dump($trace);
            $stackTraceDump = ob_get_contents();
            ob_clean();

            if (defined("PATH_ROOT"))
            {
                $stackTraceDump = str_replace(PATH_ROOT, '', $stackTraceDump);
            }

            $outHtmlText .= ''
                            . '<br /><br />'
                            . "<b>--------------- STACKTRACE DUMP--------------</b>"
                            . '<br /><br />'
                            . htmlentities($stackTraceDump)
                            . '<br /><br />';

            if (defined("PATH_ROOT"))
            {
                $outputBuffer = str_replace(PATH_ROOT, '', $outputBuffer);
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

        }
        catch (Exception $e)
        {
            $errorInfo = array(
                "class" => get_class($exception),
                "message" => $exception->getMessage(),
                "method" => NULL,
                "methodLine" => NULL,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "stackTrace" => $exception->getTrace(),
                "stackTraceAsString" => $exception->getTraceAsString(),
                "fault" => NULL,
                "lastError" => array(
                    "type" => NULL,
                    "file" => NULL,
                    "line" => NULL,
                    "message" => NULL,
                ),
            );

            $couchDoc = (object) $errorInfo;
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
?>
