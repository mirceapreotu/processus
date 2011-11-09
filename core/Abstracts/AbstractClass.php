<?php

/** 
 * @author fightbulc
 * 
 * 
 */
namespace Processus\Abstracts
{
    use Processus\Lib\Vo\Configs\ProcessusConfig;

	use Processus\Application;

	abstract class AbstractClass
    {
        /**
         * @return Application
         */
        protected function getApplication()
        {
            return Application::getInstance();
        }
        
        /**
         * @return mixed | array | stdClass
         */
        protected function config()
        {
            return $this->getApplication()->getRegistry()->getConfig($this);
        }
    }
}

?>