<?php
/**
 * Lib_Fnmatch_FilterItemList Class
 * @EXPERIMENTAL
 * @package Lib_Fnmatch
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Fnmatch_FilterItemList
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
 
class Lib_Fnmatch_FilterItemList
{

    const FLAG_IGNORE_CASE = FNM_CASEFOLD;
    

    /**
     * @var array|null
     */
    protected $_items;


    /**
     * @return array
     */
    public function getItems()
    {
        if (is_array($this->_items)!==true) {
            $this->_items = array();
        }

        return $this->_items;
    }


    /**
     * @throws Exception
     * @param  array|null $items
     * @return
     */
    public function setItems($items)
    {
        if (is_null($items)) {
            $this->_items = null;
            return;
        }

        if (is_array($items)!==true) {
            throw new Exception("Invalid parameter 'items' at ".__METHOD__);

        }

        try {
            $this->validateItems($items);

            $this->_items = null;
            $this->addItems($items);

        }catch (Exception $error) {
            throw new Exception(
                "Invalid parameter 'items' at ".__METHOD__
                ." error: ".$error->getMessage()
            );
        }

    }


    /**
     * @throws Exception
     * @param  array $items
     * @return void
     */
    public function addItems($items)
    {

        try {
            $this->validateItems($items);
        }catch (Exception $error) {
            throw new Exception(
                "Invalid parameter 'items' at ".__METHOD__
                ." error: ".$error->getMessage()
            );
        }


        $_items = $this->getItems();

        $i = -1;
        foreach($items as $item)
        {
            $i ++;

            if ((is_string($item)) || (is_numeric($item))) {
                $pattern = $item;
                $item = new Lib_Fnmatch_FilterItem();
                $item->setPattern($pattern);
            }

            if (($item instanceof Lib_Fnmatch_FilterItem)) {

                $_items[] = $item;
            }

        }

        $this->_items = $_items;
        
    }



    /**
     * @throws Exception
     * @param  string|Lib_Fnmatch_FilterItem $item
     * @return void
     */
    public function addItem($item)
    {
        $list = array($item);
        try {
            $this->addItems($list);
        } catch(Exception $error) {
            throw new Exception("Invalid parameter 'item' at ".__METHOD__
                                . " error: ".$error->getMessage());
        }

    }


    /**
     * @throws Exception
     * @param  array $items
     * @return
     */
    public function validateItems($items)
    {
        if (is_null($items)) {
            return;
        }

        if (is_array($items)!==true) {
            throw new Exception("Invalid parameter 'items' at ".__METHOD__);

        }

        $i = -1;
        foreach($items as $item) {

            $i++;

            if ($item === null) {
                throw new Exception(
                        "Invalid item in list at index="
                        .$i."  at ".__METHOD__
                    );
            }

            if ($item instanceof Lib_Fnmatch_FilterItem) {

                continue;
            }

            if ((is_string($item)) || (is_numeric($item))) {
                continue;
            }

            throw new Exception(
                        "Invalid item in list at index="
                        .$i."  at ".__METHOD__
                    );




        }


    }



    public function matchOne($string, $flags)
    {

        $patternList = $this->getItems();
        foreach($patternList as $filterItem) {
            if ($filterItem instanceof Lib_Fnmatch_FilterItem !== true) {
                continue;
            }
            /**
             * @var Lib_Fnmatch_FilterItem $filterItem
             */
            if ($filterItem->hasPattern() !== true) {
                continue;
            }


            $isMatched = false;
            $pattern = $filterItem->getPattern();

            

            if ($filterItem->hasFlags()) {
                $isMatched = fnmatch(
                    $pattern, $string, $filterItem->getFlags()
                );
            } else {
                $isMatched = fnmatch(
                    $pattern, $string, $flags
                );
            }


            if ($isMatched === true) {
                return true;
            }


        }


        return false;
    }


    public function matchAll($string, $flags)
    {


        $patternList = $this->getItems();

        foreach($patternList as $filterItem) {

            if ($filterItem instanceof Lib_Fnmatch_FilterItem !== true) {
                continue;
            }

            /**
             * @var Lib_Fnmatch_FilterItem $filterItem
             */
            if ($filterItem->hasPattern() !== true) {
                continue;
            }


            $isMatched = false;
            $pattern = $filterItem->getPattern();
            if ($filterItem->hasFlags()) {
                $isMatched = fnmatch(
                    $pattern, $string, $filterItem->getFlags()
                );
            } else {
                $isMatched = fnmatch(
                    $pattern, $string, $flags
                );
            }


            if ($isMatched === true) {
                return false;
            }


        }


        return true;
    }


    public function matchNone($string, $flags)
    {


        $patternList = $this->getItems();

        foreach($patternList as $filterItem) {

            if ($filterItem instanceof Lib_Fnmatch_FilterItem !== true) {
                continue;
            }

            /**
             * @var Lib_Fnmatch_FilterItem $filterItem
             */
            if ($filterItem->hasPattern() !== true) {
                continue;
            }


            $isMatched = false;
            $pattern = $filterItem->getPattern();
            if ($filterItem->hasFlags()) {
                $isMatched = fnmatch(
                    $pattern, $string, $filterItem->getFlags()
                );
            } else {
                $isMatched = fnmatch(
                    $pattern, $string, $flags
                );
            }


            if ($isMatched === true) {
                return false;
            }


        }


        return true;
    }


}
