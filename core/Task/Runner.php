<?php

namespace Processus\Task
{

    class Runner
    {

        /**
         * @var int|null
         */
        protected static $_pid = null;

        /**
         * @static
         * @return string
         */
        protected static function _getTaskClassname()
        {
            
            $invokedBySymlink = false;
            $command = @$_SERVER['argv'][0];
            
            // case 1:	php file is symlink pointing to the taskrunner
            //			In this case, use the symlink name as task name.
            // 			Rule: foo_bar_baz becomes App_Task_FooBarBaz
            

            if (is_link($command)) {
                
                $commandName = basename(trim($command));
                
                $taskname = '';
                
                // rule: convert characters following underscores to uppercase
                $taskParts = explode('_', $commandName);
                foreach ($taskParts as $taskPart) {
                    
                    $taskname .= ucfirst($taskPart);
                }
                
                $invokedBySymlink = true;
            
            }
            else {
                
                // case 2: Just use the first parameter as task name

                $taskname = trim(@$_SERVER['argv'][1]);
                
                // simple check for list parameter
                

                if ($taskname == '') {
                    
                    echo "Available tasks to run:\n";
                    
                    foreach (new \DirectoryIterator(PATH_ROOT . '/application/App/Task') as /** @var DirectoryIterator $fileInfo */
                    $fileInfo) {
                        
                        if ($fileInfo->isDot())
                            continue;
                        if ($fileInfo->isDir())
                            continue;
                        
                        $taskName = $fileInfo->getBasename('.php');
                        
                        echo "  $taskName\n";
                    
                    }
                    exit(0);
                }
            }
            
            // strip class name from command line
            if (! $invokedBySymlink) {
                
                unset($_SERVER['argv'][1]);
            }

            return "\\Application\\Task\\" . $taskname;
        }

        /**
         * Runs a task specified on the commandline (CLI)
         * 
         * @static
         * @return void
         */
        public static function run()
        {
            
            self::$_pid = getmypid();
            
            $taskClassName = self::_getTaskClassname();
            
            try {
                
                // we need to suppress errors because the autoloaders exceptions
                // can not be catched:
                $existsClass = class_exists($taskClassName);
            
            }
            catch (\Exception $exception) {
                
                unset($exception); // not needed
                $existsClass = false; // set to false on autoload exception

            }
            
            if ($existsClass) {
                
                /** @var $task \Processus\Abstracts\AbstractTask */
                $task = new $taskClassName();
                $task->run();
            
            }
            else {
                
                echo "Error: Unable to load task [" . $taskClassName . "]\n";
                exit(1);
            }
        
        }
    }
}