<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/13/11
 * Time: 3:55 AM
 * To change this template use File | Settings | File Templates.
 */

    class App_GaintS_Config_Membase_Config extends App_GaintS_Core_AbstractVO
    {

        protected $_databucketList = array();

        /**
         * @param string $bucketName
         * @return void
         */
        public function getMembaseDataBucketByName($bucketName = "default")
        {

            if(!$this->_databucketList[$bucketName])
            {
                var_dump($this->getMembaseConfig());
                exit;
                $config  = $this->getMembaseConfig();
                $bucktData = $config['membase_databucket'];
                $memServer = App_GaintS_Core_ServerFactory::createMemcachedServer($bucktData->host, $bucktData->port);
                var_dump($bucktData);
                //$this->_databucketList[] =
            }

            return $this->_databucketList[$bucketName];
        }

        /**
         * @param $prio string
         * @return int
         */
        public function getTimeByPrio($prio)
        {
            return (int)$this->getMembaseConfig()->membase_expireTime->$prio;
        }

        /**
         * @return array
         */
        public function getMembaseConfig()
        {
            return (array)$this->getData();
        }
    }
