<?php

namespace Processus
{

    require_once 'helpers.php';

    use Processus\Interfaces\InterfaceBootstrap;

    /**
     *
     */
    class ProcessusBootstrap implements InterfaceBootstrap
    {

        /**
         * @var \Processus\ProcessusContext
         */
        private $_applicationContext;

        /**
         * @return ProcessusContext
         */
        public function getApplication()
        {
            if (!$this->_applicationContext) {
                $this->_applicationContext = new ProcessusContext();
                \Processus\Lib\Profiler\ProcessusProfiler::getInstance()->applicationProfilerStart();
            }
            return $this->_applicationContext;
        }

        /**
         * Initializes the system
         *
         * @param string $mode the mode for which to initialize
         *
         * @return boolean true on success
         *
         */
        public function init($mode = 'DEFAULT')
        {
            try {

                define('PATH_ROOT', realpath(dirname(__FILE__) . '/../../../'));
                define('PATH_CORE', PATH_ROOT . '/library/Processus/core');
                define('PATH_APP', PATH_ROOT . '/application/php');
                define('PATH_PUBLIC', PATH_ROOT . '/htdocs');

                // display erros for the following part
                ini_set('display_errors', 'On');

                error_reporting(E_ALL | E_STRICT);

                set_error_handler(array(
                                       'Processus\ProcessusBootstrap',
                                       'handleError'
                                  ));

                register_shutdown_function(array(
                                                'Processus\ProcessusBootstrap',
                                                'handleError'
                                           ));

                set_exception_handler(array(
                                           'Processus\ProcessusBootstrap',
                                           'handleError'
                                      ));

                ini_set('display_errors', 'Off');

                // cache current include path
                $cachedIncludePath = get_include_path();

                // set new include path
                //                 set_include_path(PATH_CORE . '/Contrib' . PATH_SEPARATOR . PATH_CORE . PATH_APP);

                // setup autoloader
                spl_autoload_register(array(
                                           'Processus\ProcessusBootstrap',
                                           '_autoLoad'
                                      ));

                $registry = $this->getApplication()->getRegistry();

                // setup locale
                setlocale(LC_ALL, $registry->getConfig('locale')->default->lc_all);
                date_default_timezone_set($registry->getConfig('locale')->default->timezone);

                // adjustments for different envs (etc. phpunit)
                switch ($mode) {
                    case 'INSTALL':
                        break;

                    case 'TEST':
                        set_include_path(get_include_path() . PATH_SEPARATOR . $cachedIncludePath);

                        break;

                    case 'TASK':
                        break;

                    case 'DEFAULT':
                        break;

                    default:
                        break;
                }

                return true;

            }
            catch (\Exception $e) {

                echo json_encode($e);
                //die("" . __METHOD__ . " FAILED.");

            }
        }

        /**
         * Custom class auto loader
         * @static
         *
         * @param string $className
         *
         * @return void
         */
        public static function _autoLoad($className)
        {
            $rootPath = NULL;

            $pathParts = explode('\\', $className);

            switch ($pathParts[0]) {

                case 'Application':
                    $rootPath = PATH_APP;
                    break;

                case 'Processus':
                    $rootPath = PATH_CORE;
                    array_shift($pathParts);
                    break;

                default:
                    $rootPath = PATH_CORE;
                    array_unshift($pathParts, 'Contrib');
                    break;
            }

            $classFile = $rootPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';

            if (!file_exists($classFile)) {
                return;
            }

            require_once $classFile;
        }

        /**
         * @static
         *
         * @param $errno
         * @param $errstr
         * @param $errfile
         * @param $errline
         *
         * @return mixed
         */
        public static function handleError($errno, $errstr, $errfile, $errline)
        {
            $lastError = error_get_last();

            $returnValue           = array();
            $returnValue['result'] = array();
            $returnValue['error']  = array();

            if ($errno instanceof \Processus\Abstracts\AbstractException) {

                header('HTTP/1.1 500 Internal Server Error');

                $debug = array();
                $user  = array();

                $debug['trigger']      = "Manual Exception";
                $debug['file']         = $errno->getFile();
                $debug['line']         = $errno->getLine();
                $debug['message']      = $errno->getMessage();
                $debug['trace']        = $errno->getTraceAsString();
                $debug['method']       = $errno->getMethod();
                $debug['extendedData'] = $errno->getExtendData();

                $user['message'] = $errno->getUserMessage();
                $user['title']   = $errno->getUserMessageTitle();
                $user['code']    = $errno->getUserErrorCode();
                $user['details'] = $errno->getUserDetailError();

                $lastError['data'] = $lastError;

                $error['debug'] = $debug;

                $error['user']      = $user;
                $error['lasterror'] = $lastError;

                $returnValue['error'] = $error;

                echo json_encode($returnValue);
                return;
            }

            if ($lastError) {

                header('HTTP/1.1 500 Internal Server Error');

                $returnValue        = array();
                $error['trigger']   = "Auto Exception";
                $error['backtrace'] = debug_backtrace();
                $error['errorData'] = $lastError;
                $error['params']    = array("number"  => $errno,
                                            "message" => $errstr,
                                            "file"    => $errfile,
                                            "line"    => $errline);

                $returnValue['error'] = $error;

                echo json_encode($returnValue);
                return;
            }
        }
    }
}

?>