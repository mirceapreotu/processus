<?php

namespace Application\JsonRpc\V1\Admin\Service
{
    use Application\Model\UserModel;

    /**
     * Date: 10/28/11
     * Time: 1:23 PM
     * To change this template use File | Settings | File Templates.
     */
    class User
    {

        /**
         * @param $params
         * @return mixed
         */
        public function listing ($params)
        {
            $model = new UserModel();
            return $model->listing();
        }

        /**
         * @param $userData
         * @return bool
         */
        public function addUser($userData)
        {
            $userFirstName = $userData['name'];
            $userLastName = $userData['lastname'];

            return true;
        }
    }
}

?>