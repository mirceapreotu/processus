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
         * @return object
         */
        public function export();
    }
}

?>