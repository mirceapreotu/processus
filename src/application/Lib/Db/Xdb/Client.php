<?php
/**
 * Lib_Db_Xdb_Client
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Db_Xdb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Db_Xdb_Client
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Db_Xdb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */


class Lib_Db_Xdb_Client
{

    /**
     * @var int
     */
	private static $_masterRequestCount = 0;


	/**
	 * Increment Master DB usage count
	 *
	 * @return void
	 */
	public function beginMaster()
	{
		self::$_masterRequestCount++;
	}

	/**
	 * Decrement Master DB usage count (but stay at zero ...)
	 * @static
	 * @return void
	 */
	public function endMaster()
	{
		self::$_masterRequestCount = max(0, (self::$_masterRequestCount - 1));
	}

	/**
	 *
	 * @return bool true if master is requested somewhere
	 */
	public function isMaster()
	{
		return (self::$_masterRequestCount > 0);
	}

	/**
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	public function getZendClient()
	{
		return Bootstrap::getRegistry()->getDb();
	}

	/**
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	public function getZendClientFromPool()
	{
		if ($this->isMaster()) {
			return Bootstrap::getRegistry()->getDb();
		} else {

            throw new Exception("Implement get slave db at ".__METHOD__);
			//return Bootstrap::getRegistry()->getSlaveDb();
		}
	}

	/**
	 * @return Lib_Db_Xdb_Client
	 */
	public function beginTransaction()
	{
		$this->beginMaster();
		$result = $this;
		$this->getZendClient()->beginTransaction();
		return $result;
	}

	/**
	 * @return Lib_Db_Xdb_Client
	 */
	public function commitTransaction()
	{
		$result = $this;
		$this->getZendClient()->commit();
		$this->endMaster();
		return $result;
	}

	/**
	 * @return Lib_Db_Xdb_Client
	 */
	public function rollbackTransaction()
	{
		$result = $this;
		$this->getZendClient()->rollBack();
		$this->endMaster();
		return $result;
	}


	/**
	 * @throws Exception
	 * @param  string $delimiter
	 * @param  array $array
	 * @return null|string
	 */
	public function implode($delimiter, $array)
	{
		$result = null;
		if (is_string($delimiter) !== true) {
			$message = "Parameter delimiter must be a string!"
			           . " delimiter =" . $delimiter
			           . " at " . __METHOD__;

			throw new Exception($message);
		}
		if (is_array($array) !== true) {
			$message = "Parameter array must be an array! array =" . $array
			           . " at " . __METHOD__;

			throw new Exception($message);
		}
		if (Lib_Utils_Array::isEmpty($array)) {
			return $result;
		}

		$result = "";
		$i = -1;
		foreach ($array as $value) {
			$i++;
			$quotedTerm = $this->quoteInto("?", $value);
			if ($i > 0) {
				$result .= $delimiter;
			}
			$result .= $quotedTerm;

		}

		if (Lib_Utils_String::isEmpty($result)) {
			$result = null;
		}
		return $result;
	}


	/**
	 * @param  $table
	 * @param  $rowInsert
	 * @param bool $returnId
	 * @return null|string
	 */
	public function insert(
		$table,
		$rowInsert,
		$returnId = false
	)
	{
		$result = $this->_insert($table, $rowInsert, $returnId);
		return $result;
	}


	/**
	 * @param  $table
	 * @param  $rowUpdate
	 * @param  $where
	 * @param null $params
	 * @return int|null
	 */
	public function update(
		$table,
		$rowUpdate,
		$where,
		$params = null
	)
	{
		$result = $this->_update($table, $rowUpdate, $where, $params);
		return $result;
	}


	/**
	 * @param  $table
	 * @param  $where
	 * @param null $params
	 * @return int|null
	 */
	public function delete(
		$table,
		$where,
		$params = null
	)
	{
		$result = $this->_delete($table, $where, $params);
		return $result;
	}


	/**
	 * @param  $table
	 * @param  $rowInsert
	 * @param  $rowUpdate
	 * @return bool|null
	 */
	public function insertOrUpdate(
		$table,
		$rowInsert,
		$rowUpdate
	)
	{
		$result = $this->_insertOrUpdate($table, $rowInsert, $rowUpdate);
		return $result;
	}


    /**
     * @param  string $table
     * @param  array $rowInsert
     * @param  array|null $rowUpdate
     * @param  array $increaseFieldsMap
     * @return bool
     */
	public function insertOrUpdateAndIncreaseColumnValues(
		$table,
		$rowInsert,
		$rowUpdate,
        $increaseFieldsMap
	)
	{
		$result = $this->_insertOrUpdateAndIncreaseColumnValues(
            $table,
            $rowInsert,
            $rowUpdate,
            $increaseFieldsMap
        );
		return $result;
	}



	/**
	 * @param  string $sql
	 * @param array|null $bindParams
	 * @return array
	 */
	public function getRows(
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false
	)
	{
		$validateParamsMissing = true;

		$result = (array)$this->_getRows(
			$sql,
			$bindParams,
			$qualifiedColumnNames,
			$validateParamsMissing
		);
		return $result;
	}

	/**
	 * @param  string $sql
	 * @param array|null $bindParams
	 * @return array
	 */
	public function getRowsDebug(
		$method,
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false
	)
	{
		$validateParamsMissing = true;

		$args = (array)func_get_args();
		var_dump(
			array(
			     $method,
			     $args,
			)
		);

		$result = (array)$this->_getRowsDebug(
			$sql,
			$bindParams,
			$qualifiedColumnNames,
			$validateParamsMissing
		);
		return $result;
	}

	/**
	 * @param  string $sql
	 * @param array|null $bindParams
	 * @return array
	 */
	public function getRowsAndDontValidateParamsMissing(
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false
	)
	{
		$validateParamsMissing = false;
		$result = (array)$this->_getRows(
			$sql,
			$bindParams,
			$qualifiedColumnNames,
			$validateParamsMissing
		);
		return $result;
	}


	/**
	 * @param  string $sql
	 * @param null|array $bindParams
	 * @param bool $qualifiedColumnNames
	 * @param null|int $cursorOrientation
	 * @param null|int $cursorOffset
	 * @return array|null
	 */
	public function getRow(
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false,
		$cursorOrientation = null,
		$cursorOffset = null
	)
	{
		$result = $this->_getRow(
			$sql,
			$bindParams,
			$qualifiedColumnNames,
			$cursorOrientation,
			$cursorOffset
		);
		return $result;
	}


	/**
	 * @param  string $sql
	 * @param null|array $bindParams
	 * @return array
	 */
	protected function _getRows(
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false,
		$validateParamsMissing = true
	)
	{
		try {

			$bindParams = Lib_Db_Xdb_Utils_Query::prepareQueryParams(
				$bindParams
			);

			Lib_Db_Xdb_Utils_Query::validateQueryParams($bindParams);
			if ($validateParamsMissing === true) {
				Lib_Db_Xdb_Utils_Query::validateParamsMissing(
					$sql,
					$bindParams
				);
			}
			$result = (array)$this->_fetchAll(
				$sql,
				$bindParams,
				$qualifiedColumnNames
			);

			return $result;

		} catch (Exception $exception) {
           
			$error = new Lib_Db_Xdb_Exception("GetRows failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);
			throw $error;
		}
	}


	/**
	 * @param  string $sql
	 * @param null|array $bindParams
	 * @return array
	 */
	protected function _getRowsDebug(
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false,
		$validateParamsMissing = true
	)
	{
		try {

			$bindParams = Lib_Db_Xdb_Utils_Query::prepareQueryParams(
				$bindParams
			);

			Lib_Db_Xdb_Utils_Query::validateQueryParams($bindParams);
			if ($validateParamsMissing === true) {
				Lib_Db_Xdb_Utils_Query::validateParamsMissing(
					$sql,
					$bindParams
				);
			}

			var_dump(
				array(
				  __METHOD__,
				  $sql,
				  $bindParams,
				  $qualifiedColumnNames
			      )
			);

			$result = (array)$this->_fetchAllDebug(
				$sql,
				$bindParams,
				$qualifiedColumnNames
			);

			return $result;

		} catch (Exception $exception) {

			$error = new Lib_Db_Xdb_Exception("GetRows failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);
			throw $error;
		}
	}


	/**
	 * @throws Lib_Db_Xdb_Exception
	 * @param  string $sql
	 * @param null|array $bindParams
	 * @param bool $qualifiedColumnNames
	 * @param null|int $cursorOrientation
	 * @param null|int $cursorOffset
	 * @return array|null
	 */
	protected function _getRow(
		$sql,
		$bindParams = null,
		$qualifiedColumnNames = false,
		$cursorOrientation = null,
		$cursorOffset = null
	)
	{
		try {

			$bindParams = Lib_Db_Xdb_Utils_Query::prepareQueryParams(
				$bindParams
			);

			Lib_Db_Xdb_Utils_Query::validateQueryParams($bindParams);
			Lib_Db_Xdb_Utils_Query::validateParamsMissing($sql, $bindParams);

			$result = $this->_fetchOne(
				$sql,
				$bindParams,
				$qualifiedColumnNames,
				$cursorOrientation,
				$cursorOffset
			);
			if (is_array($result) !== true) {
				$result = null;
			}
			return $result;

		} catch (Exception $exception) {
			$error = new Lib_Db_Xdb_Exception("GetRow failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);
			throw $error;
		}
	}


	/**
	 * @param string $sql
	 * @param array $bindParams
	 * @param bool $qualifiedColumnNames
	 * @return array
	 */
	protected function _fetchAll(
		$sql,
		$bindParams,
		$qualifiedColumnNames = false
	)
	{
		$db = $this->getZendClientFromPool();

		try {

			if ($qualifiedColumnNames === true) {
                if (    (function_exists("phpversion"))
                        && (function_exists("version_compare") )
                ){
                    if (version_compare(phpversion(), '5.2.3') === -1) {
                        throw new Exception(
                            "Method ".__METHOD__." requires a higher php version"
                        );
                    }
                }

				$fetchStyle = Zend_Db::FETCH_NUM;
			} else {
				$fetchStyle = Zend_Db::FETCH_ASSOC;
			}

			$stmt = $db->prepare($sql);

			foreach ($bindParams as $key => $value) {
				$stmt->bindValue($key, $value);
			}

			$queryResult = $stmt->execute();
			$rows = $stmt->fetchAll($fetchStyle);
			if ($rows === FALSE) {
				throw new Exception("STMT FETCHALL FAILED! RESULT=FALSE");
			}

			$rows = (array)$rows;

			if ($qualifiedColumnNames !== true) {

				return $rows;
			}

			// fullnames


			if (Lib_Utils_Array::isEmpty($rows) === true) {

				return $rows;
			}

			$_rows = (array)Lib_Db_Xdb_Utils_Query::getQualifiedRows(
				$stmt,
				$rows
			);

			return $_rows;

		} catch (Exception $exception) {

			throw $exception;
		}

	}


	/**
	 * @param string $sql
	 * @param array $bindParams
	 * @param bool $qualifiedColumnNames
	 * @return array
	 */
	protected function _fetchAllDebug(
		$sql,
		$bindParams,
		$qualifiedColumnNames = false
	)
	{
		$db = $this->getZendClientFromPool();

		try {

			$fetchStyle = Zend_Db::FETCH_ASSOC;


			if ($qualifiedColumnNames === true) {
				$fetchStyle = Zend_Db::FETCH_NUM;
			} else {
				$fetchStyle = Zend_Db::FETCH_ASSOC;
			}

			$stmt = $db->prepare($sql);
			foreach ($bindParams as $key => $value) {
				$stmt->bindValue($key, $value);
			}
			var_dump(
				array(
				  __METHOD__,
				  $sql,
				  $bindParams,
				  //$stmt
			      )
			);
			$queryResult = $stmt->execute();
			$rows = $stmt->fetchAll($fetchStyle);
			if ($rows === FALSE) {
				throw new Exception("STMT FETCHALL FAILED! RESULT=FALSE");
			}

			$rows = (array)$rows;

			if ($qualifiedColumnNames !== true) {

				return $rows;
			}

			// fullnames


			if (Lib_Utils_Array::isEmpty($rows) === true) {

				return $rows;
			}

			$_rows = (array)Lib_Db_Xdb_Utils_Query::getQualifiedRows(
				$stmt,
				$rows
			);

			return $_rows;

		} catch (Exception $exception) {

			throw $exception;
		}

	}


	/**
	 * @param string $sql
	 * @param array $bindParams
	 * @param bool $qualifiedColumnNames
	 * @param null|int $cursorOrientation
	 * @param null|int $cursorOffset
	 * @return array|null
	 */
	protected function _fetchOne(
		$sql,
		$bindParams,
		$qualifiedColumnNames = false,
		$cursorOrientation = null,
		$cursorOffset = null

	)
	{

		$db = $this->getZendClientFromPool();

		if (is_int($cursorOffset) !== true) {
			$cursorOffset = 0;
		}

		if (is_int($cursorOrientation) !== true) {
			$cursorOrientation = Zend_Db::FETCH_ORI_FIRST;
		}

		try {

			$fetchStyle = Zend_Db::FETCH_ASSOC;

			if ($qualifiedColumnNames === true) {
				//$db->setFetchMode(Zend_Db::FETCH_NUM);
				$fetchStyle = Zend_Db::FETCH_NUM;
			} else {
				$fetchStyle = Zend_Db::FETCH_ASSOC;
				//$db->setFetchMode(Zend_Db::FETCH_ASSOC);
			}

			$stmt = $db->prepare($sql);

			foreach ($bindParams as $key => $value) {
				$stmt->bindValue($key, $value);
			}

			$queryResult = $stmt->execute();

			$row = $stmt->fetch(
				$fetchStyle,
				$cursorOffset,
				$cursorOrientation
			);
			//var_dump($row);
			if ($row === FALSE) {
				//throw new Exception ("STMT FETCH FAILED! result=FALSE");
				// FUCK MAN, DAS IST KEIN FEHLER -
				// AUCH WENNS IN DER DOC SO STEHT

				// eine query mit leerem ergebnis IST KEIN FUCKING FEHLER !
				return null;
			}
			if (is_array($row) !== true) {
				return null;
			}

			if ($qualifiedColumnNames !== true) {
				if (is_array($row)) {
					return $row;
				} else {
					return null;
				}

			}

			// fullnames

			$rows = array($row);

			if (Lib_Utils_Array::isEmpty($rows) === true) {
				return null;
			}

			$_rows = (array)Lib_Db_Xdb_Utils_Query::getQualifiedRows(
				$stmt,
				$rows
			);

			$result = Lib_Utils_Array::getProperty($_rows, 0, true);
			if (is_array($result) !== true) {
				$result = null;
			}

			return $result;

		} catch (Exception $exception) {
			throw $exception;
		}

	}

	/**
	 * @throws Exception
	 * @param  string $table
	 * @param  array $row
	 * @param bool $returnId
	 * @return null|string
	 */
	protected function _insert(
		$table,
		$rowInsert,
		$returnId = false
	)
	{

		try {

			$db = $this->getZendClient();

			Lib_Db_Xdb_Utils_Query::validateTable($table);

			try {
				$rowInsert = Lib_Db_Xdb_Utils_Query::prepareInsertOrUpdateRow(
					$rowInsert
				);
				Lib_Db_Xdb_Utils_Query::validateInsertOrUpdateRow($rowInsert);
			} catch (Exception $exception) {
				$message = "Validate insertRow failed! ";
				$message .= $exception->getMessage();
				throw new Exception($message);
			}

			$affectedRows = (int)$db->insert($table, $rowInsert);
			if (($affectedRows > 0) !== true) {
				$message = "Insert failed! affectedRows = " . $affectedRows;
				throw new Exception($message);
			}

			if ($returnId !== true) {
				return null;
			}

			$insertedId = (int)$db->lastInsertId();
			if ($insertedId > 0) {
				return $insertedId;
			} else {
				return null;
			}

		} catch (Exception $exception) {
			$error = new Lib_Db_Xdb_Exception("Insert failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);
			throw $error;
		}

	}


	/**
	 * @throws Exception
	 * @param  $table
	 * @param  $rowInsert
	 * @param  $rowUpdate
	 * @return bool
	 */
	protected function _insertOrUpdate(
		$table,
		$rowInsert,
		$rowUpdate
	)
	{
		try {

			$db = $this->getZendClient();

			Lib_Db_Xdb_Utils_Query::validateTable($table);

			try {
				$rowInsert = Lib_Db_Xdb_Utils_Query::prepareInsertOrUpdateRow(
					$rowInsert
				);
				Lib_Db_Xdb_Utils_Query::validateInsertOrUpdateRow($rowInsert);
			} catch (Exception $exception) {
				$message = "Validate insertRow failed! ";
				$message .= $exception->getMessage();
				throw new Exception($message);
			}

			try {
				$rowUpdate = Lib_Db_Xdb_Utils_Query::prepareInsertOrUpdateRow(
					$rowUpdate
				);
				Lib_Db_Xdb_Utils_Query::validateInsertOrUpdateRow($rowUpdate);
			} catch (Exception $exception) {
				$message = "Validate updateRow failed! ";
				$message .= $exception->getMessage();
				throw new Exception($message);
			}


			$set = array();
			$update = array();

			$params = array();

			foreach ($rowInsert as $field => $value) {
				$parameterName = ":";
				$parameterName .= "INSERT_" . $field;
				$set[] = " `" . $field . "` = " . $parameterName;

				$params[$parameterName] = $value;
			}

			$update = array();
			foreach ($rowUpdate as $field => $value) {

				$parameterName = ":";
				$parameterName .= "UPDATE_" . $field;
				$update[] = " `" . $field . "` = " . $parameterName;
				$params[$parameterName] = $value;

			}


			$sql = 'INSERT INTO  `' . $table . '`' .
			       ' SET ' . implode(', ', $set) . '' .
			       ' ON DUPLICATE KEY' .
			       ' UPDATE ' .
			       ' ' . implode(', ', $update) . ';';


			$stmt = $db->prepare($sql);

			foreach ($params as $key => $value) {
				$stmt->bindValue($key, $value);
			}


			$result = $stmt->execute();
			$affectedRows = $stmt->rowCount();

			if ($result !== true) {
				$message = "InsertOrUpdate failed! result = " . $result;
				throw new Exception($message);
			}

			return $result;
		} catch (Exception $exception) {
			$error = new Lib_Db_Xdb_Exception("InsertOrUpdate failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);

			throw $error;
		}
	}



    /**
	 * @throws Exception
	 * @param  string $table
	 * @param  array $rowInsert
	 * @param  array $rowUpdate
     * @param  array $increaseFieldsMaps
	 * @return bool
	 */
	protected function _insertOrUpdateAndIncreaseColumnValues(
		$table,
		$rowInsert,
		$rowUpdate,
        $increaseFieldsMaps
	)
	{
		try {

			$db = $this->getZendClient();

			Lib_Db_Xdb_Utils_Query::validateTable($table);

			try {
				$rowInsert = Lib_Db_Xdb_Utils_Query::prepareInsertOrUpdateRow(
					$rowInsert
				);
				Lib_Db_Xdb_Utils_Query::validateInsertOrUpdateRow($rowInsert);
			} catch (Exception $exception) {
				$message = "Validate insertRow failed! ";
				$message .= $exception->getMessage();
				throw new Exception($message);
			}

			try {

                // note: we allow empty $rowUpdate in that case,
                // since we have the increaseFieldsMap for our update statements
                if (
                        ((is_array($rowUpdate))
                         || ($rowUpdate === null))
                        !== true) {
                    throw new Exception(
                        "Parameter rowUpdate must be an array or null"
                    );
                }


                if (Lib_Utils_Array::isEmpty($rowUpdate)) {
                    // we dont have a rowUpdate?
                    // dont care,
                    // since we have a increaseFieldsMaps
                    // for the construction of the update statement
                    $rowUpdate = array();
                } else {
                    // we have a rowUpdate? validate!
                    $rowUpdate =
                            Lib_Db_Xdb_Utils_Query::prepareInsertOrUpdateRow(
                        $rowUpdate
                    );
                    Lib_Db_Xdb_Utils_Query::
                            validateInsertOrUpdateRow($rowUpdate);
                }
                      
			} catch (Exception $exception) {
				$message = "Validate updateRow failed! ";
				$message .= $exception->getMessage();
				throw new Exception($message);
			}

            // validate increaseFieldsMap
            if (Lib_Utils_Array::isEmpty($increaseFieldsMaps)) {
                throw new Exception(
                    "Parameter increaseFieldsMap must be an array"
                    ." and cant be empty at ".__METHOD__
                );
            }


			$set = array();
			$update = array();

			$params = array();

			foreach ($rowInsert as $field => $value) {
				$parameterName = ":";
				$parameterName .= "INSERT_" . $field;
				$set[] = " `" . $field . "` = " . $parameterName;

				$params[$parameterName] = $value;
			}

			$update = array();
			foreach ($rowUpdate as $field => $value) {

				$parameterName = ":";
				$parameterName .= "UPDATE_" . $field;
				$update[] = " `" . $field . "` = " . $parameterName;
				$params[$parameterName] = $value;
			}

            $increase = array();
            foreach ($increaseFieldsMaps as $field => $value) {

                if (Lib_Utils_String::isEmpty($field)) {
                    throw new Exception(
                        "Invalid fieldName"
                        ." must be string in parameter increaseFieldsMap"
                    );
                }
                if ( ( (is_int($value)) || (is_float($value)) ) !== true) {
                    throw new Exception(
                        "Invalid value for field (".$field.")"
                        ." must be int or float in parameter increaseFieldsMap"
                    );
                }
               // e.g.  '`count` = (`count`+1)'. ';';

                $term = null;

                if (is_float($value)) {
                    $term = " `".$field."` = (`".$field."`+".(float)$value.") ";
                }
                if (is_int($value)) {
                    $term = " `".$field."` = (`".$field."`+".(int)$value.") ";
                }

                if (is_string($term) !== true) {
                    // actually this case can't happen ...
                    // ... but just in case ...
                    throw new Exception(
                        "FIXME! Invalid value type for field ".$field
                        ." at parameter increaseFieldsMap"
                    );
                }
                $increase[] = $term;

            }



			$sql = 'INSERT INTO  `' . $table . '`'
                   . ' SET ' . implode(', ', $set) . ''
                   . ' ON DUPLICATE KEY'
                   . ' UPDATE '
                   . ' ' . implode(', ', $update) 
                   . ' ' . implode(', ', $increase)
                   . ';';


			$stmt = $db->prepare($sql);

			foreach ($params as $key => $value) {
				$stmt->bindValue($key, $value);
			}


			$result = $stmt->execute();
			$affectedRows = $stmt->rowCount();

			if ($result !== true) {
				$message = "InsertOrUpdate failed! result = " . $result;
				throw new Exception($message);
			}

			return $result;
		} catch (Exception $exception) {
			$error = new Lib_Db_Xdb_Exception("InsertOrUpdate failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);

			throw $error;
		}
	}





	/**
	 * @throws Exception
	 * @param  $table
	 * @param  $rowUpdate
	 * @param  $where
	 * @param null $params
	 * @return int
	 */
	protected function _update(
		$table,
		$rowUpdate,
		$where,
		$params = null
	)
	{
		try {

			$db = $this->getZendClient();

			Lib_Db_Xdb_Utils_Query::validateTable($table);

			$rowUpdate = Lib_Db_Xdb_Utils_Query::prepareInsertOrUpdateRow(
				$rowUpdate
			);

			try {
				Lib_Db_Xdb_Utils_Query::validateInsertOrUpdateRow($rowUpdate);
			} catch (Exception $exception) {
				$message = "Validate updateRow failed! ";
				$message .= $exception->getMessage();
				throw new Exception($message);
			}

			if (is_string($where) !== true) {
				$message = "Parameter where must be a string! ";
				throw new Exception($message);
			}


			$set = array();
			$bindParams = array();

			foreach ($rowUpdate as $field => $value) {
				$parameterName = ":";
				$parameterName .= "UPDATE_" . $field;
				$set[] = " `" . $field . "` = " . $parameterName;

				$bindParams[$parameterName] = $value;
			}

			$params = (array)$params;
			foreach ($params as $field => $value) {
				$parameterName = ":";
				$parameterName .= "" . $field;
				$bindParams[$parameterName] = $value;
			}

			$sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $set);
			if (strlen($where) > 0) {
				$sql .= ' WHERE ' . $where;

				try {
					Lib_Db_Xdb_Utils_Query::validateParamsMissing(
						$where,
						$params
					);
				} catch (Exception $exception) {
					$message = "Error in where clause!";
					$message .= " " . $exception->getMessage();
					throw new Exception($message);
				}
			}


			$stmt = $db->prepare($sql);

			foreach ($bindParams as $key => $value) {
				$stmt->bindValue($key, $value);
			}

			$affectedRows = 0;
			$stmt->execute();
			$affectedRows = $stmt->rowCount();

			return $affectedRows;
		} catch (Exception $exception) {
			$error = new Lib_Db_Xdb_Exception("Update failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);

			throw $error;
		}
	}


	/**
	 * @throws Exception
	 * @param  $table
	 * @param  $where
	 * @param null $params
	 * @return int
	 */
	protected function _delete(
		$table,
		$where,
		$params = null
	)
	{
		try {

			$db = $this->getZendClient();

			Lib_Db_Xdb_Utils_Query::validateTable($table);

			if (Lib_Utils_String::isEmpty($where)) {
				$message = "Parameter where must be a string and cant be empty! ";
				$message .= " where=" . $where;
				$message .= " at " . __METHOD__;
				throw new Exception($message);
			}


			$bindParams = array();
			$params = (array)$params;
			foreach ($params as $field => $value) {
				$parameterName = ":";
				$parameterName .= "" . $field;
				$bindParams[$parameterName] = $value;
			}

			$sql = 'DELETE FROM `' . $table . '` WHERE ' . $where;

			try {
				Lib_Db_Xdb_Utils_Query::validateParamsMissing(
					$where,
					$params
				);
			} catch (Exception $exception) {
				$message = "Error in where clause!";
				$message .= " " . $exception->getMessage();
				throw new Exception($message);
			}


			$stmt = $db->prepare($sql);

			foreach ($bindParams as $key => $value) {
				$stmt->bindValue($key, $value);
			}

			$affectedRows = 0;
			$stmt->execute();
			$affectedRows = $stmt->rowCount();

			return $affectedRows;
		} catch (Exception $exception) {
			$error = new Lib_Db_Xdb_Exception("Delete failed!");
			$error->setMethod(__METHOD__);
			$methodArgs = (array)func_get_args();
			$error->setFault(
				array(
				     "method" => __METHOD__,
				     "methodArgs" => $methodArgs,
				     "previousErrorMessage" => $exception->getMessage(),
				)
			);

			throw $error;
		}
	}


	/**
	 * @static
	 * @throws Exception
	 * @param  string $text
	 * @param  mixed $value
	 * @return mixed|string
	 */
	public function quoteInto(
		$text,
		$value
	)
	{

		if (is_string($text) !== true) {
			$message = "Parameter text must be a string! at " . __METHOD__;
			throw new Exception($message);
		}


		if ($value === null) {
			return str_replace('?', 'NULL', $text);
		}
		if (is_bool($value)) {
			if ($value === true) {
				return str_replace('?', 'TRUE', $text);
			} else {
				return str_replace('?', 'FALSE', $text);
			}
		}


		if (is_int($value)) {
			return $this->getZendClientFromPool()->quoteInto($text, $value);
		}
		if (is_float($value)) {
			return $this->getZendClientFromPool()->quoteInto($text, $value);
		}
		if (is_string($value)) {
			return $this->getZendClientFromPool()->quoteInto($text, $value);
		}

		$message = "Invalid type to be quotedInto";
		$message .= " for value=" . $value . " at " . __METHOD__;
		$message .= " text=" . $text;
		throw new Exception($message);

	}


	/**
	 * @static
	 * @throws Exception
	 * @param  string $text
	 * @param bool $auto
	 * @return string
	 */
	public function quoteIdentifier(
		$text,
		$auto = false
	)
	{

		return $this->getZendClientFromPool()->quoteIdentifier($text, $auto);
	}

	/**
	 * Quotes a value and places into a piece of text at a placeholder.
	 *
	 * The placeholder is a question-mark; all placeholders will be replaced
	 * with the quoted value.   For example:
	 *
	 * <code>
	 * $text = "WHERE date < ?";
	 * $date = "2005-01-02";
	 * $safe = $sql->quoteInto($text, $date);
	 * // $safe = "WHERE date < '2005-01-02'"
	 * </code>
	 *
	 * @param string  $text  The text with a placeholder.
	 * @param mixed   $value The value to quote.
	 * @param string  $type  OPTIONAL SQL datatype
	 * @param integer $count OPTIONAL count of placeholders to replace
	 * @return string An SQL-safe quoted value placed into the original text.
	 */
	public function quoteIntoZend($text, $value, $type = null, $count = null)
	{
		$db = $this->getZendClientFromPool();
		return $db->quoteInto($text, $value, $type, $count);
	}


	/**
	 * NOTE: ZEND DOES NOT SUPPORT NAMED PARAMETERS IN WHERE CLAUSE
	 *
	 * TRY TO AVOID USING THAT METHOD !
	 *
	 * each positional parameter in where clause must be separately
	 * quotedInto ... e.g.:
	 *		 $where = $db->quoteInto("id = ?","MYID");
	 *		$where .= $db->quoteInto(" OR name = ?","MYNAME");
	 *		$where .= " LIMIT 1;";
	 * @throws Exception
	 * @param  string $table
	 * @param  array $row
	 * @param  string $where
	 * @param  array|null $params   (not supported yet)
	 * @return int
	 */
	public function updateZend($table, $row, $where, $params = null)
	{
		$db = $this->getZendClient();

		if (Lib_Utils_String::isEmpty($table)) {
			$message = "Parameter table must be a string and cant be empty!";
			$message .= " table=" . $table;
			throw new Exception($message);
		}
		if (Lib_Utils_Array::isEmpty($row)) {
			$message = "Parameter row must be an array and cant be empty!";
			$message .= " row=" . $row;
			throw new Exception($message);
		}
		if (((is_array($params)) || ($params === null)) !== true) {
			$message = "Parameter params must be an array or null!";
			$message .= " params=" . $params;
			throw new Exception($message);
		}
		if (Lib_Utils_String::isEmpty($where)) {
			$message = "Parameter where must be a string and cant be empty!";
			$message .= " where=" . $where;
			throw new Exception($message);
		}

		$result = $db->update($table, $row, $where);

		return $result;
	}

}
