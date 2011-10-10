<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_ImportDjs
{

    public function run()
    {
        $dbClient = App_Facebook_Application::getInstance()->getDbClient();

        $sql = 'SELECT * FROM djs WHERE status="complete"';

        $djsData = $dbClient->getRows($sql);

        foreach($djsData as $item)
        {
            $mvo = new App_Model_Mvo_DjMvo();
            $mvo->setMemId($item["urlname"]);
            $mvo->saveInMem();

            $mvo->setMemId($item['id']);
            $mvo->saveInMem();
        }
    }

}
