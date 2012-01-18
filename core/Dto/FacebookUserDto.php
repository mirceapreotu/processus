<?php

/** 
 * @author fightbulc
 * 
 * 
 */
namespace Processus\Dto
{
    
    use Processus\Abstracts\Vo\AbstractDTO;

    class FacebookUserDto extends AbstractDTO
    {

        /**
         * @see Processus\Abstracts\Vo.AbstractDTO::getMapping()
         */
        protected function getMapping ()
        {
            return array(
                
                "id" => "userId", 
                "first_name" => "firstName", 
                "last_name" => "lastName", 
                "name" => "fullName", 
                "locale" => "language", 
                "gender" => "gender"
            );
        }
    
    }
}
?>