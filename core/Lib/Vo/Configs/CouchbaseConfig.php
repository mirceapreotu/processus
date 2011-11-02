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
         * @return \Processus\Abstracts\Vo\mixed
         */
        public function getRandomCouchbaseServerConfig()
        {
            $serverList = $this->getCouchbaseServerList();
            $totalServer = count($serverList)-1;

            $randServerId = rand(0, $totalServer);
            
            return $serverList[$randServerId];         
        }
        
        /**
         * @return \Processus\Abstracts\Vo\mixed
         */
        private function getCouchbaseServerList()
        {
            return $this->getValueByKey("couchbaseServers");
        }
        
        /**
         * @return array
         * @param string $databucketKey
         */
        private function getCouchbasePortsByDatabucketKey(string $databucketKey)
        {
             return $this->_data['couchbasePorts'][$databucketKey];   
        }
        
        /**
         * @return string
         */
        private function getCouchbaseSalt()
        {
            return $this->getValueByKey('couchbaseSalt');
        }
    }
}
?>