<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/16/11
     * Time: 11:43 PM
     * To change this template use File | Settings | File Templates.
     */
    class App_JsonRpc_V1_Public_Service_Radio extends App_JsonRpc_V1_Public_Service
    {

        public function __construct()
        {
            $this->_manager = new App_Manager_Public_RadioManager();
        }

        /**
         * @return array|mixed|null
         */
        public function getDailyRadio()
        {
            return $this->getManager()->getDailyRadio();
        }

        /**
         * @return App_Manager_Public_RadioManager
         */
        public function getManager()
        {
            return $this->_manager;
        }
    }
