<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/13/11
 * Time: 3:24 AM
 * To change this template use File | Settings | File Templates.
 */

class Core_GaintS_Vo_Core_GetDataConfigVo extends Core_GaintS_Core_AbstractVO
{
    /**
     * @var array
     */
    protected $_data = array(
        "fromCache" => true,
        "sqlParams" => array(),
        "expiredTime" => "low"
    );

    /**
     * @param $memKey
     * @return App_GaintS_Vo_GetDataConfigVo
     */
    public function setMemKey($memKey)
    {
        $this->_data['memKey'] = $memKey . md5($memKey . $this->getSQLStmt() . implode(",", $this->getSQLParamData()). $this->getExpiredTime() . $this->getFromCache());;
        return $this;
    }

    /**
     * @return string
     */
    public function getMemKey()
    {
        if (!$this->_data['memKey']) {
            $this->_data['memKey'] = md5($this->getSQLStmt() . (string)$this->getSQLParamData() . $this->getExpiredTime() . $this->getFromCache());
        }
        return $this->_data['memKey'];
    }

    /**
     * @param $sql
     * @return App_GaintS_Vo_GetDataConfigVo
     */
    public function setSQLStmt($sql)
    {
        $this->_data['sqlStmt'] = $sql;
        return $this;
    }

    /**
     * @return string
     */
    public function getSQLStmt()
    {
        if (!$this->_data['sqlStmt'])
        {
            throw new Exception("SQL Statement is empty");
        }
        return $this->_data['sqlStmt'];
    }

    /**
     * @param $value
     * @return App_GaintS_Vo_GetDataConfigVo
     */
    public function setSQLParamData($value)
    {
        $this->_data['sqlParams'] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSQLParamData()
    {
        return $this->_data['sqlParams'];
    }

    /**
     * @param $fromCache boolean
     * @return App_GaintS_Vo_GetDataConfigVo
     */
    public function setFromCache($fromCache)
    {
        $this->_data['fromCache'] = $fromCache;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getFromCache()
    {
        return $this->_data['fromCache'];
    }

    /**
     * @param string $value
     * @return App_GaintS_Vo_GetDataConfigVo
     */
    public function setExpiredTime($value = "low")
    {
        $this->_data['expiredTime'] = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpiredTime()
    {
        return (int)$this->getExpiredTimeInNumber($this->_data['expiredTime']);
    }

    /**
     * @param $prio
     * @return int
     */
    protected function getExpiredTimeInNumber($prio)
    {
        return 100;//(int)Bootstrap::getRegistry()->getGaintSConfig()->getMembaseConfig()->getTimeByPrio($prio);
    }
}
