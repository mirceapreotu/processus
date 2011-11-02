<?php

/** 
 * @author francis
 * 
 * 
 */
namespace Processus\Lib\Vo\Configs
{
    
    use Processus\Abstracts\Vo\AbstractVO;

    class ProcessusConfig extends AbstractVO
    {
        //TODO - Insert your code here
        
        /**
         * @return \Processus\Abstracts\Vo\mixed
         */
        public function getBeanstalkdConfig()
        {
            return $this->getValueByKey("beanstalkd");
        }
        
        public function getCouchbaseConfig()
        {
            return $this->getValueByKey("couchbaseConfig");
        }

    }
}
?>