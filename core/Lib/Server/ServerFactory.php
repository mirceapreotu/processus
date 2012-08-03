<?php

/**
 *
 * @author francis
 *
 */
namespace Processus\Lib\Server;

class ServerFactory
{

    /**
     * @var array
     */
    private static $_couchbasePool = array();

    /**
     * @var array
     */
    private static $_mysqlPool;

    /**
     * @static
     *
     * @param string $host
     * @param string $port
     * @param string $id
     * @param int    $memcachedFactoryType
     *
     * @return \Processus\Lib\Db\Memcached
     * @throws \Exception
     */
    public static function memcachedFactory(
        $host = "127.0.0.1", $port = "11211", $id = "default",
        $memcachedFactoryType = \Processus\Consta\MemcachedFactoryType::MEMCACHED_BINARY
    )
    {
        $poolKey   = md5($host . $port . $id . $memcachedFactoryType);
        $memcached = NULL;

        if (array_key_exists($poolKey, self::$_couchbasePool) === FALSE) {
            switch ($memcachedFactoryType) {
                case \Processus\Consta\MemcachedFactoryType::MEMCACHED_BINARY:
                    $memcached = new \Processus\Lib\Db\Memcached($host, $port, $poolKey);
                    break;
                case \Processus\Consta\MemcachedFactoryType::MEMCACHED_JSON:
                    $memcached = new \Processus\Lib\Db\MemcachedJson($host, $port, $poolKey);
                    break;
                default:
                    throw new \Exception("FactoryType not declared");
            }

            self::$_couchbasePool[$poolKey] = $memcached;
        }

        return self::$_couchbasePool[$poolKey];
    }

    /**
     * @static
     *
     * @param string $adapter
     * @param array  $params
     *
     * @return MySQL
     */
    public static function mysqlFactory(\string $adapter, array $params)
    {
        $poolKey = md5($adapter . join('', $params));

        if (array_key_exists($poolKey, self::$_mysqlPool) === FALSE) {
            $mysql = new \Processus\Lib\Db\MySQL();
            $mysql->init($adapter, $params);
            self::$_mysqlPool[$poolKey] = $mysql;
        }

        var_dump(array($mysql, $adapter, $params, $poolKey));

        return $mysql;
    }
}

?>