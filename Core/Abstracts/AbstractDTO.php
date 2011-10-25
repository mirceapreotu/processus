<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/16/11
     * Time: 1:50 PM
     * To change this template use File | Settings | File Templates.
     */
    abstract class Core_Abstracts_AbstractDTO extends Core_Abstracts_AbstractVO
    {
        /**
         * @return array
         */
        public function export()
        {
            $exportData = array();
            foreach ($this->getMapping() as $item => $key)
            {
                $exportData[$key] = $this->getValueByKey($item);
            }

            return $exportData;
        }

        /**
         * @abstract
         */
        abstract protected function getMapping();
    }
