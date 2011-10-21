<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/9/11
 * Time: 12:32 PM
 * To change this template use File | Settings | File Templates.
 */
 
class App_JsonRpc_V1_Public_Service_Tracking extends App_GaintS_Core_AbstractJsonRPCService
{
    
    public function __construct()
    {
        $this->_manager = new App_Manager_App_Tracking();
    }
    
    /**
     * @param object $artistObj
     */
    public function playedArtist($artistObj)
    {
        $this->getManager()->trackPlayingArtist($artistObj);   
    }
    
    /**
     * @return App_Manager_App_Tracking
     * @see App_GaintS_Core_AbstractJsonRPCService::getManager()
     */
    public function getManager()
    {
        return $this->_manager;
    }
}
