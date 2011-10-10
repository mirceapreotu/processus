<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/26/11
 * Time: 1:29 AM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Manager_Public_CityManager extends App_Manager_AbstractManager
{

    /**
     * @param $name
     * @return App_Model_Mvo_CitiesMvo
     */
    public function getCityByName($name)
    {
        $mvo = new App_Model_Mvo_CitiesMvo();
        $mvo->setMemId($name);
        $mvo->getFromMem();

        return $mvo;
    }

    /**
     * @param $id
     * @return App_Model_Mvo_CitiesMvo
     */
    public function getCityById($id)
    {
        $mvo = new App_Model_Mvo_CitiesMvo();
        $mvo->setMemId($id);
        $mvo->getFromMem();
        return $mvo;
    }
    
}
