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
        protected function getMapping()
        {
            return array(
                "name" => "userName", 
                "id" => "userId", 
                "email" => "mail", 
                "locale" => "language"
            );
        }
    
    }
}
?>