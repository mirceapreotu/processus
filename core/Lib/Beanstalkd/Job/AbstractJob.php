<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:29 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Beanstalkd\Job
{

    abstract class AbstractJob
    {

        /**
         * @abstract
         * @return void
         */
        abstract public function startJob();
    }
}