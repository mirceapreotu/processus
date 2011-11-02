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
        public function getCouchbaseServer()
        {
            
        }
        
        private function getRandomCouchbaseServerConfig()
        {
            
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