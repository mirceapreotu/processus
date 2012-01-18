<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/28/11
 * Time: 5:29 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Profiler
{
    interface IProcessusDebugProfilerVo extends \Processus\Interfaces\InterfaceVo
    {
        /**
         * @abstract
         * @return \Processus\Lib\Profiler\IProcessusDebugProfilerVo
         */
        public function startTimeTrack();

        /**
         * @abstract
         * @return \Processus\Lib\Profiler\IProcessusDebugProfilerVo
         */
        public function endTimeTrack();

        /**
         * @abstract
         *
         * @param string $method
         *
         * @return \Processus\Lib\Profiler\IProcessusDebugProfilerVo
         */
        public function setMethod(\string $method);

        /**
         * @abstract
         * @return string
         */
        public function getMethod();

        /**
         * @abstract
         *
         * @param string $line
         */
        public function setLine(\string $line);

        /**
         * @abstract
         * @return int
         */
        public function getLine();

        /**
         * @abstract
         *
         * @param string $comment
         *
         * @return \Processus\Lib\Profiler\IProcessusDebugProfilerVo
         */
        public function setComment(\string $comment);

        /**
         * @abstract
         * @return string
         */
        public function getComment();

        /**
         * @abstract
         *
         * @param string $class
         *
         * @return \Processus\Lib\Profiler\IProcessusDebugProfilerVo
         */
        public function setClass(\string $class);

        /**
         * @abstract
         * @return string
         */
        public function getClass();
    }
}