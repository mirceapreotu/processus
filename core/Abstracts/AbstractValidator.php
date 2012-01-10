<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 12/6/11
 * Time: 12:49 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Abstracts
{
    abstract class AbstractValidator extends \Processus\Abstracts\Vo\AbstractVO implements \Processus\Interfaces\InterfaceValidator
    {
        /**
         * @return array|mixed
         */
        public function getValidationData()
        {
            return $this->getValueByKey('validationData');
        }

        /**
         * @param object $validationData
         *
         * @return \Processus\Abstracts\AbstractValidator
         */
        public function setValidationData(\object $validationData)
        {
            $this->setValueByKey('validationData', $validationData);
            return $this;
        }

        /**
         * @abstract
         * @return boolean
         */
        abstract public function isValid();
    }
}