<?php

/** 
 * @author fightbulc
 * 
 * 
 */
namespace Processus\Abstracts
{
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
    }
}

?>