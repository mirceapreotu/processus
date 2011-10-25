<?php
/**
 * Lib_Model_Dao_Store_Json
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Model_Dao_Store
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Model_Dao_Store_Json
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Model_Dao_Store
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
abstract class Lib_Model_Dao_Store_Json
{
     /**
     * @var Lib_Model_Dao_Store_Json
     */
    private static $_instance;
    
	/**
	 * @var string
	 */
	protected $_dbTable;

    /**
     * @var string
     */
    protected $_dbKeyColumn = "key";
    /**
     * @var string
     */
    protected $_dbValueColumn = "value";


     /**
     * @static
     * @throws Exception
     * @return Lib_Model_Dao_Store_Json
     */
    public static function getInstance()
    {
        throw new Exception('Implement in subclass! '.__METHOD__);
        /*
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
         
         */
    }

    /**
     * @return string
     */
    public function getCurrentDate()
    {
        return Bootstrap::getRegistry()->getCurrentDate();
    }

	/**
	 * @return Lib_Db_Xdb_Client
	 */
	public function getDbClient()
	{
		return Bootstrap::getRegistry()->getXdbClient();
	}

    /**
     * @return string
     */
    public function getClassName()
    {
        return get_class($this);
    }

    /**
     * @return string
     */
    public function getDbTable()
    {
        return $this->_dbTable;
    }


    /**
     * @return string
     */
    public function getDbKeyColumnName()
    {
        return $this->_dbKeyColumn;
    }

    /**
     * @return string
     */
    public function getDbValueColumnName()
    {
        return $this->_dbValueColumn;
    }

    /**
     * @throws Exception
     * @param  $key
     * @return mixed|null
     */
    public function loadValue($key)
    {
        $dbClient = $this->getDbClient();

        $keyColumn = trim($this->getDbKeyColumnName());
        $valueColumn = trim($this->getDbValueColumnName());
        $table = trim($this->getDbTable());

        $tableQuoted = $dbClient->quoteIdentifier($table);
        $keyColumnQuoted = $dbClient->quoteIdentifier($keyColumn);
        $valueColumnQuoted = $dbClient->quoteIdentifier($valueColumn);

        $key = trim($key);

        if (Lib_Utils_String::isEmpty($keyColumn)) {
            throw new Exception(
                "Invalid keyColumn name=".$keyColumn." at "
                . __METHOD__." called class=".get_class($this)
            );
        }

        if (Lib_Utils_String::isEmpty($valueColumn)) {
            throw new Exception(
                "Invalid keyColumn name=".$valueColumn." at "
                . __METHOD__." called class=".get_class($this)
            );
        }


        if (Lib_Utils_String::isEmpty($table)) {
            throw new Exception(
                "Invalid table name=".$table." at "
                . __METHOD__." called class=".get_class($this)
            );
        }

        if (Lib_Utils_String::isEmpty($key)) {
            throw new Exception(
                "Invalid key=".$key." at "
                . __METHOD__." called class=".get_class($this)
            );
        }




        $sql = "SELECT
        ".$tableQuoted.".*
        FROM
        ".$tableQuoted."
        WHERE
        ".$tableQuoted.".".$keyColumnQuoted." =:key
        LIMIT 1;
        ";
        $params = array(
            "key" => (string)$key,
        );
        $row = $dbClient->getRow($sql, $params, false);
       // return $row;

        $value = Lib_Utils_Array::getProperty($row, $valueColumn, true);
        if (is_string($value)) {
            $value = json_decode($value, true);
        } else {
            $value = null;
        }

        return $value;
    }


    /**
     * @throws Exception
     * @param  string $key
     * @param  mixed $value
     * @return 
     */
    public function saveValue($key, $value)
    {

        $dbClient = $this->getDbClient();

        $keyColumn = trim($this->getDbKeyColumnName());
        $valueColumn = trim($this->getDbValueColumnName());
        $table = trim($this->getDbTable());

        $key = trim($key);

        if (Lib_Utils_String::isEmpty($keyColumn)) {
            throw new Exception(
                "Invalid keyColumn name=".$keyColumn." at "
                . __METHOD__." called class=".get_class($this)
            );
        }

        if (Lib_Utils_String::isEmpty($valueColumn)) {
            throw new Exception(
                "Invalid keyColumn name=".$valueColumn." at "
                . __METHOD__." called class=".get_class($this)
            );
        }


        if (Lib_Utils_String::isEmpty($table)) {
            throw new Exception(
                "Invalid table name=".$table." at "
                . __METHOD__." called class=".get_class($this)
            );
        }

        if (Lib_Utils_String::isEmpty($key)) {
            throw new Exception(
                "Invalid key=".$key." at "
                . __METHOD__." called class=".get_class($this)
            );
        }


        $valueJSON = json_encode($value);
        if (is_string($valueJSON)!==true) {
            throw new Exception("JsonEncode(value) failed! at ".__METHOD__);
        }

        $rowInsert = array(
            "key" => (string)$key,
            "value"=>(string)$valueJSON,
        );
        $rowUpdate = array(
            "value" => (string)$valueJSON,
        );

        $dbClient->insertOrUpdate($table, $rowInsert, $rowUpdate);

    }
}
