<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 8/15/11
 * Time: 12:02 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Core_Abstracts_AbstractModel extends Core_Abstracts_AbstractManager
{
    protected $_data = array();

    /**
     * @param $item
     * @return App_GaintS_Core_AbstractModel
     */
    public function addItem($item)
    {
        $_data[] = $item;
        return $this;
    }

    /**
     * @param $id
     * @return App_GaintS_Core_AbstractModel
     */
    public function removeItemById($id)
    {
        unset($this->_data[$id]);
        return $this;
    }

    /**
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->_data;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetchOneById($id)
    {
        return $this->_data[$id];
    }
}
