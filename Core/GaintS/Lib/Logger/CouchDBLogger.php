<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 8/22/11
 * Time: 1:38 PM
 * To change this template use File | Settings | File Templates.
 */

class App_GaintS_Lib_Logger_CouchDBLogger extends App_GaintS_Lib_Logger_AbstractLogger
{

    /**
     * @var array|\stdClass
     */
    private $_couchDoc;

    /**
     * @var \Couch_Client
     */
    private $_couchDBClient;

    public function __construct()
    {
        $this->_couchDoc = new stdClass();
        $this->_couchDoc->created = time();
    }

    /**
     * @param string $logType
     * @return App_GaintS_Lib_Logger_CouchDBLogger
     */
    public function setLogType($logType = "default")
    {
        $this->_couchDoc->type = $logType;
        return $this;
    }

    /**
     * @param $logData
     * @return App_GaintS_Lib_Logger_CouchDBLogger
     */
    public function setData($logData)
    {
        $this->_couchDoc->data = $logData;
        return $this;
    }

    /**
     * @param $exceptionData
     * @return App_GaintS_Lib_Logger_CouchDBLogger
     */
    public function setException($exceptionData)
    {
        $this->_couchDoc->exception = $exceptionData;
        return $this;
    }

    /**
     * @return App_GaintS_Lib_Logger_CouchDBLogger
     */
    public function writeLog()
    {
        $serverData = array();
        foreach ($_SERVER as $key => $serverItem)
        {
            if ($key == "REMOTE_ADDR") {
                $serverItem = md5($serverItem);
            }
            $serverData[$key] = $serverItem;
        }

        $this->_couchDoc->serverData = $serverData;
        $response = $this->getCouch()->storeDoc($this->_couchDoc);
        return $response;
    }

    /**
     * @return Couch_Client
     */
    protected function getCouch()
    {
        if (!$this->_couchDBClient) {
            $this->_couchDBClient = new Couch_Client('localhost:5984', 'logging');
        }

        return $this->_couchDBClient;
    }
}
