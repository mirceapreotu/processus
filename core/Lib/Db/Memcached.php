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
         * @return array
         */
        public function getStats()
        {
            return $this->_memcachedClient->getStats();
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
         * @return Memcached ErrorCode
         */
        public function insert($key = "foobar", $value = array(), $expiredTime = 1)
        {
            $this->_memcachedClient->set($key, $value, $expiredTime);
            return $this->_memcachedClient->getResultCode();
        }

        /**
         * @param array $keys
         * @return mixed
         */
        public function getMulipleByKey(array $keys)
        {
            return $this->_memcachedClient->getMulti($keys);
        }

        /**
         * @throws \Exception
         */
        public function update()
        {
            throw new \Exception("Not implemented");
        }

        /**
         * @param $key
         * @return bool
         */
        public function delete($key)
        {
            return $this->_memcachedClient->delete($key);
        }
    }
}

?>