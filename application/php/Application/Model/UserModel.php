<?php

namespace Application\Model
{
    use Processus\Lib\Db\MySQL;

	use Processus\Abstracts\Manager\ComConfig;

	use Processus\Abstracts\Manager\AbstractManager;

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
            $com = new ComConfig();
            $com->setConnector(MySQL::getInstance())
                ->setExpiredTime(100)
                ->setFromCache(TRUE)
                ->setSqlStmt('SELECT id, facebook_id FROM users LIMIT 10');

            return $this->fetchAll($com);
        }
    }
}

?>