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
                ini_set('display_errors', TRUE);

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

                // all error handlers are initialized
                ini_set('display_errors', FALSE);

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
         * @param $errorObj
         *
         * @return bool
         */
        public static function handleError($errorObj)
        {
            $lastError = error_get_last();

            $returnValue           = array();
            $returnValue['result'] = array();
            $returnValue['error']  = array();

            if ($errorObj instanceof \Processus\Abstracts\AbstractException) {

                $debug = array();
                $user  = array();

                $debug['file']    = $errorObj->getFile();
                $debug['line']    = $errorObj->getLine();
                $debug['message'] = $errorObj->getMessage();
                $debug['trace']   = $errorObj->getTraceAsString();
                $debug['method']  = $errorObj->getMethod();

                $user['message'] = $errorObj->getUserMessage();
                $user['title']   = $errorObj->getUserMessageTitle();
                $user['code']    = $errorObj->getUserErrorCode();
                $user['details'] = $errorObj->getUserDetailError();

                $lastError['data'] = $lastError;

                $error['debug'] = $debug;

                $error['user']      = $user;
                $error['lasterror'] = $lastError;

                $returnValue['error'] = $error;

                echo json_encode($returnValue);

            }

            if ($lastError) {

                $returnValue        = array();
                $error['backtrace'] = debug_backtrace();
                $error['errorData'] = $lastError;

                $returnValue['error'] = $error;

                echo json_encode($returnValue);
            }
        }
    }
}

?>