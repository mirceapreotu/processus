<?php

namespace Processus\Abstracts\Manager
{

    abstract class AbstractManager extends \Processus\Abstracts\AbstractClass
    {
        /**
         * @return string
         */
        protected function getUserId()
        {
            return $this->getProcessusContext()->getUserBo()->getFacebookUserId();
        }

        /**
         * @var \Processus\Lib\Db\Memcached
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
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcRequest
         */
        protected function _getRawRequest()
        {
            return $this->getProcessusContext()->getBootstrap()->getGateway()->getRequest();
        }

        /**
         * @return \Processus\Lib\Db\Memcached
         */
        protected function getMemcached()
        {
            if (!$this->_memcached) {
                $config = $this->getProcessusContext()
                    ->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getCouchbasePortByDatabucketKey($this->getDataBucketKey());

                $this->_memcached = \Processus\Lib\Server\ServerFactory::memcachedFactory(
                    $config['host'], $config['port']
                );
            }

            return $this->_memcached;

        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function getDataFromCache(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $this->getMemcached()->fetch($com->getMemId());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed|null
         */
        protected function fetch(\Processus\Interfaces\InterfaceComConfig $com)
        {
            $results = NULL;

            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }

            if (empty($results)) {
                $results = $this->_fetchFromMysql($com);
                $this->cacheResult($com, $results);
            }

            return $results;
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed|null
         */
        protected function fetchOne(\Processus\Interfaces\InterfaceComConfig $com)
        {
            $results = NULL;

            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }

            if (empty($results)) {
                $results = $this->_fetchOneFromMysql($com);
                $this->cacheResult($com, $results);
            }

            return $results;
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed|null
         */
        protected function fetchAll(\Processus\Interfaces\InterfaceComConfig $com)
        {
            $results = NULL;

            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }

            if (empty($results)) {
                $results = $this->_fetchAllFromMysql($com);
                $this->cacheResult($com, $results);
            }

            return $results;
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return \Zend\Db\Statement\Pdo
         */
        protected function insert(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->insert($com->getSqlTableName(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return \Zend\Db\Statement\Pdo
         */
        protected function update(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->update(
                $com->getSqlTableName(), $com->getSqlParams(), $com->getSqlUpdateConditions()
            );
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function _fetchFromMysql(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->fetch($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function _fetchOneFromMysql(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->fetchOne($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function _fetchAllFromMysql(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->fetchAll($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         * @param                                          $results
         */
        protected function cacheResult(\Processus\Interfaces\InterfaceComConfig $com, $results)
        {
            $this->getMemcached()->insert($com->getMemId(), $results, $com->getExpiredTime());
        }

        /**
         * @param null|\Processus\Interfaces\InterfaceDatabase $connector
         *
         * @return ComConfig
         */
        protected function ccFactory(\Processus\Interfaces\InterfaceDatabase $connector = NULL)
        {
            if (!$connector) {
                $connector = \Processus\Lib\Db\MySQL::getInstance();
            }
            $comConfig = new ComConfig();
            return $comConfig->setConnector($connector);
        }
    }
}

?>