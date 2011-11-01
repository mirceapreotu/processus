<?php
namespace Processus\Abstracts\Manager
{
    
    use Processus\Interfaces\InterfaceDatabase;

    class ComConfig implements InterfaceComConfig
    {

        private $_fromCache = TRUE;

        private $_sqlStmt;

        private $_sqlParams = array();

        private $_expiredTime = 1;

        private $_connector;

        /**
         * @return boolean $_fromCache
         */
        public function getFromCache ()
        {
            return $this->_fromCache;
        }

        /**
         * @return string $_sqlStmt
         */
        public function getSqlStmt ()
        {
            return $this->_sqlStmt;
        }

        /**
         * @return array $_sqlParams
         */
        public function getSqlParams ()
        {
            return $this->_sqlParams;
        }

        /**
         * @return int $_expiredTime
         */
        public function getExpiredTime ()
        {
            return $this->_expiredTime;
        }

        /**
         * @return the $_connector
         */
        public function getConnector ()
        {
            return $this->_connector;
        }

        /**
         * @param boolean $_fromCache
         */
        public function setFromCache ($_fromCache)
        {
            $this->_fromCache = $_fromCache;
            return $this;
        }

        /**
         * @param string $_sqlStmt
         */
        public function setSqlStmt (string $_sqlStmt)
        {
            $this->_sqlStmt = $_sqlStmt;
            return $this;
        }

        /**
         * @param array $_sqlParams
         */
        public function setSqlParams (array $_sqlParams)
        {
            $this->_sqlParams = $_sqlParams;
            return $this;
        }

        /**
         * @param number $_expiredTime
         */
        public function setExpiredTime ($_expiredTime)
        {
            $this->_expiredTime = $_expiredTime;
            return $this;
        }

        /**
         * @param InterfaceDatabase $_connector
         */
        public function setConnector (InterfaceDatabase $_connector)
        {
            $this->_connector = $_connector;
            return $this;
        }
    
    }
}
?>