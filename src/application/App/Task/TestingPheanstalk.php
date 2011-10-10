<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:21 AM
 * To change this template use File | Settings | File Templates.
 */

    class App_Task_TestingPheanstalk
    {

        public function run()
        {

            var_dump("Testing Pheanstalk");

            $pheanstalk = new Pheanstalk('127.0.0.1');

            // ----------------------------------------
            // producer (queues jobs)

            $pheanstalk
                    ->useTube('testtube')
                    ->put("job payload goes here\n");

            // ----------------------------------------
            // worker (performs jobs)

            $job = $pheanstalk
                    ->watch('testtube')
                    ->ignore('default')
                    ->reserve();

            echo $job->getData();

            $pheanstalk->delete($job);
        }

    }
