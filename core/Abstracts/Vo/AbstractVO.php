<?php

namespace Processus\Abstracts\Vo
{

    abstract class AbstractVO extends \Processus\Abstracts\AbstractClass implements \Processus\Interfaces\InterfaceVo
    {
        /**
         * @var object
         */
        protected $_data;

        /**
         * @return array|mixed
         */
        public function getId()
        {
            return $this->getValueByKey('id');
        }

        /**
         * @param array $data
         *
         * @return \Processus\Abstracts\Vo\AbstractVO
         */
        public function setData($data)
        {
            /** @var object $_data */
            $this->_data = prosc_array_to_object($data);
            return $this;
        }

        /**
         * @param string $key
         * @param mixed  $value
         *
         * @return \Processus\Abstracts\Vo\AbstractVO
         */
        public function setValueByKey(\string $key, \object $value)
        {
            $this->_data->$key = $value;
            return $this;
        }

        /**
         * @param string $key
         *
         * @return mixed | array
         */
        public function getValueByKey(\string $key)
        {
            if (is_null($this->_data->$key)) {
                return NULL;
            }
            else {
                return $this->_data->$key;
            }

        }

        /**
         * @return object
         */
        public function getData()
        {
            try {
                return $this->_data;
            }
            catch (\Exception $error)
            {
                return null;
            }
        }

        /**
         * @param \Processus\Interfaces\InterfaceDto $dto
         *
         * @return \Processus\Interfaces\InterfaceDto
         */
        public function setDto(\Processus\Interfaces\InterfaceDto $dto)
        {
            $dto->setData($this->getData());
            return $dto;
        }

    }
}

?>