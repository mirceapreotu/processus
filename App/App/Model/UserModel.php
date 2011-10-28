<?php

namespace App\Model
{
    use Core\Abstracts\AbstractManager;

    /**
     *
     */
    class FooModel extends AbstractManager
    {
        /**
         * @return mixed
         */
        public function foo()
        {
            $mysql = $this->getMysqlInstance();
            return $mysql->fetch('SELECT id, facebook_id FROM users LIMIT 10');
        }
    }
}

?>