<?php

namespace Core\Lib\Db
{
    use Core\Interfaces\InterfaceDatabase;

    /**
     *
     */
    class Memcached implements InterfaceDatabase
    {
        public function fetch()
        {}

        public function fetchOne()
        {}

        public function fetchAll()
        {}

        public function insert()
        {}

        public function update()
        {}
    }
}

?>