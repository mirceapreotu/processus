<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/27/11
 * Time: 12:09 AM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_FlushMemcached extends App_GaintS_Core_AbstractTask
{
    public function run()
    {
        $port = "11211";
        $host = "127.0.0.1";
        $memServer = new Memcached();
        $memServer->addServer($host, $port);
        $memServer->flush();
    }
}
