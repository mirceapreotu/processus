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
            return $this->getValueByKey("firstName");
        }

        /**
         * @return string
         */
        public function getLastname()
        {
            return $this->getValueByKey("lastName");
        }

        /**
         * @return string
         */
        public function getFullName()
        {
            return $this->getValueByKey("fullName");
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
            return $this->getValueByKey("urlName");
        }
    }
}
?>