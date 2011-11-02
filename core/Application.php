<?php

namespace Processus
{
    use Processus\Lib\Bo\UserBo;
    
    use Processus\Lib\Profiler\Profiler;

    class Application
    {

        /**
         * @var Application
         */
        private static $_instance;

        /**
         * @var Profiler
         */
        private $_profiler;

        /**
         * @var UserBo
         */
        private $_userBo;

        // #########################################################
        

        /**
         * @return \Processus\Application
         */
        public static function getInstance()
        {
            if (self::$_instance instanceof self !== TRUE) {
                self::$_instance = new Application();
                self::$_instance->init();
            }
            
            return self::$_instance;
        }

        // #########################################################
        

        /**
         * @return \Processus\Lib\Bo\UserBo
         */
        public function getUserBo()
        {
            if (! $this->_userBo) {
                $this->_userBo = new UserBo();
            }
            return $this->_userBo;
        }

        // #########################################################
        

        /**
         * @return \Processus\Lib\Profiler\Profiler
         */
        public function getProfiler()
        {
            if (! $this->$_profiler) {
                $this->$_profiler = new Profiler();
            }
            
            return $this->$_profiler;
        }
    }
}

?>