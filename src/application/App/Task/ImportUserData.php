<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_ImportUserData
{

    public function run()
    {
        $dbClient = App_Facebook_Application::getInstance()->getDbClient();

        $sql = "SELECT * FROM members";

        $datas = $dbClient->getRows($sql);

        foreach($datas as $item)
        {
            $userMVO = new App_GaintS_Vo_Membase_UserMVO();
            $userMVO->setMemId($item["id"]);
            $userMVO->setData($item);
            $result = $userMVO->saveInMem();
        }
    }

}
