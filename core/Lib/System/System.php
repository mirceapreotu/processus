<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 12/1/11
 * Time: 4:17 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\System
{
    class System
    {

        /**
         * @var \Processus\Lib\System\System
         */
        private static $_Instance;

        /**
         * @return \Processus\Lib\System\System
         */
        public static function getInstance()
        {
            if (!self::$_Instance) {
                self::$_Instance = new System();
            }

            return self::$_Instance;
        }

        /**
         * @return string
         */
        public function getMemoryPeakUsage()
        {
            return number_format(memory_get_peak_usage() / 1024, 2);
        }

        /**
         * @return string
         */
        public function getMemoryUsage()
        {
            return number_format(memory_get_usage() / 1024, 2);
        }
    }
}