<?php

    namespace Processus\Abstracts\Manager
    {
        use Processus\Lib\ServerFactory;

		use Processus\Lib\Db\MySQL;
        use Processus\Lib\Db\CouchDb;
        use Processus\Lib\Db\Memcached;

        /**
         *
         */
        abstract class AbstractManager
        {

            protected static $_mysqlInstance;

            /**
             * @return Core_Lib_Db_MySQL
             */
            public function getMysqlInstance()
            {
                if (self::$_mysqlInstance instanceof MySQL !== TRUE) {
                    /** @var $_mysqlInstance Core_Lib_Db_MySQL */
                    self::$_mysqlInstance = MySQL::getInstance();
                }

                return self::$_mysqlInstance;
            }

            // #########################################################


            protected static $_couchDbInstance;

            /**
             * @return
             */
            public function getCouchDbInstance()
            {
                if (self::$_couchDbInstance instanceof CouchDb !== TRUE) {
                    self::$_couchDbInstance = CouchDb::getInstance();
                }

                return self::$_couchDbInstance;
            }

            // #########################################################


            protected static $_memcachedInstance;

            /**
             * @return
             */
            public function getMemcachedInstance()
            {                
                if (self::$_memcachedInstance instanceof Memcached !== TRUE) {
                    self::$_memcachedInstance = Memcached::getInstance();
                }

                return self::$_memcachedInstance;
            }
        }
    }
?>