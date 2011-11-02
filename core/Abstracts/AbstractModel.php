<?php

namespace Processus\Abstracts
{

    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 8/15/11
     * Time: 12:02 PM
     * To change this template use File | Settings | File Templates.
     */
    use Processus\Abstracts\Manager\AbstractManager;

	abstract class AbstractModel extends AbstractManager
    {

        protected $_data = array();

        /**
         * @param unknown_type $item
         * @return \Processus\Abstracts\AbstractModel
         */
        public function addItem ($item)
        {
            $_data[] = $item;
            return $this;
        }

        /**
         * @param unknown_type $id
         * @return \Processus\Abstracts\AbstractModel
         */
        public function removeItemById ($id)
        {
            unset($this->_data[$id]);
            return $this;
        }

        /**
         * @return mixed
         */
        public function fetchAll ()
        {
            return $this->_data;
        }

        /**
         * @param $id
         * @return mixed
         */
        public function fetchOneById ($id)
        {
            return $this->_data[$id];
        }
    }
}

?>