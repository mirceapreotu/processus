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
         * @var bool
         */
        private $_isEnded = FALSE;

        /**
         * @var \Processus\Lib\Profiler\ProcessusProfiler
         */
        private static $_Instance;

        /**
         * @static
         * @return \Processus\Lib\Profiler\ProcessusProfiler
         */
        public static function getInstance()
        {
            if (!self::$_Instance) {
                self::$_Instance = new ProcessusProfiler();
            }

            return self::$_Instance;
        }

        /**
         * @param \Processus\Lib\Profiler\IProcessusDebugProfilerVo $profilerInfo
         *
         * @return \Processus\Lib\Profiler\ProcessusProfiler
         */
        public function addDebugInfo(IProcessusDebugProfilerVo $profilerInfo)
        {
            $this->_debugProfilerStack[] = $profilerInfo;
            return $this;
        }

        /**
         * @return int
         */
        public function applicationProfilerStart()
        {
            if (!$this->_startTime)
            {
                $this->_startTime = microtime_float();
            }

            return $this->_startTime;
        }

        /**
         * @return \Processus\Lib\Profiler\ProcessusProfiler
         */
        public function applicationProfilerEnd()
        {
            $endTime = microtime_float();
            $this->_appDuration = $endTime - $this->_startTime;
            $this->_isEnded     = true;
            return $endTime;
        }

        /**
         * @return int
         */
        public function applicationDuration()
        {
            if ($this->_isEnded == FALSE) {
                $this->applicationProfilerEnd();
            }

            return $this->_appDuration;
        }

        /**
         * @return mixed
         */
        public function getProfilerStack()
        {
            $rawData = array();
            /** @var $debug \Processus\Abstracts\Vo\AbstractVO */
            foreach($this->_debugProfilerStack as $debug)
            {
                $data = $debug->getData();
                $rawData[] = $data;
            }
            return $rawData;
        }
    }
}
?>