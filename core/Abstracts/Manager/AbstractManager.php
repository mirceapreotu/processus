<?php

namespace Processus\Abstracts\Manager
{
    
    use Processus\Interfaces\InterfaceComConfig;

    abstract class AbstractManager
    {

        /**
         * @param InterfaceComConfig $com
         */
        public function fetch(InterfaceComConfig $com)
        {
            $results = NULL;
            
            if ($com->getFromCache() === TRUE) {
                // $results = theCache($com);
            }
            
            if (empty($results)) {
                $results = $this->_fetchFromMysql($com);
            }
            
            return $results;
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         */
        public function fetchOne(InterfaceComConfig $com)
        {
            $results = NULL;
            
            if ($com->getFromCache() === TRUE) {
                // $results = theCache($com);
            }
            
            if (empty($results)) {
                $results = $this->_fetchOneFromMysql($com);
            }
            
            return $results;
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         */
        public function fetchAll(InterfaceComConfig $com)
        {
            $results = NULL;
            
            if ($com->getFromCache() === TRUE) {
                // $results = theCache($com);
            }
            
            if (empty($results)) {
                $results = $this->_fetchAllFromMysql($com);
            }
            
            return $results;
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         */
        public function insert(InterfaceComConfig $com)
        {
            $com->getConnector()->insert($com->getSqlTableName(), $com->getSqlParams());
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         */
        public function update(InterfaceComConfig $com)
        {
            $com->getConnector()->update($com->getSqlTableName(), $com->getSqlConditions());
        }

        // #########################################################
        

        /**
         * @param array $com
         */
        private function _fetchFromMysql($com)
        {
            return $com->getConnector()->fetch($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################
        

        /**
         * @param array $com
         */
        private function _fetchOneFromMysql($com)
        {
            return $com->getConnector()->fetchOne($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################
        

        /**
         * @param array $com
         */
        private function _fetchAllFromMysql($com)
        {
            return $com->getConnector()->fetchAll($com->getSqlStmt(), $com->getSqlParams());
        }
    
    }
}

?>