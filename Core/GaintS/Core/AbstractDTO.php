<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/16/11
     * Time: 1:50 PM
     * To change this template use File | Settings | File Templates.
     */
    abstract class App_GaintS_Core_AbstractDTO extends App_GaintS_Core_AbstractVO
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
