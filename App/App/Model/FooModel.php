<?php

class App_Model_FooModel extends Core_GaintS_Core_AbstractManager
{
	public function foo()
	{
		$mysql = Core_GaintS_Lib_MySQL::getInstance();

		echo '<h1>Insert</h1>';
		echo '<pre>';
		print_r($mysql->fetchValue('select facebook_id from users ORDER BY ID ASC limit 10'));
		echo '</pre><hr>';
		exit;

	}
}

?>
