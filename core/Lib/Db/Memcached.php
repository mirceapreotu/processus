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
            $this->_memcachedClient->setOption(\Memcached::OPT_SERIALIZER, \Memcached::SERIALIZER_JSON);
            $this->_memcachedClient->addServer($host, $port);
        }
        
        /**
         * Return the filling percentage of the backend storage
         *
         * @throws \Zend\Cache\Exception
         * @return int integer between 0 and 100
         */
        public function getFillingPercentage ()
        {
            $mems = $this->_memcachedClient->getStats();
            return $mems;
            
            $memSize = null;
            $memUsed = null;
            foreach ($mems as $key => $mem) {
                if ($mem === false) {
                    $this->_log('can\'t get stat from ' . $key);
                    continue;
                }
        
                $eachSize = $mem['limit_maxbytes'];
                $eachUsed = $mem['bytes'];
                if ($eachUsed > $eachSize) {
                    $eachUsed = $eachSize;
                }
        
                $memSize += $eachSize;
                $memUsed += $eachUsed;
            }
        
            if ($memSize === null || $memUsed === null) {
                Cache\Cache::throwException('Can\'t get filling percentage');
            }
        
            return ((int) (100. * ($memUsed / $memSize)));
        }

        /**
         * @param $key
         * @return mixed
         */
        public function fetch($key = "foobar")
        {
            $jsonObject = json_decode($this->_memcachedClient->get($key));
            return (object)$jsonObject;
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
            $jsonString = json_encode($value);
            $this->_memcachedClient->set($key, $jsonString, $expiredTime);
            return $this->_memcachedClient->getResultCode();
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