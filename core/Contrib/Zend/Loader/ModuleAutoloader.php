<?php

namespace Zend\Loader;

use SplFileInfo, Traversable;

class ModuleAutoloader implements SplAutoloader
{

    /**
     * @var array An array of module paths to scan
     */
    protected $paths = array();

    /**
     * @var array An array of modulename => path 
     */
    protected $explicitPaths = array();

    /**
     * @var array An array of supported phar formats
     */
    protected $pharExtensions = array('phar', 'phar.gz', 'phar.bz2', 'phar.tar', 
    'phar.tar.gz', 'phar.tar.bz2', 'phar.zip', 'tar', 'tar.gz', 'tar.bz2', 'zip');

    /**
     * Constructor
     *
     * Allow configuration of the autoloader via the constructor.
     * 
     * @param  null|array|Traversable $options 
     * @return void
     */
    public function __construct ($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * Configure the autoloader
     *
     * In most cases, $options should be either an associative array or 
     * Traversable object.
     * 
     * @param  array|Traversable $options 
     * @return SplAutoloader
     */
    public function setOptions ($options)
    {
        $this->registerPaths($options);
        return $this;
    }

    /**
     * Autoload a class
     *
     * @param   $class
     * @return  mixed
     * False [if unable to load $class]
     * get_class($class) [if $class is successfully loaded]
     */
    public function autoload ($class)
    {
        // Limit scope of this autoloader
        if (substr($class, - 7) !== '\Module') {
            return false;
        }
        $moduleName = substr($class, 0, - 7);
        if (isset($this->explicitPaths[$moduleName])) {
            if ($classLoaded = $this->loadModuleFromDir(
            $this->explicitPaths[$moduleName], $class)) {
                return $classLoaded;
            } elseif ($classLoaded = $this->loadModuleFromPhar(
            $this->explicitPaths[$moduleName], $class)) {
                return $classLoaded;
            }
        }
        
        $moduleClassPath = str_replace('\\', DIRECTORY_SEPARATOR, $moduleName);
        
        foreach ($this->paths as $path) {
            $path = $path . $moduleClassPath;
            if ($classLoaded = $this->loadModuleFromDir($path, $class)) {
                return $classLoaded;
            }
            // No directory with Module.php, searching for phars
            //$moduleName = substr($class, 0, strpos($class, '\\'));
            

            // Find executable phars
            $matches = glob(
            $path . '.{' . implode($this->pharExtensions, ',') . '}', GLOB_BRACE);
            foreach ($matches as $phar) {
                if ($classLoaded = $this->loadModuleFromPhar($phar, $class)) {
                    return $classLoaded;
                }
            }
        }
        return false;
    }

    /**
     * loadModuleFromDir 
     * 
     * @param string $dirPath 
     * @param string $class 
     * @return  mixed
     * False [if unable to load $class]
     * get_class($class) [if $class is successfully loaded]
     */
    protected function loadModuleFromDir ($dirPath, $class)
    {
        $file = new SplFileInfo($dirPath . '/Module.php');
        if ($file->isReadable() && $file->isFile()) {
            // Found directory with Module.php in it
            require_once $file->getRealPath();
            if (class_exists($class)) {
                return $class;
            }
        }
        return false;
    }

    /**
     * loadModuleFromPhar 
     * 
     * @param string $pharPath 
     * @param string $class 
     * @return  mixed
     * False [if unable to load $class]
     * get_class($class) [if $class is successfully loaded]
     */
    protected function loadModuleFromPhar ($pharPath, $class)
    {
        $pharPath = static::normalizePath($pharPath, false);
        $file = new SplFileInfo($pharPath);
        if (! $file->isReadable() || ! $file->isFile()) {
            return false;
        }
        // Phase 0: Check for executable phar with Module class in stub
        if (strpos($file->getRealPath(), '.phar') !== false) {
            // First see if the stub makes the Module class available
            require_once $file->getRealPath();
            if (class_exists($class)) {
                return $class;
            }
        }
        // Phase 1: Not executable phar, no stub, or stub did not provide Module class; try Module.php directly
        $moduleClassFile = 'phar://' . $file->getRealPath() .
         '/Module.php';
        $moduleFile = new SplFileInfo($moduleClassFile);
        if ($moduleFile->isReadable() && $moduleFile->isFile()) {
            require_once $moduleClassFile;
            if (class_exists($class)) {
                return $class;
            }
        }
        // Phase 2: Check for nested module directory within archive
        // Checks for /path/to/MyModule.tar/MyModule/Module.php 
        // (shell-integrated zip/tar utilities wrap directories like this)
        $pharBaseName = $this->pharFileToModuleName(
        $file->getRealPath());
        $moduleClassFile = 'phar://' . $file->getRealPath() . '/' . $pharBaseName .
         '/Module.php';
        $moduleFile = new SplFileInfo($moduleClassFile);
        if ($moduleFile->isReadable() && $moduleFile->isFile()) {
            require_once $moduleClassFile;
            if (class_exists($class)) {
                return $class;
            }
        }
        return false;
    }

    /**
     * Register the autoloader with spl_autoload registry
     * 
     * @return void
     */
    public function register ()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Unregister the autoloader with spl_autoload registry
     * 
     * @return void
     */
    public function unregister ()
    {
        $test = spl_autoload_unregister(array($this, 'autoload'));
    }

    /**
     * registerPaths 
     * 
     * @param array|Traversable $paths 
     * @return ModuleLoader
     */
    public function registerPaths ($paths)
    {
        if (is_array($paths) || $paths instanceof Traversable) {
            foreach ($paths as $module => $path) {
                if (is_string($module)) {
                    $this->registerPath($path, $module);
                } else {
                    $this->registerPath($path);
                }
            }
        } else {
            throw new \InvalidArgumentException(
            'Parameter to \\Zend\\Loader\\ModuleAutoloader\'s ' .
             'registerPaths method must be an array or ' .
             'implement the \\Traversable interface');
        }
        return $this;
    }

    /**
     * registerPath 
     * 
     * @param string $path 
     * @param string $moduleName 
     * @return ModuleLoader
     */
    public function registerPath ($path, $moduleName = false)
    {
        if (! is_string($path)) {
            throw new \InvalidArgumentException(
            sprintf('Invalid path provided; must be a string, received %s', 
            gettype($path)));
        }
        if ($moduleName) {
            $this->explicitPaths[$moduleName] = static::normalizePath($path);
        } else {
            $this->paths[] = static::normalizePath($path);
        }
        return $this;
    }

    /**
     * getPaths 
     *
     * This is primarily for unit testing, but could have other uses.
     * 
     * @return array
     */
    public function getPaths ()
    {
        return $this->paths;
    }

    /**
     * Returns the base module name from the path to a phar
     * 
     * @param string $pharPath 
     * @return string
     */
    protected function pharFileToModuleName ($pharPath)
    {
        do {
            $pathinfo = pathinfo($pharPath);
            $pharPath = $pathinfo['filename'];
        } while (isset($pathinfo['extension']));
        return $pathinfo['filename'];
    }

    /**
     * Normalize a path for insertion in the stack
     * 
     * @param  string $path 
     * @return string
     */
    public static function normalizePath ($path, $trailingSlash = true)
    {
        $path = rtrim($path, '/');
        $path = rtrim($path, '\\');
        if ($trailingSlash) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path;
    }
}
