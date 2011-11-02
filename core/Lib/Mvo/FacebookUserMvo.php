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
         * @return boolean
         */
        public function checkForUpdate()
        {
           return TRUE; 
        }
    }

}
?>