<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 7/8/11
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class Core_Abstracts_AbstractVO extends Core_Abstracts_AbstractClass
{

    /** @var object */
    protected $_data = array();

    /**
     * @param $data
     * @return App_GaintS_Core_AbstractVO
     */
    public function setData($data)
    {
        /** @var $_data object */
        $this->_data = (array)$data;
        return $this;
    }

    /**
     * @param $key string
     * @param $value mixed
     * @return App_GaintS_Core_AbstractVO
     */
    public function setValueByKey($key, $value)
    {
        $this->_data->$key = $value;
        return $this;
    }

    /**
     * @param $key
     * @return mixed | object
     */
    public function getValueByKey($key)
    {
        return $this->_data[$key];
    }

    /**
     * @return object
     */
    public function getData()
    {
        if (is_null($this->_data)) {
            throw new Exception("data is null");
        }
        return $this->_data;
    }

}
