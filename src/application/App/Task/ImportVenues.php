<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_ImportVenues extends App_GaintS_Core_AbstractTask
{

    public function run()
    {
        $dbClient = App_Facebook_Application::getInstance()->getDbClient();

        $sql  = "SELECT * FROM venues";

        $datas = $dbClient->getRows($sql);

        foreach($datas as $item)
        {
            $mvo = new App_Model_Mvo_VenuesMvo();
            $mvo->setMemId($item["urlname"]);
            $mvo->setData($item);
            $mvo->saveInMem();

            $mvo->setMemId($item["id"]);
            $mvo->setData($item);
            $mvo->saveInMem();
        }
    }

}
