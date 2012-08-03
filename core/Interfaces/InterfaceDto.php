<?php

/**
 * @author fightbulc
 *
 *
 */
namespace Processus\Interfaces
{
    interface InterfaceDto
    {
        /**
         * @abstract
         *
         * @param $data
         */
        public function setData($data);

        /**
         * @abstract
         *
         * @param $rawData
         */
        public function export($rawData = null);
    }
}

?>