<?php

namespace Application\JsonRpc\V1\App\Service
{
    class User extends \Processus\Abstracts\JsonRpc\AbstractJsonRpcService
    {

        /**
         * @return array
         */
        public function getInitialData ()
        {
            // get data from current user
            $userMvo = $this->getProcessusContext()->getUserBo()->getFacebookUserMvo();
            
            $userDto = $userMvo->setDto(new \Application\Dto\FbBasicDto())->export();
            
            // get current user's friends
            $friendsList = $this->getProcessusContext()->getUserBo()->getAppFriends();
            
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