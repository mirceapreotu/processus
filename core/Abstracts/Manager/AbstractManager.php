<?php

namespace Processus\Abstracts\Manager
{
    
    use Processus\Lib\Db\Memcached;
    
    use Processus\Interfaces\InterfaceComConfig;
    
    use Processus\Lib\Server\ServerFactory;
    
    use Processus\Abstracts\AbstractClass;

    abstract class AbstractManager extends AbstractClass
    {

        /**
         * @var Memcached
         */
        protected $_memcached;

        /**
         * @return string
         */
        protected function getDataBucketKey()
        {
            return 'default';
        }

        // #########################################################
        
        /**
         * @return \Processus\Lib\Db\Memcached
         */
        protected function getMemcached()
        {
            if (! $this->_memcached) {
                $config = $this->getApplication()
                    ->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getCouchbasePortByDatabucketKey($this->getDataBucketKey());
                
                $this->_memcached = ServerFactory::memcachedFactory($config['host'], $config['port']);
            }
            
            return $this->_memcached;
        
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         * @return \Processus\Lib\Db\mixed
         */
        protected function getDataFromCache(InterfaceComConfig $com)
        {
            return $this->getMemcached()->fetch($com->getMemId());
        }

        // #########################################################
        

        /**
         * @param InterfaceComConfig $com
         */
        protected function fetch(InterfaceComConfig $com)
        {
            $results = NULL;
            
            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }
            
            if (empty($results)) {
                $results = $this->_fetchFromMysql($com);
                $this->cacheResult($results, $com);
            }
            
            return $results;
        }

        // #########################################################
        
        /**
         * @param InterfaceComConfig $com
         * @param mixed|array $results
         */
        protected function cacheResult(InterfaceComConfig $com, $results)
        {
            $this->getMemcached()->insert($com->getMemId(), $results, $com->getExpiredTime());
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