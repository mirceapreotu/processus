<?php

namespace Application\JsonRpc\V1\App\Service
{
    use Application\Dto\FbBasicDto;
    
    use Processus\Abstracts\JsonRpc\AbstractJsonRpcService;

    class User extends AbstractJsonRpcService
    {

        /**
         * @return array
         */
        public function getInitialData ()
        {
            // get data from current user
            $userMvo = $this->getApplication()->getUserBo()->getFacebookUserMvo();
            
            $userDto = $userMvo->setDto(new FbBasicDto())->export();
            
            // get current user's friends
            $friendsList = $this->getApplication()->getUserBo()->getAppFriends();
            
            $friendsListDto = array();
            
            foreach ($friendsList as $friendMvo) {
                $friendsListDto[] = $friendMvo->setDto(new FbBasicDto())->export();
            }
            
            return array(
                "user" => $userDto, 
                "appFriends" => $friendsListDto
            );
        }
    }
}

?>