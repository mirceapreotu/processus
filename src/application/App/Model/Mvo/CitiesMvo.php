<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:42 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Model_Mvo_CitiesMvo extends App_GaintS_Core_AbstractMVO
{
    /**
     * @param $memId
     * @return void
     */
    public function setMemId($memId)
    {
        $this->_memId = "App_Model_Mvo_CitiesMvo_" . $memId;
    }

    /**
     * @return string

    protected function getDataBucketPort()
    {
        return "11280";
    }
     */
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_data["name"];
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->_data['id'];
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->_data['currency'];
    }

    /**
     * @return int
     */
    public function getRaId()
    {
        return $this->_data['ra_id'];
    }

    /**
     * @return bool
     */
    public function isLive()
    {
        return (boolean)$this->_data['live'];
    }
}
