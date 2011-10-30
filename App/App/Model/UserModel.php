<?php

namespace App\Model
{
    use Core\Abstracts\AbstractManager;

    /**
     *
     */
    class UserModel extends AbstractManager
    {

        /**
         * @return mixed
         */
        public function listing ()
        {
            $mysql = $this->getMysqlInstance();
            return $mysql->fetchAll(
            'SELECT id, facebook_id FROM users LIMIT 10');
        }
    }
}

?>