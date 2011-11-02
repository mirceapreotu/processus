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
         * @param string $host
         * @param string $port
         * @param string $id
         * @return \Processus\Lib\Db\Memcached
         */
        public static function memcachedFactory(string $host, string $port, $id = "default")
        {
            $poolKey = md5($host . $port . $id);
            
            if (array_key_exists($poolKey, self::$_couchbasePool) === FALSE) {
                
                $memcached = new Memcached($host, $port, $id);
                self::$_couchbasePool[$poolKey] = $memcached;
            
            }

            return self::$_couchbasePool[$poolKey];
        }

        /**
         * @param array $mysqlConfig
         * @return \Processus\Lib\Db\MySQL
         */
        public static function mysqlFactory(string $host, string $port)
        {
            $mysql = new MySQL();
            return $mysql;
        }
    }
}
?>