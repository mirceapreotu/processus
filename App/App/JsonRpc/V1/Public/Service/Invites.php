<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/16/11
     * Time: 8:32 PM
     * To change this template use File | Settings | File Templates.
     */
    class App_JsonRpc_V1_Public_Service_Invites extends App_JsonRpc_V1_App_Service
    {
        public function __construct()
        {
            $this->_manager = new App_Manager_Public_InvitesManager();
        }

        /**
         * @param $inviteData
         * @return array|null
         */
        public function addInvite($inviteData)
        {
            $this->getManager()->addInvite($inviteData);
            return $this->getTotalInvite();
        }

        /**
         * @return array|null
         */
        public function getTotalInvite()
        {
            return $this->getManager()->getTotalInvite();
        }

        /**
         * @return App_Manager_Public_InvitesManager
         */
        public function getManager()
        {
            return $this->_manager;
        }
    }
