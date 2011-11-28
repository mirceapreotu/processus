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
        public function startTimeTrack();
        public function endTimeTrack();
        public function setMethod(\string $method);
        public function getMethod();
        public function setLine(\string $line);
        public function getLine();
        public function setComment(\string $comment);
        public function getComment();
        public function setClass(\string $class);
        public function getClass();
    }
}