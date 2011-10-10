<?php

/**
 * @EXPERIMENTAL
 * Lib_Io_JsonRpc_Filter_AbstractFilter
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc_Filter
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * @EXPERIMENTAL
 * Lib_Io_JsonRpc_Filter_AbstractFilter
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc_Filter
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Io_JsonRpc_Filter_AbstractFilter
{



    /**
     * @param  string $value
     * @param  string|array $pattern
     * @param null|int $flags
     * @return bool
     */
    public function match($value, $pattern, $flags = null)
    {
        $result = false;
        if (is_int($flags) !== true) {
            $flags = FNM_CASEFOLD;
        }

        if (is_string($value) !== true) {
            return $result;
        }
        $value = trim($value);
        if (strlen($value)<1) {
            return $result;
        }

        $_patternList = array();
        if (is_string($pattern)) {
            $_patternList[] = $pattern;
        }
        if (is_array($pattern)) {
            $_patternList = (array)$pattern;
        }

        $patternList = (array)$_patternList;
        if (count($patternList)<1) {
            return $result;
        }


        foreach ($patternList as $pattern) {

            if (is_string($pattern) !== true) {
                continue;
            }
            $pattern = trim($pattern);
            if (strlen($pattern) <1) {
                continue;
            }

            $found = fnmatch($pattern, $value, $flags);
            if ($found === true) {
                $result = true;
                return $result;
            }
        }

        return $result;
    }



    /**
     * @param  array $sourceList
     * @param  array $mergeList
     * @return array
     */
    public function addToList($sourceList, $mergeList)
    {
        $result = array();
        if ((is_array($sourceList)) && (is_array($mergeList))) {
            $result = array_merge($sourceList, $mergeList);
            $result = array_unique($result);
            return (array)$result;
        }

        if (is_array($sourceList)) {
            $result = $sourceList;
            return $result;
        }
        if (is_array($mergeList)) {
            $result = $mergeList;
            return $result;
        }
        return $result;
    }


    /**
     * @param  array $sourceList
     * @param  array $removeList
     * @return array
     */
    public function removeFromList($sourceList, $removeList)
    {

        $result = array();
        if ((is_array($sourceList)) && (is_array($removeList))) {
            foreach($sourceList as $item) {
                if ((in_array($item, $removeList, true)) !== true) {
                    $result[] = $item;
                }
            }

            return $result;
        }

        return $result;
    }
    
    
}


