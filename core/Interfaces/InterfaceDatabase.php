<?php

namespace Processus\Interfaces
{
    /**
     *
     */
    interface InterfaceDatabase
    {
        /**
         * @abstract
         *
         */
        public function fetch();
        /**
         * @abstract
         *
         */
        public function fetchOne();
        /**
         * @abstract
         *
         */
        public function fetchAll();
        /**
         * @abstract
         * @return \Zend\Db\Statement\Pdo
         */
        public function insert();
        /**
         * @abstract
         * @return \Zend\Db\Statement\Pdo
         */
        public function update();
    }
}

?>