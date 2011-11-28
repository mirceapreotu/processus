<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Profiler
{

    class ProcessusProfiler
    {

        /**
         * @var int
         */
        private $_startTime;

        /**
         * @var int
         */
        private $_appDuration;

        /**
         * @var array
         */
        private $_debugProfilerStack = array();

        /**
         * @var \Processus\Lib\Profiler\ProcessusProfiler
         */
        private static $_Instance;

        /**
         * @static
         * @return ProcessusProfiler
         */
        public static function getInstance()
        {
            if (!self::$_Instance)
            {
                self::$_Instance = new ProcessusProfiler();
            }

            return self::$_Instance;
        }

        /**
         * @param \Processus\Lib\Profiler\IProcessusDebugProfilerVo $profilerInfo
         *
         * @return ProcessusProfiler
         */
        public function addDebugInfo(IProcessusDebugProfilerVo $profilerInfo)
        {
            $this->_debugProfilerStack[] = $profilerInfo;
            return $this;
        }

        /**
         * @return ProcessusProfiler
         */
        public function applicationProfilerStart()
        {
            $this->_startTime = time();
            return $this;
        }

        /**
         * @return ProcessusProfiler
         */
        public function applicationProfilerEnd()
        {
            $this->_appDuration = time() - $this->_startTime;
            return $this;
        }

        /**
         * @return array
         */
        public function getDefaultInformation()
        {
            return array(
                "appDuration"   => $this->_appDuration,
                "profilerStack" => ProfilerStack::getProfilerStackData(),
            );
        }
    }
}
?>