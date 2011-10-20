<?php

/**
 * Lib_Profiler_Profiler
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Profiler
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Profiler
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Profiler
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */



class Lib_Profiler_Profiler
{



    protected $_id;
    protected $_isStarted;
    protected $_isStopped;

    protected $_startTimestamp;
    protected $_startDate;

    protected $_startMemoryLimit;
    protected $_startMemoryUsage;
    protected $_startMemoryUsageReal;
    protected $_startMemoryPeakUsage;
    protected $_startMemoryPeakUsageReal;

    

    protected $_stopTimestamp;
    protected $_stopDate;

    protected $_stopMemoryLimit;
    protected $_stopMemoryUsage;
    protected $_stopMemoryUsageReal;
    protected $_stopMemoryPeakUsage;
    protected $_stopMemoryPeakUsageReal;



    protected $_children;

    public function getChildren()
    {
        if (is_array($this->_children)!==true) {
            $this->_children = array();
        }
        return $this->_children;
    }


    /**
     * @param Lib_Profiler_Profiler $value
     * @return int
     */
    public function addChild(Lib_Profiler_Profiler $value)
    {
        $children = $this->getChildren();
        $children[] = $value;
        $this->_children = $children;
        return (count($children)-1);
    }

    /**
     * @param null|string $id
     * @param bool|null $autoStart
     * @return Lib_Profiler_Profiler
     */
    public function createAndAddChild($id=null, $autoStart=false)
    {
        $child = new self($id);
        $children = $this->getChildren();
        $children[] = $child;
        $this->_children = $children;

        if ($autoStart === true) {
            $child->start();
        }

        return $child;
    }


    public function setId($value)
    {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }

    public function getStartTimestamp()
    {
        return $this->_startTimestamp;
    }
    public function getStartDate()
    {
        return $this->_startDate;
    }
    public function getStopTimestamp()
    {
        return $this->_stopTimestamp;
    }
    public function getStopDate()
    {
        return $this->_stopDate;
    }


    public function getStartMemoryLimit()
    {
        return $this->_startMemoryLimit;
    }
    public function getStartMemoryUsage()
    {
        return $this->_startMemoryUsage;
    }
    public function getStartMemoryUsageReal()
    {
        return $this->_startMemoryUsageReal;
    }
    public function getStartMemoryPeakUsage()
    {
        return $this->_startMemoryPeakUsage;
    }
    public function getStartMemoryPeakUsageReal()
    {
        return $this->_startMemoryPeakUsageReal;
    }


    public function getStopMemoryLimit()
    {
        return $this->_stopMemoryLimit;
    }
    public function getStopMemoryUsage()
    {
        return $this->_stopMemoryUsage;
    }
    public function getStopMemoryUsageReal()
    {
        return $this->_stopMemoryUsageReal;
    }
    public function getStopMemoryPeakUsage()
    {
        return $this->_stopMemoryPeakUsage;
    }
    public function getStopMemoryPeakUsageReal()
    {
        return $this->_stopMemoryPeakUsageReal;
    }


    public function __construct($id=null)
    {
        if ($id !== null) {
            $this->setId($id);
        }
    }


    public function isStarted()
    {
        return (bool)($this->_isStarted===true);
    }
    public function isStopped()
    {
        return (bool)($this->_isStopped===true);
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return (bool)(
                    ($this->isStarted()===true)
                    && ($this->isStopped() !== true)
        );
    }

    public function isComplete()
    {
        return (bool)(
                ($this->isStarted()===true)
                && ($this->isStopped()===true)
        );
    }


    public function reset()
    {
        $this->_isStarted = false;
        $this->_isStopped = false;
        $this->_startDate = null;
        $this->_startTimestamp = null;
        $this->_stopDate = null;
        $this->_stopTimestamp = null;

        $this->_startMemoryLimit = null;
        $this->_startMemoryUsage = null;
        $this->_startMemoryUsageReal = null;
        $this->_startMemoryPeakUsage = null;
        $this->_startMemoryPeakUsageReal = null;

        $this->_stopMemoryLimit = null;
        $this->_stopMemoryUsage = null;
        $this->_stopMemoryUsageReal = null;
        $this->_stopMemoryPeakUsage = null;
        $this->_stopMemoryPeakUsageReal = null;
    }

    public function start()
    {
        $this->reset();

        $this->_startMemoryLimit = ini_get("memory_limit");
        $this->_startMemoryUsage = memory_get_usage(false);
        $this->_startMemoryUsageReal = memory_get_usage(true);
        $this->_startMemoryPeakUsage = memory_get_peak_usage(false);
        $this->_startMemoryPeakUsageReal = memory_get_peak_usage(true);



        $this->_startDate = date(
                "Y-m-d H:i:s", time()
            );
        $this->_isStarted = true;
        $this->_startTimestamp = microtime(true);
    }

    public function stop()
    {
        $this->_stopTimestamp = microtime(true);
        $this->_stopDate = date(
                "Y-m-d H:i:s", time()
            );
        $this->_stopMemoryLimit = ini_get("memory_limit");
        $this->_stopMemoryUsage = memory_get_usage(false);
        $this->_stopMemoryUsageReal = memory_get_usage(true);
        $this->_stopMemoryPeakUsage = memory_get_peak_usage(false);
        $this->_stopMemoryPeakUsageReal = memory_get_peak_usage(true);

        $this->_isStopped = true;
    }


    public function getDuration()
    {
        $result = null;

        $startTimestamp = $this->getStartTimestamp();
        if ($startTimestamp === null) {
            return $result;
        }
        $stopTimestamp = $this->getStopTimestamp();
        if ($stopTimestamp === null) {
            $startTimestamp = microtime(true);
        }

        $duration = (float)$stopTimestamp - (float)$startTimestamp;
        return $duration;
    }

    public function getDurationAsString($precision = null)
    {
        if (((is_int($precision)) && ($precision>=0))!==true) {
            $precision = 4;
        }
        $result = "";
        $duration = $this->getDuration();
        if ($duration === null) {
            return $result;
        }

        return "".round(
                ($duration), $precision
            );
    }


    /**
     * @param bool $exportChildren
     * @return array
     */
    public function stopAndExportAsArray($exportChildren = false)
    {

        $this->stop();
        $currentRecursionDepth = 0;
        $exportChildren = (bool)($exportChildren===true);
        return $this->_toArray($exportChildren, $currentRecursionDepth);

    }

    /**
     * @param bool $exportChildren
     * @return array
     */
    public function toArray($exportChildren = false)
    {
        $currentRecursionDepth = 0;
        $exportChildren = (bool)($exportChildren===true);
        return $this->_toArray($exportChildren, $currentRecursionDepth);
    }

    /**
     * @throws Exception
     * @param  bool $exportChildren
     * @param  int $currentRecursionDepth
     * @return array
     */
    protected function _toArray($exportChildren, $currentRecursionDepth)
    {
        $maxRecursionDepth = 1024;
        $currentRecursionDepth = (int)$currentRecursionDepth;

        $result = array(
            "id" => $this->getId(),
            "time" => array(
                "duration" => array(
                    "value" => $this->getDuration(),
                    "text" => $this->getDurationAsString(null),
                ),
                "start" => array(
                    "value" => $this->getStartTimestamp(),
                    "text" => $this->getStartDate(),
                ),
                "stop" => array(
                    "value" => $this->getStopTimestamp(),
                    "text" => $this->getStopDate(),
                ),
            ),
            "memory" => array(

                "start" => array(
                    "usagePeakReal" => array(
                        "value" => $this->getStartMemoryPeakUsageReal(),
                        "text" => $this->_memoryToString(
                            $this->getStartMemoryPeakUsageReal()
                        ),
                    ),
                    "usagePeak" => array(
                        "value" => $this->getStartMemoryPeakUsage(),
                        "text" => $this->_memoryToString(
                            $this->getStartMemoryPeakUsage()
                        ),
                    ),
                    "usageReal" => array(
                        "value" => $this->getStartMemoryUsageReal(),
                        "text" => $this->_memoryToString(
                            $this->getStartMemoryUsageReal()
                        ),
                    ),
                    "usage" => array(
                        "value" => $this->getStartMemoryUsage(),
                        "text" => $this->_memoryToString(
                            $this->getStartMemoryUsage()
                        ),
                    ),
                    "limit" => array(
                        "value" => $this->getStartMemoryLimit(),
                        "text" => $this->getStartMemoryLimit(),
                    ),


                ),


                "stop" => array(
                    "usagePeakReal" => array(
                        "value" => $this->getStopMemoryPeakUsageReal(),
                        "text" => $this->_memoryToString(
                            $this->getStopMemoryPeakUsageReal()
                        ),
                    ),
                    "usagePeak" => array(
                        "value" => $this->getStopMemoryPeakUsage(),
                        "text" => $this->_memoryToString(
                            $this->getStopMemoryPeakUsage()
                        ),
                    ),
                     "usage" => array(
                         "value" => $this->getStopMemoryUsage(),
                         "text" => $this->_memoryToString(
                             $this->getStopMemoryUsage()
                         ),
                     ),
                     "usageReal" => array(
                         "value" => $this->getStopMemoryUsageReal(),
                         "text" => $this->_memoryToString(
                             $this->getStopMemoryUsageReal()
                         ),
                     ),
                     "limit" => array(
                         "value" => $this->getStopMemoryLimit(),
                         "text" => $this->getStopMemoryLimit(),
                     ),

                 ),

                "delta" => array(
                    "usagePeakReal" => array(
                        "value" => $this->getStopMemoryPeakUsageReal()
                                   -$this->getStartMemoryPeakUsageReal(),
                        "text" => $this->_memoryToString(
                            $this->getStopMemoryPeakUsageReal()
                            -$this->getStartMemoryPeakUsageReal()
                        ),
                    )
                ),

            ),

            "isComplete" => $this->isComplete(),
            "isRunning" => $this->isRunning(),
            "isStarted" => $this->isStarted(),
            "isStopped" => $this->isStopped(),
        );

        if (Lib_Utils_String::isEmpty(
                $result["time"]["duration"]["text"])!==true) {
            $result["time"]["duration"]["text"] .= " s";
        }

        if ($exportChildren === true) {
            if ($currentRecursionDepth > $maxRecursionDepth) {
                throw new Exception(
                    "Max recursion depth exceeded at ".__METHOD__
                );
            }

            $result["children"] = array();
            $childs = $this->getChildren();
            foreach($childs as $child) {
                if (($child instanceof self)!==true) {
                    continue;
                }
                $currentRecursionDepth++;
                $item = $child->toArray(
                    $exportChildren, $currentRecursionDepth
                );
                $result["children"][] = $item;
            }

        }


        return $result;
    }


    protected function _memoryToString($value)
    {
        $size = $value;
        if (((int)$value)===0) {
            return "0 bytes";
        }

        $rnd = 0;
        $i = 0;
        try {
            $rnd = round(
                   $size/pow(1024,($i=floor(log($size,1024)))),2
               );
        }catch(Exception $e) {

        }


        $units = array('bytes','kb','mb','gb','tb','pb');
        $unit = Lib_Utils_Array::getProperty($units, $i);
        return $rnd.' '.$unit;
    }



    /**
     * @return null|string
     */
    public function getMemoryManagerSegSize()
    {
        $value = getenv('ZEND_MM_SEG_SIZE');
        if ($value === false) {
            return null;
        }
        return $value;
    }
    /**
     * @return null|string
     */
    public function getMemoryManagerMemType()
    {
        $value = getenv('ZEND_MM_MEM_TYPE');
        if ($value === false) {
            return null;
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getMemoryManagerStats()
    {
        // @see: http://www.ibm.com/developerworks/opensource/library/os-php-v521/
        // @see: http://julien-pauli.developpez.com/tutoriels/php/internals/zend-memory-manager/

        $result = array(
            "memType" => null,
            "segSize" => null,
            "segCount" => null,
            "usageReal" => null,
            "usage" => null,
            "usagePeakReal" => null,
            "usagePeak" => null,
        );

        $memType = $this->getMemoryManagerMemType();
        $segSize = $this->getMemoryManagerSegSize();
        $result["segSize"] = $segSize;
        $result["memType"] = $memType;
        if ($segSize!==null) {
            try {
                $segmentsCount = (memory_get_usage(true)/$segSize);
                $result["segCount"] = $segmentsCount;
            } catch(Exception $e) {

            }
        }

        $result["usageReal"] = memory_get_usage(true);
        $result["usage"] = memory_get_usage(false);
        $result["usagePeakReal"] = memory_get_peak_usage(true);
        $result["usagePeak"] = memory_get_peak_usage(false);
        return $result;
    }
}
