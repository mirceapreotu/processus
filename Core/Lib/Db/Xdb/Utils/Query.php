<?php
/**
 * Lib_Db_Xdb_Utils_Query
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Db_Xdb_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Db_Xdb_Utils_Query
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Db_Xdb_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class Lib_Db_Xdb_Utils_Query
{

	/**
	 * @static
	 * @param  $value
	 * @return bool
	 */
	public static function isValidSqlValue($value)
	{
		$isValid = false;
		if ($value === null) {
			$isValid = true;
		}
		if (is_string($value)) {
			$isValid = true;
		}

		if (is_bool($value)) {
			$isValid = true;
		}

		if (is_int($value)) {
			$isValid = true;
		}

		if (is_float($value)) {
			$isValid = true;
		}
		return $isValid;
	}

/*
	public static function getSqlValue($value)
	{
		if (is_null($value)) {return "NULL";}
		if (is_string($value)) {
			return $this->escape($value);
		}
		if (is_bool($value)) {
			if ($value===true) {return "TRUE";}
			if ($value===false) {return "FALSE";}
		}
		if (is_float($value)) {
			return $value;
		}
		if (is_int($value)) {
			return $value;
		}


		$reason=array(
			"method"=>__METHOD__,
			"value"=>$value,
			"lastUnpreparedStatement"=>$this->getLastUnpreparedStatement()

		);
		throw new XDBException("INVALID_DATA_TYPE",$reason);


		// else: cast to string //

		$_value=$this->escape("".$value);

		return $_value;
	}
*/


	/**
	 * @static
	 * @throws Exception
	 * @param  $table
	 * @return void
	 */
	public static function validateTable($table)
	{
		if (Lib_Utils_String::isEmpty($table)) {
			$message = "Parameter table must be a string and cant be empty!";
			$message .= " table=".$table;
			throw new Exception($message);
		}
	}


	/**
	 * @static
	 * @param  array|null $params
	 * @return array
	 */
	public static function prepareQueryParams($params)
	{
		$params = (array)$params;
		foreach ($params as $key => $value) {

			if ($value === null) {
				//$params[$key] = "NULL";
				continue;
			}
		}
		return $params;
	}


	/**
	 * @static
	 * @throws Exception
	 * @param  array|null $params
	 * @return void
	 */
	public static function validateQueryParams($params)
	{
		if ($params === null) {
			return;
		}
		if (is_array($params)!==true) {
			$message = "Invalid Query Params. Must be array or null!";
			$message .= " params =".$params;
			throw new Exception($message);
		}

		foreach ($params as $key => $value) {

			if (Lib_Utils_String::isEmpty($key)===true) {
				$message = "Invalid key in QueryParams! Key must be a string!";
				$message .= " key=".$key;
				throw new Exception($message);
			}
			if (self::isValidSqlValue($value) !== true) {
				$message = "Invalid value in QueryParams!";
				$message .= " key=".$key." value = ".$value;
				throw new Exception($message);
			}
		}
	}




	/**
	 * @static
	 * @param  array $row
	 * @return array
	 */
	public static function prepareInsertOrUpdateRow($row)
	{
		$row = (array)$row;
		foreach ($row as $key => $value) {

			if ($value === null) {
				//$params[$key] = "NULL";
				continue;
			}
		}
		return $row;
	}


	/**
	 * @static
	 * @throws Exception
	 * @param  array|null $params
	 * @return void
	 */
	public static function validateInsertOrUpdateRow($row)
	{
		if (Lib_Utils_Array::isEmpty($row)===true) {
			$message = "Invalid insert/update row.";
			$message .= " Must be array and cant be empty!";
			$message .= " row =".$row;
			throw new Exception($message);
		}

		foreach ($row as $key => $value) {

			if (Lib_Utils_String::isEmpty($key)===true) {
				$message = "Invalid key in insert/update row!";
				$message .= " Key must be a string and cant be empty!";
				$message .= " key=".$key;
				throw new Exception($message);
			}
			if (self::isValidSqlValue($value) !== true) {
				$message = "Invalid value in insert/update row!";
				$message .= " key=".$key." value = ".$value;
				throw new Exception($message);
			}
		}
	}




	/**
	 * @static
	 * @throws Exception
	 * @param  string $sql
	 * @return array
	 */
	public static function findParamsExpected($sql)
	{
		if (is_string($sql)!==true) {
			throw new Exception("[".__METHOD__.":] Parameters sql must be a string! sql = ".$sql);
		}
		preg_match_all('/(:\w+)/', $sql, $found);

		$list = array();
		$found = (array)$found;
		foreach ($found as $item) {
			if (is_array($item)!==true) {
				continue;
			}
			foreach ($item as $index => $value) {
				$list[] = $value;
			}
		}
		$list = (array)array_unique($list);
		return $list;
	}


	/**
	 * @static
	 * @throws Exception
	 * @param  string $sql
	 * @param  array|null $params
	 * @return array
	 */
	public static function findParamsMissing($sql,$params)
	{

		if (is_string($sql)!==true) {
			throw new Exception("[".__METHOD__.":] Parameters sql must be a string! sql = ".$sql);
		}

		$paramsExpected = (array)self::findParamsExpected($sql);

		$params = (array)$params;

		$search = array();
        foreach ($params as $key => $value) {
            $search[] = ":".$key."";
        }


		$paramsMissing = array();
        foreach ($paramsExpected as $paramName) {
        	if (in_array($paramName, $search, true)!==true) {
        		$paramsMissing[] = $paramName;
        	}
        }
        return $paramsMissing;
	}



	/**
	 * @static
	 * @throws Exception
	 * @param  string $sql
	 * @param  array|null $bindParams
	 * @return void
	 */
	public static function validateParamsMissing($sql, $bindParams)
	{
		$paramsMissing = self::findParamsMissing($sql, $bindParams);

		if (count($paramsMissing)>0) {
			$message = "Sql query params missing!";
			$message .= " Missing: ".json_encode($paramsMissing);
			$message .= " Given: ".json_encode($bindParams);
			throw new Exception($message);
		}
	}




	/**
	 * @static
	 * @param  $stmt
	 * @param  array $rows
	 * @return array
	 */
	public static function getQualifiedRows($stmt, $rows)
	{
		$result = array();
       // var_dump(version_compare(PHP_VERSION, '5.2.3'));
        
        /*
        var_dump(__METHOD__);
var_dump(function_exists("php_version"));
        if (version_compare(PHP_VERSION, '5.4.3') === -1) {
            throw new Exception(
                "Method ".__METHOD__." requires a higher php version"
            );
        }
 */

        //table field


		/*   FETCH_NUM
			[
			 		["1",null,"update to reset98","0","EXTKEY"]
			 	,	["7",null,"foo65","0",""]]


			 */

		$firstRow = Lib_Utils_Array::getProperty($rows, 0, true);
		if (is_array($firstRow) !== true) {
			return $result;
		}
		$colsCount = count($firstRow);
		$colsMeta = array();
		for ($i=0; $i<$colsCount; $i++) {
			$cm = (array)$stmt->getColumnMeta($i);
            $colsMeta[$i] = $cm;
            /*
            $cmKeys = array();
            foreach($cm as $key => $value) {
                $cmKeys[] = "".$key;
            }
            var_dump(implode("_",$cmKeys));
            */
			/*
			 {
				"native_type":"LONGLONG"
				,"flags":["not_null","primary_key"]
				,"table":"Seb",
				"name":"id",
				"len":20,
				"precision":0,
				"pdo_type":2
			 }
			 */

            

		}
//var_dump($colsCount);
//var_dump($rows);
		$_rows = array();
		
		foreach ($rows as $row) {

			$_row = array();
			for ($i=0; $i<$colsCount; $i++) {

				$colIndex = $i;
				$colMeta = $colsMeta[$colIndex];

				$fieldValue = $row[$colIndex];

				$colMetaTableName = $colMeta["table"];
				if (Lib_Utils_String::isEmpty($colMetaTableName)) {
					$colMetaTableName = "";
				}

				$colMetaFieldName = $colMeta["name"];
				$fieldQualifiedName =
					$colMetaTableName.".".$colMetaFieldName;
				$_row[$fieldQualifiedName] = $fieldValue;


			}
			$_rows[] = $_row;
		}

		$result = (array)$_rows;

		return $result;

	}


	/**
	 * @static
	 * @param  string $fieldName
	 * @return null|string
	 */
	public static function getTableNameFromQualifiedFieldName($fieldName)
	{

		$delimiter = ".";
		$prefix = Lib_Utils_String::getPrefixByDelimiter(
			$fieldName,
			$delimiter
		);

		if (Lib_Utils_String::isEmpty($prefix)===true) {
			return null;
		}

		return $prefix;
	}


	/**
	 * @static
	 * @param  string $fieldName
	 * @return null|string
	 */
	public static function getFieldNameFromQualifiedFieldName($fieldName)
	{
		$delimiter = ".";
		$postfix = Lib_Utils_String::getPostfixByDelimiter(
			$fieldName,
			$delimiter
		);

		if (Lib_Utils_String::isEmpty($postfix)===true) {
			return null;
		}

		return $postfix;
	}


	/**
	 * @static
	 * @param  string $fieldNameOrQualifiedName
	 * @return null|string
	 */
	public static function getFieldName($fieldNameOrQualifiedName)
	{
		$delimiter = ".";
		if (is_string($fieldNameOrQualifiedName)!==true) {
			return null;
		}

		$fieldName = Lib_Utils_String::removePrefixByDelimiterIfExists(
			$fieldNameOrQualifiedName,
			$delimiter
		);
		if (is_string($fieldName)===true) {
			return $fieldName;
		}

		return null;
	}


	/**
	 * @static
	 * @param  $row
	 * @return array|mixed
	 */
	public static function removeTableNameFromRow($row)
	{
		if (is_array($row)!==true) {
			return $row;
		}
		$delimiter = ".";
		$_row = array();
		foreach ($row as $key => $value) {
			$_key = Lib_Utils_String::removePrefixByDelimiterIfExists(
				$key,
				$delimiter
			);
			$_row[$_key] = $value;
		}
		return $_row;
	}


	/**
	 * @static
	 * @param  $rows
	 * @return array|mixed
	 */
	public static function removeTableNameFromRows($rows)
	{
		if (is_array($rows)!==true) {
			return $rows;
		}

		$_rows = array();
		foreach ($rows as $row) {
			$_row = self::removeTableNameFromRow($row);
			$_rows[] = $_row;
		}

		return $_rows;
	}


	/**
	 * @static
	 * @param  array $row
	 * @param  string $tableName
	 * @return array
	 */
	public static function collectFieldsFromRowByTableName($row, $table)
	{
		$result = array();
		if (is_array($row)!==true) {
			return $result;
		}
		if (is_string($table)!==true) {
			return $result;
		}

		$_row = array();

		$prefix = "".$table.".";
		$ignoreCase = false;

		foreach ($row as $key => $value) {
			if (
                Lib_Utils_String::startsWith(
                    $key, $prefix, $ignoreCase
                )===true
            ) {
				$_row[$key] = $value;
			}
		}
		return $_row;
	}

    /**
     * @static
     * @param array $rows
     * @param string $table
     * @return array
     */
	public static function collectFromRowsByTableName($rows, $table)
	{
		$result = array();
		if (is_array($rows)!==true) {
			return $result;
		}
		if (is_string($table)!==true) {
			return $result;
		}

		$_rows = array();
		foreach ($rows as $row) {
			$_row = self::collectFieldsFromRowByTableName($row, $table);
			if (is_array($_row)!==true) {
				continue;
			}
			$_rows[] = $_row;
		}

		return $_rows;

	}




}
