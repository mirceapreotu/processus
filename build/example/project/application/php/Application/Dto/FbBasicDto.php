<?php

namespace Application\Dto
{
    
    use Processus\Abstracts\Vo\AbstractDTO;

    class FbBasicDto extends AbstractDTO
    {
        /**
         * @see Processus\Abstracts\Vo.AbstractDTO::getMapping()
         */
        protected function getMapping ()
        {
            return array(
                
                "id" => "userId", 
                "name" => "fullName", 
                "locale" => "language"
            );
        }
    }
}

?>