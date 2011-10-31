<?php

namespace Application\JsonRpc\V1\Pub\Service
{
    use Application\Model\UserModel;

    /**
     * Date: 10/28/11
     * Time: 1:23 PM
     * To change this template use File | Settings | File Templates.
     */
    class User
    {

        public function listing ($params)
        {
            $model = new UserModel();
            return $model->listing();
        }
    }
}

?>