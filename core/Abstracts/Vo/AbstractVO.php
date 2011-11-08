<?php

namespace Processus\Abstracts\Vo
{
    
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 7/8/11
     * Time: 11:38 AM
     * To change this template use File | Settings | File Templates.
     */
    use Zend\Pdf\BinaryParser\DataSource\String;

	use Processus\Abstracts\AbstractClass;

    abstract class AbstractVO extends AbstractClass
    {

        /**
         * @var object
         */
        protected $_data;

        /**
         * @param array $data
         * @return \Processus\Abstracts\Vo\AbstractVO
         */
        public function setData($data)
        {
            /** @var object $_data */
            $this->_data = array_to_object($data);
            return $this;
        }

        /**
         * @param string $key
         * @param mixed $value
         * @return \Processus\Abstracts\Vo\AbstractVO
         */
        public function setValueByKey(string $key, mixed $value)
        {
            $this->_data->$key = $value;
            return $this;
        }

        /**
         * @param string $key
         * @return mixed | array
         */
        public function getValueByKey(string $key)
        {
            $data = $this->getData();
            return $data->$key;
        }

        /**
         * @return object
         */
        public function getData()
        {
            return $this->_data;
        }
    
    }
}

?>