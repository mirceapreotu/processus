<?php
namespace Processus\Interfaces
{

    interface InterfaceComConfig
    {

        public function getFromCache();

        public function getSqlUpdateConditions();

        public function getSqlTableName();

        public function getSqlStmt();

        public function getSqlParams();

        public function getExpiredTime();
        
        public function getMemId();

        /**
         * @abstract
         * @return \Processus\Interfaces\InterfaceDatabase
         */
        public function getConnector();

        public function setConnector(InterfaceDatabase $_connector);
    
    }
}
?>