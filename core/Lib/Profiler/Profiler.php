<?php

/** 
 * @author francis
 * 
 * 
 */

namespace Processus\Lib\Profiler
{

    class Profiler
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
         * @return \Processus\Lib\Profiler\Profiler
         */
        public function profile()
        {
            return $this;
        }

        /**
         * @return \Processus\Lib\Profiler\Profiler
         */
        public function applicationProfilerStart()
        {
            $this->_startTime = time();
            return $this;
        }

        /**
         * @return \Processus\Lib\Profiler\Profiler
         */
        public function applicationProfilerEnd()
        {
            $this->_appDuration = time() - $this->_startTime;
            return $this;
        }

        /**
         * @return multitype:number
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