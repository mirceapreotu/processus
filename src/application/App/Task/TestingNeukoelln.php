<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_TestingNeukoelln
{

    public function run()
    {
        $dbClient = App_Facebook_Application::getInstance()->getDbClient();
        
        $sql = 'SELECT * FROM venues WHERE id=1004';
        $rows = $dbClient->getRow($sql);
        
        echo json_encode($rows);
        
        $rowUpdate = $rows;
        $rowUpdate["district"] = "Fuckers";
        $where = "id=:id LIMIT 1";
        $params = array(
            "id" => (int)$rows["id"]
        );
        
        $affectedRows = $dbClient->update("venues", $rowUpdate, $where, $params);
        var_dump($affectedRows);
        $result[] = $rowUpdate;

        echo json_encode($result);
    }
}