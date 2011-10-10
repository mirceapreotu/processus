<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/13/11
 * Time: 3:34 AM
 * To change this template use File | Settings | File Templates.
 */

    class App_GaintS_Config_GaintSConfig extends App_GaintS_Core_AbstractVO
    {

        /**
         * @var \App_GaintS_Config_CouchDb_Config
         */
        protected $_couchDbConfig;

        /**
         * @var \App_GaintS_Config_Membase_Config
         */
        protected $_membaseConfig;

        /**
         * @var \App_GaintS_Config_Twitter_Config
         */
        protected $_twitterConfig;

        /**
         * @return void
         */
        protected function init()
        {

            return;
            $config  = $this->getData();

            var_dump($config->membase_config);
            exit;

            $this->_membaseConfig = new App_GaintS_Config_Membase_Config();
            $this->_membaseConfig->setData($this->getValueByKey("membase_config"));
            $this->_couchDbConfig = new App_GaintS_Config_CouchDb_Config();
            $this->_couchDbConfig->setData($this->getValueByKey("couchDB"));
        }

        /**
         * @param $data
         * @return void
         */
        public function setData($data)
        {
            parent::setData($data);
            $this->init();
        }

        /**
         * @return App_GaintS_Config_CouchDb_Config
         */
        public function getCouchDbConfig()
        {
            return $this->_couchDbConfig;
        }

        /**
         * @return App_GaintS_Config_Membase_Config
         */
        public function getMembaseConfig()
        {
            return $this->_membaseConfig;
        }

        /**
         * @return App_GaintS_Config_Twitter_Config
         */
        public function getTwitterConfig()
        {
            return $this->_twitterConfig;
        }
    }
