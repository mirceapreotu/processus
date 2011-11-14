<?php

/**
 *
 * @author francis
 *
 */
namespace Processus\Lib\Server
{
    use Processus\Lib\Db\MySQL;

    use Processus\Lib\Db\Memcached;

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
         *
         * @return Memcached
         */
        public static function memcachedFactory(string $host, string $port, $id = "default")
        {
            $poolKey = md5($host . $port . $id);

            if (array_key_exists($poolKey, self::$_couchbasePool) === FALSE) {

                $memcached = new Memcached($host, $port, $poolKey);
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
        public static function mysqlFactory(string $adapter, array $params)
        {
            $poolKey = md5($adapter . join('', $params));

            if (array_key_exists($poolKey, self::$_mysqlPool) === FALSE) {
                $mysql = new MySQL();
                $mysql->init($adapter, $params);
                self::$_mysqlPool[$poolKey] = $mysql;
            }

            var_dump(array($mysql, $adapter, $params, $poolKey));

            return $mysql;
        }
    }
}
?>