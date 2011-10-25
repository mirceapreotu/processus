<?php
/**
 * 
 */
abstract class Core_Abstracts_AbstractManager
{
	protected static $_mysqlInstance;

	/**
	 * @return
	 */
	public function getMysqlInstance()
	{
		if (self::$_mysqlInstance instanceof Core_Lib_Db_MySQL !== TRUE)
		{
			self::$_mysqlInstance = Core_Lib_Db_MySQL::getInstance();
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
		if (self::$_couchDbInstance instanceof Core_Lib_Db_CouchDb !== TRUE)
		{
			self::$_couchDbInstance = Core_Lib_Db_CouchDb::getInstance();
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
		if (self::$_memcachedInstance instanceof Core_Lib_Db_Memcached !== TRUE)
		{
			self::$_memcachedInstance = Core_Lib_Db_Memcached::getInstance();
		}

		return self::$_memcachedInstance;
	}
}

?>