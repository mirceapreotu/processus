<?php
/**
 * App_Model_Record_Abstract
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Record
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Record_Abstract
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Record
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class App_Model_Record_Abstract
{

	/**
	 * @var string
	 */
	protected $_dbTable;
	/**
	 * @var array|null
	 */
	protected $_dbRow;
	/**
	 * @var array
	 */
	protected $_dbFields;


    /**
     * @var string|null
     */
    protected $_importFieldNamePrefix = null;


	/**
	 * @param null|array $dbRow
	 *
	 */
	public function __construct($dbRow = null, $fieldNamePrefix = null)
	{
        if (is_string($fieldNamePrefix)) {
            $this->_importFieldNamePrefix = $fieldNamePrefix;
        }
		if (is_array($dbRow)) {
			$this->setDbRow($dbRow);
		}
	}



    /**
     * @return null|string
     */
    public function getImportFieldNamePrefix()
    {
        return $this->_importFieldNamePrefix;
    }

	/**
	 * @return App_Model_Record_Abstract
	 */
	public function parse()
	{
		return $this;
	}


	/**
	 * @return App_Model_Record_Abstract
	 */
	public function reset()
	{
		return $this;
	}


	/**
	 * @return bool
	 */
	public function exists()
	{
		$result = false;
		return $result;
	}


	/**
	 * @param  array|null $dbRow
	 * @return App_Model_Record_Abstract
	 */
	public function setDbRow($dbRow)
	{
		$this->_dbRow = $dbRow;
		$this->parse();
		return $this;
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
		$result = "AbstractTable";
		throw new Exception("Implement in subclass! ".__METHOD__);
		return $result;
	}

	/**
	 * @return array|null
	 */
	public function getDbRow()
	{
		return $this->_dbRow;
	}

	/**
	 *
	 * @return array
	 */
	public function getDbFields()
	{
		$result = array();
		throw new Exception("Implement in subclass! ".__METHOD__);
		return $result;
	}

	/**
	 * @param  string $fieldName
	 * @return mixed
	 */
	public function getDbRowFieldValue($fieldName)
	{

        $importFieldNamePrefix = $this->getImportFieldNamePrefix();
        if (is_string($importFieldNamePrefix)) {
            //NOTICE: you must explicit define a delimiter "." if required
            $fieldQualifiedName = "".$importFieldNamePrefix
                    .$fieldName;
        } else {
            $fieldQualifiedName = $this->getDbFieldQualifiedName($fieldName);
        }

		$value = Lib_Utils_Array::getProperty(
			$this->getDbRow(),
			$fieldQualifiedName
		);
		return $value;
	}

	/**
	 * @param  string $fieldName
	 * @return string
	 */
	public function getDbFieldQualifiedName($fieldName)
	{
		$table = $this->getDbTable();
		$qualifiedFieldName = $table.".".$fieldName;
		return $qualifiedFieldName;
	}

	/**
	 * @return array
	 */
	public function getDbFieldsQualified()
	{
		$fields = (array)$this->getDbFields();
		$table = $this->getDbTable();
		$qualifiedFields = array();
		foreach ($fields as $fieldName) {
			$qualifiedFieldName = $table.".".$fieldName;
			$qualifiedFields[] = $qualifiedFieldName;
		}
		return $qualifiedFields;
	}


    /**
	 * @return array
	 */
	public function getDbFieldsQualifiedCustomPrefixed()
	{
		$fields = (array)$this->getDbFields();
		$table = $this->getDbTable();
        $prefix = $table.".";

        if (is_string($this->getImportFieldNamePrefix())) {
            $prefix = $this->getImportFieldNamePrefix();
        }

		$qualifiedFields = array();
		foreach ($fields as $fieldName) {
			$qualifiedFieldName = $prefix.$fieldName;
			$qualifiedFields[] = $qualifiedFieldName;
		}
		return $qualifiedFields;
	}


	/**
	 * @param  string $name
	 * @param  mixed $value
	 * @return App_Model_Record_Abstract
	 */
	public function setDynamicProperty($name, $value)
	{
		$this->$name = $value;
		return $this;
	}





	/**
	 * @throws Exception
	 * @param  array $row
	 * @return array
	 */
    /*
	public function getRowWithDefinedFieldsForInsertOrUpdate($row)
	{
		$fields = $this->getDbFields();

		if (Lib_Utils_Array::isEmpty($row)) {
			$message = "Parameter row must be an array and cant be empty!";
			$message .=" recordClass =".$this->getClassName();
			$message .= " fields =".json_encode($fields);
			$message .=" row = ".json_encode($row);
			$message .= " at ".__METHOD__;
			throw new Exception($message);
		}

		if (Lib_Utils_Array::isEmpty($fields)) {
			$message = "Record fields must be an array and cant be empty!";
			$message .=" recordClass =".$this->getClassName();
			$message .= " fields =".json_encode($fields);
			$message .=" row = ".json_encode($row);
			$message .= " at ".__METHOD__;
			throw new Exception($message);
		}

		$_row = array();
		foreach ($row as $key => $value) {
			if (in_array($key, $fields, true)) {
				$_row[$key] = $value;
			}
		}

		return $_row;

	}
    */


}
