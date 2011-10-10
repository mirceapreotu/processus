<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 10/2/11
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_RestructureDB extends App_GaintS_Core_AbstractTask
{
    public function run()
    {
        $tables = $this->getTables();
    }

    /**
     * @return array
     */
    private function getTables()
    {
        $sqlStmt = "SHOW TABLES";

        $dbClient = $this->getDbClient();

        $rows = $dbClient->getRows($sqlStmt);
        $tables = array();

        foreach($rows AS $tableKey => $table)
        {
             $tables[] = $table['Tables_in_meetidaaa'];
        }

        return $tables;
    }
}
