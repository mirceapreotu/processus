<?php

class App_Model_FooModel extends Core_Abstracts_AbstractManager
{
	public function foo()
	{
		$mysql = Core_Lib_Db_MySQL::getInstance();
		return $mysql->fetch('SELECT id, facebook_id FROM users LIMIT 10');
	}
}

?>
