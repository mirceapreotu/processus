<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 12/12/11
 * Time: 1:43 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Interfaces
{
    interface InterfaceValidator extends InterfaceVo
    {
        /**
         * @abstract
         * @return array
         */
        public function getValidationData();

        /**
         * @abstract
         *
         * @param object $validationData
         */
        public function setValidationData(\object $validationData);
    }
}