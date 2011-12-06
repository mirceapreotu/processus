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
    abstract class AbstractValidator extends \Processus\Abstracts\Vo\AbstractVO
    {
        abstract public function validate();

        /**
         * @return array|mixed
         */
        protected function getValidationData()
        {
            return $this->getValueByKey('validationData');
        }

        /**
         * @param object $validationData
         *
         * @return AbstractValidator
         */
        public function setValidationData(\object $validationData)
        {
            $this->setValueByKey('validationData', $validationData);
            return $this;
        }
    }
}