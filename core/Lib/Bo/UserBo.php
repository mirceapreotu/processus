<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Lib\Bo
{
    class UserBo extends \Processus\Abstracts\AbstractClass
    {
        /**
         * @var \Processus\Lib\Mvo\FacebookUserMvo
         */
        private $_userMvo;

        /**
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function getFacebookUserMvo()
        {
            if (!$this->_userMvo) {
                $this->_userMvo = new \Processus\Lib\Mvo\FacebookUserMvo();
                $this->_userMvo->setMemId($this->getFacebookUserId());
                $this->_userMvo->getFromMem();
            }

            return $this->_userMvo;
        }

        /**
         * @return string
         */
        public function getFacebookUserId()
        {
            return $this->getProcessusContext()->getFacebookClient()->getUserId();
        }

        /**
         * @return int
         */
        public function getFacebookHighScore()
        {
            return 1;
        }

        /**
         * @return bool|string
         */
        public function isAuthorized()
        {
            return True;
        }

    }
}

?>