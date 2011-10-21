<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 10/5/11
 * Time: 10:27 PM
 * To change this template use File | Settings | File Templates.
 */
class App_JsonRpc_V1_Public_Service_Filter extends App_JsonRpc_V1_Public_Service
{
    public function __construct ()
    {
        $this->_manager = new App_Manager_Public_FilterManager();
    }
    /**
     * @param $filterObject
     * @return array
     */
    public function filter ($filterObject)
    {
        return $this->getManager()->filter($filterObject);
    }
    /**
     * @return App_Manager_Public_FilterManager
     * @see App_GaintS_Core_AbstractJsonRPCService::getManager()
     */
    protected function getManager ()
    {
        return $this->_manager;
    }
}
