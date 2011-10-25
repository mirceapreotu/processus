<?php
/**
 *
 */
class Core_GaintS_Lib_MySQL
{
	/**
	 * @var
	 */
	private static $_instance;

	/**
	 * @var
	 */
	public $dbh;


    // #########################################################

	/**
	 * @static
	 * @return
	 */
	public static function getInstance()
	{
		if(self::$_instance instanceof self !== TRUE)
		{
			self::$_instance = new self();
			self::$_instance->init();
		}

		return self::$_instance;
	}


    // #########################################################


	/**
	 * @return void
	 */
	public function init()
	{
		$registry = Core_Registry::getInstance();
        $this->dbh = Zend_Db::factory($registry->getConfig('database')->adapter, $registry->getConfig('database')->params->toArray());
	}


    // #########################################################


	/**
	 * @param null $sql
	 * @param array $args
	 * @return Zend_Db_Statement_Pdo
	 */
	private function _prepare($sql = NULL, $args = array())
	{
		$stmt = new Zend_Db_Statement_Pdo($this->dbh, $sql);
		$stmt->setFetchMode(Zend_DB::FETCH_OBJ);
		$stmt->execute($args);
		return $stmt;
	}


    // #########################################################


	/**
	 * @param null $sql
	 * @param array $args
	 * @return string
	 */
	public function fetchValue($sql = NULL, $args = array())
	{
		return $this->_prepare($sql, $args)->fetchColumn();
	}


    // #########################################################


	/**
	 * @param null $sql
	 * @param array $args
	 * @return Zend_Db_Statement_Pdo
	 */
	public function fetch($sql = NULL, $args = array())
	{
		return $this->_prepare($sql, $args);
	}


    // #########################################################


	/**
	 * @param null $sql
	 * @param array $args
	 * @return mixed
	 */
	public function fetchOne($sql = NULL, $args = array())
	{
		return $this->_prepare($sql, $args)->fetchObject();
	}


    // #########################################################


	/**
	 * @param null $sql
	 * @param array $args
	 * @return array
	 */
	public function fetchAll($sql = NULL, $args = array())
	{
		return $this->_prepare($sql, $args)->fetchAll();
	}


    // #########################################################


	/**
	 * @param null $sql
	 * @param array $args
	 * @return Zend_Db_Statement_Pdo
	 */
	public function query($sql = NULL, $args = array())
	{
		return $this->_prepare($sql, $args);
	}
}

?>
