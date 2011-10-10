<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/9/11
 * Time: 12:53 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_JsonRpc_V1_App_Service_Events extends App_GaintS_Core_AbstractJsonRPCService
{
    /**
     * @Json-Rpc
     *      - Method: "App.Service.Events"
     *      - Params: null
     *      - Result: null
     */
    public function __construct()
    {
        $this->_manager = new App_Manager_App_EventsManager();
    }

    /**
     * @Json-Rpc
     *      - Method: "App.Service.Events.filter"
     *      - Params: [{id: 12345, time: time(), place: ""}]
     *      - Result: null
     * @return void
     */
    public function filter()
    {
        //$this->getManager()->get_event()
    }

    /**
     * @return App_Manager_App_EventsManager
     */
    public function getManager()
    {
        return $this->_manager;
    }
}
