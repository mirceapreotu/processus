<?php
/**
 * Lib_Fnmatch_Filter Class
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
 * Lib_Fnmatch_Filter
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
 
class Lib_Fnmatch_Filter
{

    const FLAG_IGNORE_CASE = FNM_CASEFOLD;


    /**
     * @var Lib_Fnmatch_FilterItemList
     */
    protected $_whitelist;

    /**
     * @var Lib_Fnmatch_FilterItemList
     */
    protected $_blacklist;


    /**
     * @var int|null
     */
    protected $_flags;


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



    /**
     * @return Lib_Fnmatch_FilterItemList
     */
    public function getWhitelist()
    {
        if (($this->_whitelist instanceof Lib_Fnmatch_FilterItemList)!==true) {
            $this->_whitelist = new Lib_Fnmatch_FilterItemList();
        }

        return $this->_whitelist;
    }

    /**
     * @throws Exception
     * @param  array|Zend_Config Lib_Fnmatch_FilterItemList $list
     * @return
     */
    public function setWhitelist($list)
    {
        if ($list === null) {
            $this->_whitelist = null;
            return;
        }

        if ($list instanceof Zend_Config) {
            /**
             * @var Zend_Config $list
             */
            $list = $list->toArray();
        }

        if (is_array($list)) {
            $_list = new Lib_Fnmatch_FilterItemList();
            try {
                $_list->setItems($list);
            }catch(Exception $error) {
                throw new Exception(
                    "Invalid parameter 'list' at ".__METHOD__
                    ." error: ".$error->getMessage()
                );
            }
            $this->_whitelist = $_list;
        }
                
    }


        /**
     * @return Lib_Fnmatch_FilterItemList
     */
    public function getBlacklist()
    {
        if (($this->_blacklist instanceof Lib_Fnmatch_FilterItemList)!==true) {
            $this->_blacklist = new Lib_Fnmatch_FilterItemList();
        }

        return $this->_blacklist;
    }


    /**
     * @throws Exception
     * @param  array|Zend_Config Lib_Fnmatch_FilterItemList $list
     * @return
     */
    public function setBlacklist($list)
    {
        if ($list === null) {
            $this->_blacklist = null;
            return;
        }

        if ($list instanceof Zend_Config) {
            /**
             * @var Zend_Config $list
             */
            $list = $list->toArray();
        }


        if (is_array($list)) {
            $_list = new Lib_Fnmatch_FilterItemList();
            try {
                $_list->setItems($list);
            }catch(Exception $error) {
                throw new Exception(
                    "Invalid parameter 'list' at ".__METHOD__
                    ." error: ".$error->getMessage()
                );
            }
            $this->_blacklist = $_list;



        }

    }



    /**
     * @param  string $string
     * @param  int|null $flags
     * @return bool
     */
    public function isWhitelisted($string, $flags)
    {
        if ($flags === null) {
            if ($this->hasFlags()) {
                $flags = $this->getFlags();
            }
        }
        $list = $this->getWhitelist();
        $isListed = $list->matchOne($string, $flags);

        return (bool)($isListed===true);
    }

    /**
     * @param  string $string
     * @param  int|null $flags
     * @return bool
     */
    public function isBlacklisted($string, $flags)
    {
        if ($flags === null) {

            if ($this->hasFlags()) {
                $flags = $this->getFlags();
            }
        }
        $list = $this->getBlacklist();
        $isListed = $list->matchOne($string, $flags);

        return (bool)($isListed===true);
    }




    /**
     * @throws Exception
     * @param  array|Zend_Config|null $config
     * @return
     */
    public function applyConfig($config)
    {
        if (is_array($config)) {
            $config = new Zend_Config($config);
        }

        if ($config === null) {
            $this->setFlags(null);
            $this->setWhitelist(null);
            $this->setBlacklist(null);
            return;
        }

        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid parameter 'config' at ".__METHOD__);
        }

        /**
         * @var Zend_Config $config
         */

        $flags = $config->flags;
        $whitelist = $config->whitelist;
        $blacklist = $config->blacklist;

        $this->setFlags($flags);
        $this->setWhitelist($whitelist);
        $this->setBlacklist($blacklist);

    }

}
