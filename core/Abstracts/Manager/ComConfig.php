<?php
namespace Processus\Abstracts\Manager
{
    /**
     *
     * @author fightbulc
     *
     */
    class ComConfig implements \Processus\Interfaces\InterfaceComConfig
    {
        private $_memId;

        private $_classPath;

        private $_fromCache = TRUE;

        private $_sqlStmt = "";

        private $_sqlParams = array();

        private $_sqlUpdateConditions = array();

        private $_expiredTime = 1;

        /**
         * @var \Processus\Interfaces\InterfaceDatabase
         */
        private $_connector = null;

        private $_sqlTableName = null;

        // #########################################################


        /**
         * @return the $_sqlUpdateConditions
         */
        public function getSqlUpdateConditions()
        {
            return $this->_sqlUpdateConditions;
        }

        // #########################################################


        /**
         * @return the $_tableName
         */
        public function getSqlTableName()
        {
            return $this->_sqlTableName;
        }

        // #########################################################


        /**
         * @param $_sqlUpdateConditions
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setSqlUpdateConditions($_sqlUpdateConditions)
        {
            $this->_sqlUpdateConditions = $_sqlUpdateConditions;
            return $this;
        }

        // #########################################################


        /**
         * @param $_sqlTableName
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setSqlTableName($_sqlTableName)
        {
            $this->_sqlTableName = $_sqlTableName;
            return $this;
        }

        // #########################################################


        /**
         * @return mixed
         */
        public function getClassPath()
        {
            return $this->_classPath;
        }

        // #########################################################


        /**
         * @param string $_classPath
         */
        public function setClassPath(string $_classPath)
        {
            $this->_classPath = $_classPath;
        }

        // #########################################################


        /**
         * @return string
         */
        public function getMemId()
        {
            if (empty($this->_memId)) {
                $this->setMemId($this->generateMemId());
            }

            return $this->_memId;
        }

        // #########################################################


        /**
         * @return string
         */
        private function generateMemId()
        {
            return md5(
                $this->_sqlStmt . join('', $this->_sqlParams) . $this->_expiredTime . \Processus\ProcessusContext::getInstance()->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getValueByKey('couchbaseSalt'));
        }

        // #########################################################


        /**
         * @param string $_memId
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setMemId(string $_memId)
        {
            $this->_memId = $_memId;
            return $this;
        }

        // #########################################################


        /**
         * @return boolean $_fromCache
         */
        public function getFromCache()
        {
            return $this->_fromCache;
        }

        // #########################################################


        /**
         * @return string $_sqlStmt
         */
        public function getSqlStmt()
        {
            if (strlen($this->_sqlStmt) <= 1) {
                throw new ComConfigException('SQL Statement not available');
            }
            return $this->_sqlStmt;
        }

        // #########################################################


        /**
         * @return array $_sqlParams
         */
        public function getSqlParams()
        {
            return $this->_sqlParams;
        }

        // #########################################################


        /**
         * @return int
         */
        public function getExpiredTime()
        {
            return $this->_expiredTime;
        }

        // #########################################################


        /**
         * @return \Processus\Interfaces\InterfaceDatabase
         */
        public function getConnector()
        {
            if (!$this->_connector) {
                throw new ComConfigException('Connector not available');
            }
            return $this->_connector;
        }

        // #########################################################


        /**
         * @param $_fromCache
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setFromCache($_fromCache)
        {
            $this->_fromCache = $_fromCache;
            return $this;
        }

        // #########################################################


        /**
         * @param string $_sqlStmt
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setSqlStmt(string $_sqlStmt)
        {
            $this->_sqlStmt = $_sqlStmt;
            return $this;
        }

        // #########################################################


        /**
         * @param array $_sqlParams
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setSqlParams(array $_sqlParams)
        {
            $this->_sqlParams = $_sqlParams;
            return $this;
        }

        // #########################################################


        /**
         * @param $_expiredTime
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setExpiredTime($_expiredTime)
        {
            $this->_expiredTime = $_expiredTime;
            return $this;
        }

        // #########################################################        


        /**
         * @param \Processus\Interfaces\InterfaceDatabase $_connector
         *
         * @return \Processus\Abstracts\Manager\ComConfig
         */
        public function setConnector(\Processus\Interfaces\InterfaceDatabase $_connector)
        {
            $this->_connector = $_connector;
            return $this;
        }

        // #########################################################

    }
}
?>