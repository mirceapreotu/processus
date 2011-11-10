<?php

namespace Processus
{
    
    require_once 'helpers.php';
    
    /**
     *
     */
    class Bootstrap
    {        

        /**
         * Initializes the system
         *
         * @param string $mode the mode for which to initialize
         * @return boolean true on success
         *
         */
        public static function init($mode = 'DEFAULT')
        {
            try {
                
                define('PATH_ROOT', realpath(dirname(__FILE__) . '/../../../'));
                define('PATH_CORE', PATH_ROOT . '/library/Processus/core');
                define('PATH_APP', PATH_ROOT . '/application/php');
                define('PATH_PUBLIC', PATH_ROOT . '/htdocs');
                
                // display erros for the following part
                ini_set('display_errors', TRUE);
                
                error_reporting(E_STRICT | E_ALL);
                set_error_handler(array(
                    'Processus\Bootstrap', 
                    'handleError'
                ));
                register_shutdown_function(array(
                    'Processus\Bootstrap', 
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
                    'Processus\Bootstrap', 
                    '_autoLoad'
                ));

                $registry = Application::getInstance()->getRegistry();
                
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
            
            if (! file_exists($classFile)) {
                return;
            }
            
            require_once $classFile;
        }

        // #########################################################
        

        public static function handleError()
        {
            $lastError = error_get_last();
            
            if (is_array($lastError) && array_key_exists('message', $lastError)) {
                echo '<div style="background:#c00;color:#fff;font-size:22px;padding:10px">';
                echo $lastError['message'] . '<hr>';
                echo 'File: ' . $lastError['file'] . '<br>';
                echo 'Line: ' . $lastError['line'] . '<br>';
                echo '</div>';
                
                echo '<h3>Stack</h3>';
                echo '<pre style="background:#ffc;padding:10px">';
                print_r(debug_backtrace());
                echo '</pre>';
                
                return TRUE;
            }
        }
    }
}

?>