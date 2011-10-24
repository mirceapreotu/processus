<?php

class App_Model_FooModel extends Core_GaintS_Core_AbstractManager
{
	public function foo()
	{
		$select = $this->getDatabase()
						->select()
						->from(
							array('u' => 'users'),
							array('facebook_id', 'locale')
						)
						->limit(10);

		$query = $select->query();

		return $query->fetchAll();
	}
}

?>
