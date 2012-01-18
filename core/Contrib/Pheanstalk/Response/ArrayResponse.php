<?php

/**
 * A response with an ArrayObject interface to key=>value data
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Response;
class ArrayResponse extends \ArrayObject implements \Pheanstalk\Response
{
    private $_name;

    /**
     * @param string $name
     * @param array  $data
     */
    public function __construct($name, $data)
    {
        $this->_name = $name;
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getResponseName()
    {
        return $this->_name;
    }

    /**
     * @param $property
     *
     * @return null
     */
    public function __get($property)
    {
        $key = $this->_transformPropertyName($property);
        return isset($this[$key]) ? $this[$key] : null;
    }

    /**
     * @param $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        $key = $this->_transformPropertyName($property);
        return isset($this[$key]);
    }

    // ----------------------------------------

    /**
     * Transform underscored property name to hyphenated array key.
     *
     * @param string
     *
     * @return string
     */
    private function _transformPropertyName($propertyName)
    {
        return str_replace('_', '-', $propertyName);
    }
}
