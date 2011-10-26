<?php

    namespace Core\Interfaces;

    /**
     *
     */
    interface InterfaceDatabase
    {
        public function fetch();
        public function fetchOne();
        public function fetchAll();
        public function insert();
        public function update();
    }

?>