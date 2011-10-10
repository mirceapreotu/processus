<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_ImportCities extends App_GaintS_Core_AbstractTask
{

    public function run()
    {
        $dbClient = App_Facebook_Application::getInstance()->getDbClient();

        $sql  = "SELECT * FROM base_cities";

        $datas = $dbClient->getRows($sql);

        foreach($datas as $item)
        {
            $mvo = new App_Model_Mvo_CitiesMvo();
            $mvo->setMemId($item["name"]);
            $mvo->setData($item);
            $mvo->saveInMem();

            $mvo = new App_Model_Mvo_CitiesMvo();
            $mvo->setMemId($item["id"]);
            $mvo->setData($item);
            $mvo->saveInMem();
        }
    }

}
