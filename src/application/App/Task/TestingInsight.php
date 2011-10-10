<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 2:50 AM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Task_TestingInsight {

    public function run()
    {
        $insight = new App_GaintS_Vo_Facebook_Insight();
        echo json_encode($insight->getInsight());
    }
}
