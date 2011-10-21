<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/5/11
 * Time: 2:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Core_GaintS_Core_ServerFactory
{

    /** @var array */
    protected static $_mysqlDBPool = array();

    /** @var array */
    protected static $_couchDBPool = array();

    /** @var array */
    protected static $_memcachedDBPool = array();


    public static function createMySQLServer($options, $isMaster)
    {
        $mysqlServer = null;

        return $mysqlServer;
    }

    /**
     * @static
     * @param $host
     * @param $port
     * @param string $serverKey
     * @return Memcached
     */
    public static function createMemcachedServer($host, $port, $serverKey = "default")
    {
        $memPoolId = md5($host . $port . $serverKey);

        if(!self::$_memcachedDBPool[3])
        {
            $memServer = new Memcached($serverKey);
            $memServer->addServer($host, $port);
            self::$_memcachedDBPool[$memPoolId] = $memServer;
        }

        return self::$_memcachedDBPool[$memPoolId];
    }

    public static function createCouchDBServer()
    {
        $couchServer = null;

        return $couchServer;
    }
}
