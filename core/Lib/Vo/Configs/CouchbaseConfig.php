<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Lib\Vo\Configs
{

    use Processus\Abstracts\Vo\AbstractVO;

    class CouchbaseConfig extends AbstractVO
    {

        /**
         * @return mixed
         */
        public function getRandomCouchbaseServerConfig()
        {
            $serverList  = $this->getCouchbaseServerList();
            $totalServer = count($serverList) - 1;

            $randServerId = rand(0, $totalServer);

            return $serverList[$randServerId];
        }

        /**
         * @return array|mixed
         */
        private function getCouchbaseServerList()
        {
            return $this->getValueByKey("couchbaseServers");
        }

        /**
         * @param string $databucketKey
         *
         * @return mixed
         */
        public function getCouchbasePortByDatabucketKey(string $databucketKey)
        {
            return $this->_data->couchbasePorts->$databucketKey;
        }

        /**
         * @return mixed
         */
        public function getCouchbasePortList()
        {
            return $this->_data->couchbasePorts;
        }

        /**
         * @return array|mixed
         */
        private function getCouchbaseSalt()
        {
            return $this->getValueByKey('couchbaseSalt');
        }
    }
}
?>