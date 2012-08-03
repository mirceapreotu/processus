<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/23/11
 * Time: 12:09 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Interfaces
{
    interface InterfaceVo
    {
        /**
         * @abstract
         *
         * @param $data
         */
        public function setData($data);

        /**
         * @abstract
         *
         * @param string $key
         * @param object $value
         */
        public function setValueByKey(\string $key, \object $value);

        /**
         * @abstract
         *
         * @param string $key
         *
         * @return mixed
         */
        public function getValueByKey(\string $key);

        /**
         * @abstract
         *
         * @return mixed|array
         */
        public function getData();

        /**
         * @abstract
         *
         * @param InterfaceDto $dto
         *
         * @return \Processus\Interfaces\InterfaceVo
         */
        public function setDto(\Processus\Interfaces\InterfaceDto $dto);
    }
}