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
         * @return ProcessusProfiler
         */
        public function profile()
        {
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
                    "appDuration" => $this->_appDuration,
                    "profilerStack" => ProfilerStack::getProfilerStackData(),
            );
        }
    }
}
?>