<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Server
{

    use Processus\Lib\Db\CouchDb;

	use Processus\Lib\Db\MySQL;

	use Processus\Lib\Db\Memcached;

	class ServerFactory
    {

        /**
         * @param array $memcachedConfig
         * @return \Processus\Lib\Db\Memcached
         */
        public static function memcachedFactory (array $memcachedConfig)
        {    
            $memcached = new Memcached();
            
            return $memcached;
        }

        /**
         * @param array $mysqlConfig
         * @return \Processus\Lib\Db\MySQL
         */
        public static function mysqlFactory (array $mysqlConfig)
        {
            $mysql = new MySQL();
            return $mysql;
        }

        /**
         * @param array $couchDbConfig
         * @return \Processus\Lib\Db\CouchDb
         */
        public static function couchDbFactory (array $couchDbConfig)
        {
            $couchdb = new CouchDb();
            
            return $couchdb;
        }
    }
}
?>