<?php

/** 
 * @author francis
 * 
 * 
 */

namespace Processus\Lib\Mvo
{
    use Processus\Abstracts\Vo\AbstractMVO;

    class UserMvo extends AbstractMVO
    {

        /**
         * @return \Processus\Abstracts\Vo\multitype:
         */
        public function getFirstname()
        {
            return $this->getValueByKey("first_name");
        }
        
        /**
         * @return string
         */
        public function getFullName()
        {
            return $this->getValueByKey("name");
        }
    }
}
?>