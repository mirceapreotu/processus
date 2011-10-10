<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:29 AM
 * To change this template use File | Settings | File Templates.
 */
 
abstract class App_GaintS_Lib_Beanstalkd_AbstractJob
{
    /**
     * @abstract
     * @return void
     */
    abstract public function startJob();
}
