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
            return $this->getValueByKey("firstname");
        }
    }
}
?>