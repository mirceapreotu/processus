<?php
/**
 * Lib_Db_Xdb_Pager_PrevNext
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Db_Xdb_Pager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Db_Xdb_Pager_PrevNext
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Db_Xdb_Pager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Db_Xdb_Pager_PrevNext
{



    protected $_limitStart;
    protected $_limitPageSize;
    protected $_limitMaxPageSize;
    protected $_hasPrevPage;
    protected $_hasNextPage;

    protected $_items;

    protected $_fetchNextRow;
    protected $_fetchPrevRow;


    /**
     * @throws Exception
     * @param  int|null $limitStart
     * @param  int|null $limitPageSize
     * @param  int $limitMaxPageSize
     * @return void
     */
    public function setLimit($limitStart, $limitPageSize, $limitMaxPageSize)
    {
        $limitStart = (int)$limitStart;
        if ($limitStart<0) {
            $limitStart = 0;
        }

        if ( (is_int($limitMaxPageSize)) && (($limitMaxPageSize>0)!==true) ) {
            throw new Exception(
                "Parameter limitMaxPageSize must be int>0! at ".__METHOD__
            );
        }

        $limitMaxPageSize = (int)$limitMaxPageSize;
        if ($limitMaxPageSize<1) {
            $limitMaxPageSize = 1;
        }

        if ($limitPageSize === null) {
            $limitPageSize = $limitMaxPageSize;
        } else {
            $limitPageSize = (int)$limitPageSize;
        }
        if ($limitPageSize>$limitMaxPageSize) {
            $limitPageSize = $limitMaxPageSize;
        }

        $this->_limitStart = $limitStart;
        $this->_limitPageSize = $limitPageSize;
        $this->_limitMaxPageSize = $limitMaxPageSize;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getSql()
    {
        $result = null;
        $this->_hasNextPage = null;
        $this->_hasPrevPage = null;
        $this->_fetchNextRow = false;
        $this->_fetchPrevRow = false;

        $this->_items = array();

        $limitStart = $this->_limitStart;
        if (is_int($limitStart)!==true) {
            throw new Exception("Invalid Limit bounds(start) at ".__METHOD__);
        }

        $limitPageSize = $this->_limitPageSize;
        if (is_int($limitPageSize)!==true) {
            throw new Exception(
                "Invalid Limit bounds(PageSize) at ".__METHOD__
            );
        }



        if ($limitStart === 0) {

            // recordSet & folgerecord holen
            $sql = "0,".($limitPageSize +1);
            $this->_fetchNextRow = true;
            $this->_fetchPrevRow = false;
            return $sql;

        }

        // recordSet & prevrecord & nextrecord holen
        $sql = "".($limitStart-1).",".($limitPageSize+2);
        $this->_fetchNextRow = true;
        $this->_fetchPrevRow = true;

        return $sql;

    }


    /**
     * @throws Exception
     * @return array
     */
    public function getRealLimitsForQuery()
    {
        $result = array(
            "limitStart" => null,
            "limitPageSize" => null,
        );
        $this->_hasNextPage = null;
        $this->_hasPrevPage = null;
        $this->_fetchNextRow = false;
        $this->_fetchPrevRow = false;

        $this->_items = array();

        $limitStart = $this->_limitStart;
        if (is_int($limitStart)!==true) {
            throw new Exception(
                "Invalid Limit bounds(start) at ".__METHOD__
            );
        }

        $limitPageSize = $this->_limitPageSize;
        if (is_int($limitPageSize)!==true) {
            throw new Exception(
                "Invalid Limit bounds(PageSize) at ".__METHOD__
            );
        }



        if ($limitStart === 0) {

            // recordSet & folgerecord holen
            //$sql = "0,".($limitPageSize +1);

            $result["limitStart"] = 0;
            $result["limitPageSize"] = ($limitPageSize +1);

            $this->_fetchNextRow = true;
            $this->_fetchPrevRow = false;
            //return $sql;
            return $result;

        }

        // recordSet & prevrecord & nextrecord holen
        //$sql = "".($limitStart-1).",".($limitPageSize+2);

        $result["limitStart"] = ($limitStart-1);
        $result["limitPageSize"] = ($limitPageSize+2);

        $this->_fetchNextRow = true;
        $this->_fetchPrevRow = true;

        return $result;
    }




    /**
     * @throws Exception
     * @param  array $rows
     * @return array
     */
    public function setResultRows($rows)
    {
        //$this->setDebugProperty(__METHOD__, "fetchPrev", $this->_fetchPrevRow);
        //$this->setDebugProperty(__METHOD__, "fetchNext", $this->_fetchNextRow);


        $this->_items = null;
        if (is_array($rows)!==true) {
            throw new Exception(
                "Parameter rows must be an array! at ".__METHOD__
            );
        }
        $limitStart = $this->_limitStart;
        if (is_int($limitStart)!==true) {
            throw new Exception(
                "Invalid Limit bounds(start) at ".__METHOD__
            );
        }
        $limitPageSize = $this->_limitPageSize;
        if (is_int($limitPageSize)!==true) {
            throw new Exception(
                "Invalid Limit bounds(pageSize) at ".__METHOD__
            );
        }

        $fetchNextRow = $this->_fetchNextRow;
        $fetchPrevRow = $this->_fetchPrevRow;

        if (count($rows)===0) {
            $this->_items = $rows;
            $this->_hasPrevPage = false;
            $this->_hasNextPage = false;
            return $this->getItems();
        }


        if ($limitStart>0) {
            $this->_hasPrevPage = true;
        }
        if (($fetchNextRow!==true) && ($fetchPrevRow!==true)) {

            $index = -1;
            $maxIndex = $limitPageSize-1;
            $_rows = array();
            foreach ($rows as $row) {
                $index++;
                if ($index <=$maxIndex) {
                    $_rows[] = $row;
                }
            }

            $this->_items = $_rows;
            $this->_hasPrevPage = ($this->_limitStart>0);
            $this->_hasNextPage = ((count($rows)>(count($_rows))));
            return $this->getItems();
        }

        if (count($rows)<=$limitPageSize) {
            $this->_hasPrevPage = false;
            $this->_hasNextPage = false;
        }

        if ($fetchPrevRow === true) {
            // drop that first row

            if (count($rows)>0) {
                $this->_hasPrevPage = true;
            }

            $_rows = array();
            $index = -1;
            foreach ($rows as $row) {
                $index++;
                if ($index===0) {
                    continue;
                }
                $_rows[] = $row;
            }
            $rows=$_rows;
        } else {
            $this->_hasPrevPage = false;
        }


        $_rows = array();
        $index = -1;
        $maxIndex = $limitPageSize-1;
        foreach ($rows as $row) {
            $index++;
            if ($index<=$maxIndex) {
                $_rows[] = $row;
            }
        }
        $this->_hasNextPage = (count($rows)>count($_rows));
        $rows=$_rows;

        $this->_items = $rows;
        return $this->getItems();
    }


    public function setItems($items)
    {
        $this->_items = (array)$items;
    }



    /**
     * @return array
     */
    public function getItems()
    {
        return (array)$this->_items;
    }



    /**
     * @return array
     */

    public function export()
    {
        return $this->toArray();
    }


    /**
     * @var array
     */
    protected $_debugProperties;

    /**
     * @param  $name
     * @param  $value
     * @return void
     */
    public function setDebugProperty($method, $name, $value)
    {
        if (is_array($this->_debugProperties)!==true) {
            $this->_debugProperties = array();
        }
        if (strpos($method, "::")===FALSE) {
            throw new Exception(
                "Parameter 'method' must be a class method name at ".__METHOD__
            );
        }
        $key = "".$method."_".$name;
        $this->_debugProperties[$key] = $value;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $result = array(
            "items" => $this->getItems(),
            "start" => $this->_limitStart,
            "pageSize" => $this->_limitPageSize,
            "maxPageSize" => $this->_limitMaxPageSize,
            "hasPrevPage" => $this->_hasPrevPage,
            "hasNextPage" => $this->_hasNextPage,

        );
        if (Lib_Utils_Array::isEmpty($this->_debugProperties)!==true) {
            $result["__debug"] = $this->_debugProperties;
        }
        return $result;
    }










}
