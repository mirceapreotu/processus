<?php
/**
 * Lib_Db_Xdb_Utils_FieldAlias
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
 * Lib_Db_Xdb_Utils_FieldAlias
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
 
class Lib_Db_Xdb_Utils_FieldAlias
{

    const TABLE_PREFIX = "XDBTABLE";


    /**
     * @static
     * @param  $table
     * @return string
     */
    public static function getPrefix($table)
    {
        $prefix = "";
        if (is_string($table)!==true) {
            $table = "";
        }
        $table = trim($table);
        if (Lib_Utils_String::isEmpty($table) !== true) {
            $prefix = self::TABLE_PREFIX."_".$table."_";
        }

        $prefix = trim($prefix);
        if (Lib_Utils_String::isEmpty($prefix) === true) {
                return "";
        }
        return $prefix;
    }


    /**
     * @static
     * @param  string $table
     * @param null|string $prefix
     * @param  array $fields
     * @return string
     */
	public static function getSqlForSelect(
        $table,
        $fields
    )
    {

        if (is_array($fields)!==true) {
            $fields = array();
        }
        if (is_string($table)!==true) {
            $table = "";
        }
        $table = trim($table);
        $prefix = self::getPrefix($table);
        
        $sqlTerms = array();
        foreach($fields as $field) {

            $field = trim($field);
            if (Lib_Utils_String::isEmpty($field)) {
                continue;
            }

            $sqlTerm = "";
            if (Lib_Utils_String::isEmpty($table) !== true) {
                $sqlTerm .= "`".$table."`.";
            }

            $sqlTerm .= "`".$field."`";

            if (Lib_Utils_String::isEmpty($prefix) !== true) {
                $sqlTerm .= " as ".$prefix.$field."";
            }

            $sqlTerms[] = $sqlTerm;
        }

        $sql = implode(" , ",$sqlTerms);


        return $sql;
    }

    /**
     * @static
     * @throws Exception
     * @param  array $recordList
     * @return string
     */
    public static function getSqlForSelectByRecordList(
        $recordList
    ) {

        if (is_array($recordList)!==true) {
            $recordList = array();
        }


        $sqlTerms = array();

        foreach($recordList as $record) {
            /**
             * @var App_Model_Record_Abstract $record
             */
            if (($record instanceof App_Model_Record_Abstract) !== true) {
                throw new Exception(
                    "Invalid item in recordList at ".__METHOD__
                );
            }

            $table = $record->getDbTable();
            $fields = $record->getDbFields();

            $sqlTerm = self::getSqlForSelect($table, $fields);
            $sqlTerms[] = $sqlTerm;
        }


        $sql = implode(" , " ,$sqlTerms);

        return $sql;
    }





    /**
     * @static
     * @throws Exception
     * @param  string $table
     * @param null|array $fields
     * @param  array|null $row
     * @return array
     */
    public static function collectPropertiesFromRow(
        $table,
        $fields = null,
        $row
    )
    {
        $result = array();
        if (is_array($row) !== true) {
            return $result;
        }

        if (( ($fields === null) || (is_array($fields)) )!==true) {
            throw new Exception(
                "Parameter fields must be array/null at ".__METHOD__
            );
        }

        $prefix = self::getPrefix($table);

        if (is_array($fields)) {
            foreach($fields as $field) {
                $field = trim($field);
                if (Lib_Utils_String::isEmpty($field)) {
                    continue;
                }

                $prefixedField = $prefix.$field;
                $value = Lib_Utils_Array::getProperty($row, $prefixedField);
                $result[$field] = $value;
            }
            return $result;
        }

        // no fields given, collect everything starting with
        foreach ($row as $prefixedField => $value) {
            if (Lib_Utils_String::isEmpty($prefixedField)) {
                continue;
            }

            $ignoreCase = false;
            if (Lib_Utils_String::startsWith(
                $prefixedField,
                $prefix,
                $ignoreCase
            ) !== true) {
                continue;
            }
            $field = Lib_Utils_String::removePrefix(
                $prefixedField,
                $prefix,
                $ignoreCase
            );
            $result[$field] = $value;
        }
        return $result;


    }




    /**
     * @static
     * @throws Exception
     * @param  string $table
     * @param string $field
     * @param  array|null $row
     * @return array
     */
    public static function getPropertyFromRow(
        $table,
        $field,
        $row
    )
    {
        $result = null;
        if (Lib_Utils_String::isEmpty($field)) {
            return $result;
        }

        $prefix = self::getPrefix($table);
        $prefixedField = $prefix.$field;

        $value = Lib_Utils_Array::getProperty($row, $prefixedField, true );
        return $value;

    }



    /**
     * @static
     * @param  array|null $row
     * @return array
     */
    public static function getQualifiedRowByPrefixedRow($row)
    {
        $result = array();
        if (is_array($row) !== true) {
            return $result;
        }

        foreach($row as $prefixedField => $value) {

            $fieldQName = $prefixedField;

            $parts = (array)explode("_", $fieldQName);
            if (count($parts)>=3) {
                $prefix = array_shift($parts);
                if ($prefix === self::TABLE_PREFIX) {

                    $table = array_shift($parts);

                    $field = implode("_", $parts);

                    $fieldQName = "".$table.".".$field;

                }
            }

            $result[$fieldQName] = $value;

        }


        return $result;
    }



}
