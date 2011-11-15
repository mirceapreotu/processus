<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Mvo {
    use Processus\Abstracts\Vo\AbstractMVO;
    use Processus\Interfaces\InterfaceUser;

    class UserMvo extends AbstractMVO implements InterfaceUser
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
    }
}
?>