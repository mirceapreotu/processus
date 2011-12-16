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
         * @return AbstractValidator
         */
        public function setValidationData(\object $validationData)
        {
            $this->setValueByKey('validationData', $validationData);
            return $this;
        }

        /**
         * @return boolean
         */
        public function isValid()
        {
            throw new \Processus\Exceptions\NotImplementedException('Method not implemented!', 'PRC-1001', '', __FILE__, __LINE__);
        }
    }
}