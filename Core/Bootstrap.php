<?php
/**
 * 
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

	protected static $_instance;
	protected static $_registry;


    // #########################################################


	/**
	 * Initializes the system
	 *
	 * @param string $mode the mode for which to initialize
	 * @return boolean true on success
	 *
	 */
	public static function init($mode = 'DEFAULT')
	{
        try
        {
            // display erros for the following part
            ini_set('display_errors', TRUE);

            error_reporting(E_ALL | E_STRICT);
            set_error_handler(array('Bootstrap', 'handleError'));
			register_shutdown_function(array('Bootstrap', 'handleError'));

            // all error handlers are initialized
            ini_set('display_errors', FALSE);

            // cache current include path
            $cachedIncludePath = get_include_path();

            // set new include path
            set_include_path(
                PATH_CORE . '/Contrib' . PATH_SEPARATOR .
                PATH_CORE . '/GaintS' . PATH_SEPARATOR .
                PATH_APP
            );

            // setup autoloader
            spl_autoload_register(array('Bootstrap', '_autoLoad'));

            // setup locale
            setlocale(LC_ALL, self::getRegistry()->getConfig('locale')->default->lc_all);
            date_default_timezone_set(self::getRegistry()->getConfig('locale')->default->timezone);

            // adjustments for different envs (etc. phpunit)
            switch ($mode)
            {
                case 'INSTALL':
                    break;

                case 'TEST':
                    set_include_path(
                        get_include_path() . PATH_SEPARATOR .
                        $cachedIncludePath
                    );

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
                array_shift($pathParts);
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

//		echo $classFile.'<hr>';

        if ( ! file_exists($classFile))
        {
            return;
        }

        require_once $classFile;
    }


    // #########################################################


	/**
	 * @static
	 * @return get registry instance
	 */
	public static function getRegistry()
	{
		if (self::$_registry instanceof Core_Registry !== TRUE)
		{
            self::$_registry = Core_Registry::getInstance();
		}

		return self::$_registry;
	}


    // #########################################################


	public static function handleError()
	{
		$lastError = error_get_last();

		echo '<div style="background:#c00;color:#fff;font-size:22px;padding:10px">';
		echo $lastError['message'] . '<hr>';
		echo 'File: ' . $lastError['file'] . '<br>';
		echo 'Line: ' . $lastError['line'] . '<br>';
		echo '</div>';

		echo '<h3>Stack</h3>';
		echo '<pre style="background:#ffc;padding:10px">';
		debug_print_backtrace();
		echo '</pre>';

		return TRUE;
    }
}

?>
