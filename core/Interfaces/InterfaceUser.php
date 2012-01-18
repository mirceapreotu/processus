<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/15/11
 * Time: 11:24 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Interfaces
{
    interface InterfaceUser extends InterfaceVo
    {
        /**
         * @abstract
         * @return int
         */
        public function getId();

        /**
         * @abstract
         * @return int
         */
        public function getCreated();
        /**
         * @abstract
         * @return int
         */
        public function getFacebookId();
        public function getFullName();
        public function getFirstName();
        public function getName();
        public function getUserName();
        public function getIsAppUser();
        public function setIsAppUser(\boolean $is);
    }
}