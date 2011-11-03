<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Mvo
{
    use Processus\Lib\Mvo\UserMvo;

    class FacebookUserMvo extends UserMvo
    {

        /**
         * @see Processus\Abstracts\Vo.AbstractMVO::getDataBucketPort()
         */
        protected function getDataBucketPort()
        {
            $config = $this->getApplication()
                ->getRegistry()
                ->getProcessusConfig()
                ->getCouchbaseConfig()
                ->getCouchbasePortByDatabucketKey("fbusers");
            
            return $config['port'];
        }

        /**
         * @see Processus\Abstracts\Vo.AbstractMVO::setMemId()
         */
        public function setMemId($mId)
        {
            $this->_memId = "FacebookUserMvo_2" . $mId;
            return $this;
        }
    
    }

}
?>