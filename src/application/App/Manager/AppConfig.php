<?php
/**
 * App_Manager_AppConfig Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_AppConfig
 *
 * @category	meetidaaa.com
 * @package		App_Manager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_AppConfig extends App_Manager_AbstractManager
{



    


    /**
     * @var App_Manager_AppConfig
     */
    private static $_instance;

    /**
     * @static
     * @return App_Manager_AppConfig
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    /**
     *
     */
    public function __construct()
    {
       // $this->loadData();
    }

    













    /**
     * @return array
     */
    public function toArray()
    {
        $result = array(
            // framework
            //"newsItemsCount" => $this->getNewsItemsCount(),
        );
        return $result;
    }


    /**
     * @return App_Model_Dto_CampaignInfo
     */
    /*
    public function toDto()
    {
        $dto = new App_Model_Dto_CampaignInfo();
        // framework
        $dto->setIsRunning($this->isRunning());
        $dto->setIsStarted($this->isStarted());
        $dto->setIsFinished($this->isFinished());
        $dto->setStartDate($this->getStartDate());
        $dto->setEndDate($this->getEndDate());
        $dto->setRealDate($this->getRealDate());
        $dto->setCurrentDate($this->getCurrentDate());
        $dto->setCurrentCampaignDay($this->getCurrentCampaignDay());
        $dto->setCampaignMaxDay($this->getCampaignMaxDay());
        // app specific
        $dto->setEntertainingEggValue($this->getEntertainingEggValue());
        $dto->setEntertainingEggShowTeaser(
            $this->getEntertainingEggShowTeaser()
        );

        $dto->unsetPropertiesEmpty();
        return $dto;
    }
    */



    /**
     * @throws Exception
     * @param  string $key
     * @return mixed|null
     */
    public function loadValue($key)
    {
        $dbClient = $this->getDbClient();

        if (Lib_Utils_String::isEmpty($key)) {
            throw new Exception("Invalid parameter 'key' at ".__METHOD__);
        }

        $key = (string)$key;
        $sql = "SELECT * FROM AppConfig WHERE AppConfig.key=:key";


        $params = array(
            "key" => (string)$key,
        );

     

        $row = $dbClient->getRow($sql, $params, false);

        $value = Lib_Utils_Array::getProperty($row, "value", true);
        if (Lib_Utils_String::isEmpty($value)!==true) {
            $value = json_decode($value, true);
        } else {
            $value = null;
        }

        return $value;
    }

    /**
     * @throws Exception
     * @param  string $key
     * @param  mixed $value
     * @return
     */
    public function saveValue($key, $value)
    {

        $dbClient = $this->getDbClient();


        $valueJSON = json_encode($value);
        if (is_string($valueJSON)!==true) {
            throw new Exception("JsonEncode(value) failed! at ".__METHOD__);
        }

        $rowInsert = array(
            "key" => (string)$key,
            "value"=>(string)$valueJSON,
        );
        $rowUpdate = array(
            "value" => (string)$valueJSON,
        );

        $dbClient->insertOrUpdate("AppConfig", $rowInsert, $rowUpdate);

    }



    /**
     * @return array
     */
    public function load()
    {
        $dbClient = $this->getDbClient();

        $sql = "SELECT * FROM AppConfig WHERE 1";
        $params = array(

        );
        $rows = $dbClient->getRows($sql, $params, false);


        $result = array();

        foreach($rows as $row) {

            $key = Lib_Utils_Array::getProperty($row, "key", true);
            if (Lib_Utils_String::isEmpty($key)) {
                continue;
            }
            $value = Lib_Utils_Array::getProperty($row, "value", true);
            if (Lib_Utils_String::isEmpty($value)!==true) {
                $value = json_decode($value, true);
            } else {
                $value = null;
            }

            $result[$key] = $value;
        }


        return $result;
    }




}
