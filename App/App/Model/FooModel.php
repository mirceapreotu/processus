<?php

class App_Model_FooModel extends Core_Abstracts_AbstractManager
{
	public function foo()
	{
		$mysql = $this->getMysqlInstance();
		return $mysql->fetch('SELECT id, facebook_id FROM users LIMIT 10');
	}
}

?>
