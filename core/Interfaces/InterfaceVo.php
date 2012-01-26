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
        public function setData($data);

        public function setValueByKey(\string $key, \object $value);

        public function getValueByKey(\string $key);

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