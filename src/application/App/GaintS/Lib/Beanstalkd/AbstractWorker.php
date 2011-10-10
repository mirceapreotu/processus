<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:28 AM
 * To change this template use File | Settings | File Templates.
 */
 
abstract class App_GaintS_Lib_Beanstalkd_AbstractWorker
{

    public function __construct()
    {
        $this->startWorker();
    }

    abstract public function startWorker();
}
