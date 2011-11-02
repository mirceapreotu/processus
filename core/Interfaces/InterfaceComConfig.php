<?php
namespace Processus\Interfaces
{

    interface InterfaceComConfig
    {

        public function getFromCache();

        public function getSqlConditions();

        public function getSqlTableName();

        public function getSqlStmt();

        public function getSqlParams();

        public function getExpiredTime();

        public function getConnector();

        public function setConnector(InterfaceDatabase $_connector);
    
    }
}
?>