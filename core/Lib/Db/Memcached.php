<?php

namespace Processus\Lib\Db
{
    use Processus\Interfaces\InterfaceDatabase;

    class Memcached implements InterfaceDatabase
    {
        /**
         * @var \Memcached
         */
        private $_memcachedClient;

        /**
         * @param string $host
         * @param string $port
         * @param string $id
         */
        public function __construct(string $host, string $port, $id = "default")
        {
            $this->_memcachedClient = new \Memcached($id);
            $this->_memcachedClient->addServer($host, $port);
        }

        /**
         * @param $key
         * @return mixed
         */
        public function fetch($key = "foobar")
        {
            return $this->_memcachedClient->get($key);
        }

        /**
         * @throws \Exception
         */
        public function fetchOne()
        {
            throw new \Exception("Not implemented");
        }

        /**
         * @throws \Exception
         */
        public function fetchAll()
        {
            throw new \Exception("Not implemented");
        }

        /**
         * @param string $key
         * @param mixed $value
         */
        public function insert($key = "foobar", $value = array())
        {
            $this->_memcachedClient->set($key, $value);
        }

        /**
         * @throws \Exception
         */
        public function update()
        {
            throw new \Exception("Not implemented");
        }
    }
}

?>