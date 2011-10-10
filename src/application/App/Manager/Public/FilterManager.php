<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/26/11
 * Time: 2:49 AM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Manager_Public_FilterManager extends App_Manager_AbstractManager
{
    public function filter($filterData)
    {
        $time = $filterData['time'];
        $genre = $filterData['genre'];
        $venue = $filterData['venue'];
        $cost = $filterData['cost'];
    }
}
