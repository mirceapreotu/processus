<?php
/**
 * Lib_Fnmatch_FilterItem Class
 *
 * @EXPERIMENTAL
 * 
 * @package Lib_Fnmatch
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Fnmatch_FilterItem
 *
 *
 * @package Lib_Fnmatch
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
 
class Lib_Fnmatch_FilterItem
{

    const FLAG_IGNORE_CASE = FNM_CASEFOLD;


    /**
     * @var string|null
     */
    protected $_pattern;

    /**
     * @var int|null
     */
    protected $_flags;


    /**
     * @param  string $pattern
     * @return void
     */
    public function setPattern($pattern)
    {
        if ($pattern === null) {
            $this->_pattern = null;
            return;
        }

        if (is_string($pattern)) {
            $this->_pattern = $pattern;
            return;
        }

        if (is_numeric($pattern)) {
            $this->_pattern = "".$pattern;
            return;
        }

        throw new Exception("Invalid parameter 'pattern' at ".__METHOD__);

    }


    /**
     * @return string|null
     */
    public function getPattern()
    {
        return $this->_pattern;
    }

    /**
     * @return bool
     */
    public function hasPattern()
    {
        return (bool)is_string($this->_pattern);
    }


    /**
     * @throws Exception
     * @param  int|null $flags
     * @return
     */
    public function setFlags($flags)
    {
        if ($flags === null) {
            $this->_flags = null;
            return;
        }
        if (is_int($flags)) {
            $this->_flags = $flags;
            return;
        }
        throw new Exception("Invalid parameter 'flags' at ".__METHOD__);
    }

    /**
     * @return int|null
     */
    public function getFlags()
    {
        return $this->_flags;
    }

    /**
     * @return bool
     */
    public function hasFlags()
    {
        $flags = $this->_flags;
        if (is_int($flags) && ($flags>0)) {
            return true;
        }
        return false;
    }

}
