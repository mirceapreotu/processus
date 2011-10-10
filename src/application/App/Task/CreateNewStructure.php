<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 10/2/11
 * Time: 2:48 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_CreateNewStructure
{
    public function run()
    {
        $importer = new App_Task_ImportCities();
        $importer->run();

        $importer = new App_Task_ImportDjs();
        $importer->run();

        $importer = new App_Task_ImportUserData();
        $importer->run();

        $importer = new App_Task_ImportVenues();
        $importer->run();

        $importer = new App_Task_MapsDataToCouchDB();
        $importer->run();

        $importer = new App_Task_MapsDataToElasticSearch();
        $importer->run();


    }
}
