<?php
/**
 * 
 */
abstract class Core_Abstracts_AbstractManager
{

	protected static $_mysqlInstance;


    // #########################################################


	/**
	 * @return
	 */
	public function getMysqlInstance()
	{
		if(self::$_mysqlInstance instanceof Core_Lib_Db_MySQL !== TRUE)
		{
			self::$_mysqlInstance = Core_Lib_Db_MySQL::getInstance();
		}

		return self::$_mysqlInstance;
	}
}
