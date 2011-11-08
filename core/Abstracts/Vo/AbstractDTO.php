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
    use Processus\Interfaces\InterfaceDto;

	abstract class AbstractDTO extends AbstractVO implements InterfaceDto
    {
        
        /**
         * @see Processus\Abstracts\Vo.AbstractVO::setData()
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
            
            foreach ($this->getMapping() as $item => $key) {
                $exportData[$key] = $this->getValueByKey($item);
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