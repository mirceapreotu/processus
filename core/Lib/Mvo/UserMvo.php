<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Mvo
{

    class UserMvo extends \Processus\Abstracts\Vo\AbstractMVO implements \Processus\Interfaces\InterfaceUser
    {

        /**
         * @return string
         */
        public function getFirstname()
        {
            return $this->getValueByKey("first_name");
        }

        /**
         * @return string
         */
        public function getLastname()
        {
            return $this->getValueByKey("last_name");
        }

        /**
         * @return string
         */
        public function getFullName()
        {
            return $this->getValueByKey("name");
        }

        /**
         * @return array|mixed
         */
        public function getId()
        {
            return $this->getValueByKey("id");
        }

        /**
         * @return array|mixed
         */
        public function getCreated()
        {
            return $this->getValueByKey("created");
        }

        /**
         * @return array|mixed
         */
        public function getFacebookId()
        {
            return $this->getValueByKey("id");
        }

        /**
         * @return array|mixed
         */
        public function getName()
        {
            return $this->getValueByKey("name");
        }

        /**
         * @return array|mixed
         */
        public function getUrlName()
        {
            return $this->getValueByKey("username");
        }

        /**
         * @return array|mixed
         */
        public function getIsAppUser()
        {
            return $this->getValueByKey('isAppUser');
        }

        /**
         * @param bool $is
         *
         * @return \Processus\Lib\Mvo\UserMvo
         */
        public function setIsAppUser(\boolean $is)
        {
            $this->setValueByKey('isAppUser', $is);
            return $this;
        }
    }
}
?>