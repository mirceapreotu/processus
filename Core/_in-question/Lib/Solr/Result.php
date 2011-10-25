<?php
/**
 * Lib_Solr_Result
 *
 * @category	meetidaaa.com
 * @package        Lib_Solr
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

/**
 * Lib_Solr_Result
 *
 * @category	meetidaaa.com
 * @package        Lib_Solr
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */
class Lib_Solr_Result implements ArrayAccess, Iterator, Countable
{

    /**
     * @var array
     */
    private $_result = array();

    /**
     * @var object
     */
    public function __construct($data)
    {
        $this->_result = $data;
    }

    /**
     * @throws Exception
     * @param int $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {

        throw new Exception("Lib_Solr_Result class is read-only!");
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_result->response->docs[$offset]);
    }

    /**
     * @param int $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new Exception("Lib_Solr_Result class is read-only!");
    }

    /**
     * @param int $offset
     * @return array|null
     */
    public function offsetGet($offset)
    {
        return
            isset(
                $this->_result->response->docs[$offset]
            )
            ? $this->_result->response->docs[$offset]
            : null;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->_result->response->docs);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->_result->response->docs);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->_result->response->docs);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->_result->response->docs);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * @return int|void
     */
    public function count()
    {
        return count($this->_result->response->docs);
    }

    /**
     * getLastResultCount
     *
     * @return int
     */
    public function countFound()
    {

        return (int)@$this->_result->response->numFound;
    }

    /**
     * @return array[Apache_Solr_Document]
     */
    public function getDocuments()
    {
        return $this->_result->response->docs;
    }

    public function getDocumentIds()
    {
        $data = array();
        foreach ($this->_result->response->docs as $doc) {
            $data[] = $doc->id;
        }
        return $data;
    }

    /**
     * @return
     */
    public function getResponse()
    {
        return $this->_result->response;
    }
}
