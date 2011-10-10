<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Model_Mvo_DjMvo extends App_GaintS_Vo_Membase_UserMVO
{
    /**
     * @return string

    protected function getDataBucketPort()
    {
        return "11280";
    }
     */

    /**
     * @return int
     */
    public function getDjId()
    {
        return (int)$this->getValueByKey('id');
    }

    /**
     * @return string
     */
    public function getDjName()
    {
        return (string)$this->getValueByKey('name');
    }

    /**
     * @return string
     */
    public function getUrlName()
    {
        return (string)$this->getValueByKey('urlname');
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return (string)$this->getValueByKey('real_name');
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return (string)$this->getValueByKey('country');
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return (string)$this->getValueByKey('bio');
    }

    /**
     * @return string
     */
    public function getUrlBioSource()
    {
        return (string)$this->getValueByKey('url_bio_source');
    }

    /**
     * @return string
     */
    public function getUrlSoundcloudUrl()
    {
        return (string)$this->getValueByKey('url_soundcloud');
    }

    /**
     * @return string
     */
    public function getUrlFacebook()
    {
        return (string)$this->getValueByKey('url_facebook');
    }

    /**
     * @return string
     */
    public function getUrlMySpace()
    {
        return (string)$this->getValueByKey('url_myspace');
    }

    /**
     * @return string
     */
    public function getDataSource()
    {
        return (string)$this->getValueByKey('data_source');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return (string)$this->getValueByKey('status');
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return (string)$this->getValueByKey('created');
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return (string)$this->getValueByKey('updated');
    }
}
