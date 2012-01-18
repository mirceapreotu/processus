<?php

namespace Processus\Abstracts\Vo
{

    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/16/11
     * Time: 1:50 PM
     * To change this template use File | Settings | File Templates.
     */
    abstract class AbstractDTO extends AbstractVO implements \Processus\Interfaces\InterfaceDto
    {

        /**
         * @param $data
         *
         * @return AbstractDTO
         */
        public function setData($data)
        {
            parent::setData($data);
            return $this;
        }

        /**
         * @return array
         */
        public function export()
        {
            $exportData = array();

            foreach ($this->getMapping() as $key => $item) {

                $data = $this->getValueByKey($item['match']);

                if (is_null($data)) {
                    $data = $item['default'];
                }

                $exportData[$key] = $data;
            }

            return $exportData;
        }

        /**
         * @abstract
         */
        abstract protected function getMapping();
    }
}

?>