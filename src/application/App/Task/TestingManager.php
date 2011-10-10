<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 12:14 AM
 * To change this template use File | Settings | File Templates.
 */

    class App_Task_TestingManager
    {
        public function run()
        {
            $manager = new App_Manager_App_DJsManager();
            $result = $manager->listing();

            $manager = new App_Manager_App_BaseManager();
            $result = $manager->get_cities();

            $manager = new App_Manager_Public_CityManager();
            /** @var $mvo  */
            $mvo = $manager->getCityByName("Berlin");
            var_dump($mvo->getData());
        }
    }
