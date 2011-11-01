<?php
namespace Processus\Abstracts\Manager
{
    use Processus\Interfaces\InterfaceComConfig;
    
    use Processus\Interfaces\InterfaceDatabase;

    /**
     * 
     * @author fightbulc
     *
     */
    class ComConfig implements InterfaceComConfig
    {

        private $_memIdSalt = 'Crowdpark!43ver';

        private $_memId;

        private $_classPath;

        private $_fromCache = TRUE;

        private $_sqlStmt;

        private $_sqlParams = array();

        private $_sqlConditions = array();

        private $_expiredTime = 1;

        private $_connector;

        private $_sqlTableName;

        // #########################################################
        

        /**
         * @return the $_sqlConditions
         */
        public function getSqlConditions()
        {
            return $this->_sqlConditions;
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
         * @param multitype: $_sqlConditions
         */
        public function setSqlConditions($_sqlConditions)
        {
            $this->_sqlConditions = $_sqlConditions;
            return $this;
        }

        // #########################################################
        

        /**
         * @param field_type $_tableName
         */
        public function setSqlTableName($_sqlTableName)
        {
            $this->_sqlTableName = $_sqlTableName;
            return $this;
        }

        // #########################################################
        

        /**
         * @return string
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
            return md5($this->_sqlStmt . join('', $this->_sqlParams) . $this->_expiredTime . $this->_memIdSalt);
        }

        // #########################################################
        

        /**
         * @param string $_memId
         */
        public function setMemId(string $_memId)
        {
            $this->_memId = $_memId;
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
         * @return int $_expiredTime
         */
        public function getExpiredTime()
        {
            return $this->_expiredTime;
        }

        // #########################################################
        

        /**
         * @return the $_connector
         */
        public function getConnector()
        {
            return $this->_connector;
        }

        // #########################################################
        

        /**
         * @param boolean $_fromCache
         */
        public function setFromCache($_fromCache)
        {
            $this->_fromCache = $_fromCache;
            return $this;
        }

        // #########################################################
        

        /**
         * @param string $_sqlStmt
         */
        public function setSqlStmt(string $_sqlStmt)
        {
            $this->_sqlStmt = $_sqlStmt;
            return $this;
        }

        // #########################################################
        

        /**
         * @param array $_sqlParams
         */
        public function setSqlParams(array $_sqlParams)
        {
            $this->_sqlParams = $_sqlParams;
            return $this;
        }

        // #########################################################
        

        /**
         * @param number $_expiredTime
         */
        public function setExpiredTime($_expiredTime)
        {
            $this->_expiredTime = $_expiredTime;
            return $this;
        }

        // #########################################################        
        

        /**
         * @param InterfaceDatabase $_connector
         */
        public function setConnector(InterfaceDatabase $_connector)
        {
            $this->_connector = $_connector;
            return $this;
        }
    
    // #########################################################        
    

    }
}
?>