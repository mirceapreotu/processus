<?php

/**
 * @author fightbulc
 *
 *
 */
namespace Processus\Abstracts;
abstract class AbstractClass
{

    /**
     * @return \Application\ApplicationContext
     */
    public function getApplicationContext()
    {
        return \Application\ApplicationContext::getInstance();
    }

    /**
     * @return \Processus\ProcessusContext
     */
    protected function getProcessusContext()
    {
        return \Processus\ProcessusContext::getInstance();
    }

    /**
     * @return mixed | array | stdClass
     */
    protected function config()
    {
        return $this->getProcessusContext()->getRegistry()->getConfig($this);
    }

    /**
     * @return \Processus\Lib\Profiler\ProcessusProfiler
     */
    protected function getProfiler()
    {
        return \Processus\Lib\Profiler\ProcessusProfiler::getInstance();
    }
}

?>