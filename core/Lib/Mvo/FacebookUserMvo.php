<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Mvo
{
    
    use Processus\Registry;
    
    use Processus\Lib\Mvo\UserMvo;

    class FacebookUserMvo extends UserMvo
    {

        /**
         * @see Processus\Abstracts\Vo.AbstractMVO::getDataBucketPort()
         */
        protected function getDataBucketPort()
        {
            return Registry::getInstance()->getProcessusConfig()
                ->getCouchbaseConfig()
                ->getCouchbasePortByDatabucketKey("fbusers");
        }

        /**
         * @return boolean
         */
        public function checkForUpdate()
        {
            $memCachedClient = $this->getMemcachedClient();
            $check = $memCachedClient->fetch($this->getMemId());
            
            if ($check) {
            
            }
            
            return TRUE;
        }
    
    }

}
?>