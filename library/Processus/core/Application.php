<?php

    namespace Processus
    {

        /**
         *
         */
        class Application
        {

            /**
             * @var App_Application
             */
            private static $_instance;

            // #########################################################


            /**
             * @static
             * @return App_Application
             */
            public static function getInstance()
            {
                if (self::$_instance instanceof self !== TRUE) {
                    self::$_instance = new self();
                    self::$_instance->init();
                }

                return self::$_instance;
            }

            // #########################################################


            /**
             * @return void
             */
            public function init()
            {
            }

            // #########################################################


            /**
             * @var Lib_Profiler_Profiler
             */
            protected $_profiler;

            /**
             * @var Lib_Profiler_Profiler
             */
            protected $_profilerRoot;

            /**
             * @return Lib_Profiler_Profiler
             */
            public function getProfilerRoot()
            {
                if ($this->_profilerRoot instanceof Lib_Profiler_Profiler !== TRUE) {
                    $profiler = new Lib_Profiler_Profiler(__METHOD__);
                    $this->_profilerRoot = $profiler;

                    //$profiler->start();
                    //$profiler->stop();
                }

                return $this->_profilerRoot;
            }

            // #########################################################


            /**
             * @return Lib_Profiler_Profiler
             */
            public function getProfiler()
            {
                if ($this->_profiler instanceof Lib_Profiler_Profiler !== TRUE) {
                    $this->_profiler = $this->getProfilerRoot();
                }

                return $this->_profiler;
            }

            // #########################################################


            public function setProfiler(Lib_Profiler_Profiler $value)
            {
                $this->_profiler = $value;
            }

            // #########################################################


            /**
             * @param  string $method
             * @return Lib_Profiler_Profiler
             */
            public function profileMethodStart($method)
            {
                $profiler = $this->getProfiler()->createAndAddChild($method, TRUE);
                $this->setProfiler($profiler);
                return $profiler;
            }
        }
    }

?>