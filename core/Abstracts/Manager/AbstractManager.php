<?php

namespace Processus\Abstracts\Manager
{
    
    abstract class AbstractManager
    {

        /**
         * @param InterfaceComConfig $com
         */
        protected function fetch(InterfaceComConfig $com)
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
        protected function fetchOne(InterfaceComConfig $com)
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
        protected function fetchAll(InterfaceComConfig $com)
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
        protected function insert(InterfaceComConfig $com)
        {
            $com->getConnector()->insert($com->getSqlTableName(), $com->getSqlParams());
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         */
        protected function update(InterfaceComConfig $com)
        {
            $com->getConnector()->update($com->getSqlTableName(), $com->getSqlConditions());
        }

        // #########################################################
        

        /**
         * @param $com
         * @return mixed
         */
        protected function _fetchFromMysql($com)
        {
            return $com->getConnector()->fetch($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################
        

        /**
         * @param $com
         * @return mixed
         */
        protected function _fetchOneFromMysql($com)
        {
            return $com->getConnector()->fetchOne($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################
        

        /**
         * @param $com
         * @return mixed
         */
        protected function _fetchAllFromMysql($com)
        {
            return $com->getConnector()->fetchAll($com->getSqlStmt(), $com->getSqlParams());
        }
    
    }
}

?>