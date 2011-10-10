<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/9/11
 * Time: 12:32 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_JsonRpc_V1_Public_Service_Cities extends App_JsonRpc_V1_Public_Service
{

    /**
     * @param $name
     * @return object
     */
    public function getCityByName($name)
    {
        $manager = new App_Manager_Public_CityManager();
        $mvo = $manager->getCityByName($name);
        return $mvo->getData();
    }

    public function getCityByCoords($coords)
    {
        $long = $coords['long'];
        $lat = $coords['lat'];


    }
}
