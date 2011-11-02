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
    abstract class AbstractVO
    {

        /** @var array */
        protected $_data = array();

        /**
         * @param array|mixed $data
         * @return \Processus\Abstracts\Vo\AbstractVO
         */
        public function setData($data)
        {
            /** @var $_data object */
            $this->_data = (array) $data;
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
            return $this->_data[$key];
        }

        /**
         * @return object
         */
        public function getData()
        {
            if (is_null($this->_data)) {
                throw new \Exception("data is null");
            }
            return $this->_data;
        }
    
    }
}

?>