<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/26/11
 * Time: 10:56 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Model_Mvo_VenuesMvo extends App_GaintS_Core_AbstractMVO
{
    /**
     * @return string

    protected function getDataBucketPort()
    {
        return "11280";
    }
     */
    
    /**
     * @param $memId
     * @return App_Model_Mvo_VenuesMvo
     */
    public function setMemId($memId)
    {
        $this->_memId = "App_Model_Mvo_VenuesMvo_" . md5($memId);
        return $this;
    }

    /**
     * @return int
     */
    public function getVenueId()
    {
        return (int)$this->getValueByKey("id");
    }

    /**
     * @return int
     */
    public function getRaId()
    {
        return (int)$this->getValueByKey("ra_id");
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return (int)$this->getValueByKey("city_id");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->getValueByKey("name");
    }

    /**
     * @return string
     */
    public function getUrlname()
    {
        return (string)$this->getValueByKey("urlname");
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return (string)$this->getValueByKey("address");
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return (float)$this->getValueByKey("latitude");
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return (float)$this->getValueByKey("longitude");
    }

    /**
     * @return string
     */
    public function getUrlWebsite()
    {
        return (string)$this->getValueByKey("url_website");
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return (boolean)$this->getValueByKey("status");
    }

    /**
     * @return array
     */
    public function getCreated()
    {
        return $this->getValueByKey("created");
    }
}
