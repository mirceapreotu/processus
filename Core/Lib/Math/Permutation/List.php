<?php
/**
 * Lib_Math_Permutation_List Class
 *
 * @package Lib_Math_Permutation
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Math_Permutation_Traits
 *
 * @package Lib_Math_Permutation
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Math_Permutation_List
{

    /**
     * @var array
     */
    protected $_dataProvider;

    /**
     * @var array
     */
    protected $_result;

    /**
     * @return array
     */
    public function getResult()
    {
        if (is_array($this->_result)!==true) {
            $this->_result = array();
        }
        return $this->_result;
    }


    /**
     * @return array
     */
    public function getDataProvider()
    {
        if (is_array($this->_dataProvider)!==true) {
            $this->_dataProvider = array();
        }

        return $this->_dataProvider;
    }

    /**
     *
     *
     *
     *
     * @throws Exception
     * @param  array|null $value
     * @return void
     */
    public function setDataProvider($value)
    {
        /* EXAMPLE

            $value = array
             (
                "A","B","C"
            );
        */

        if ($value===null) {
            $value=array();
        }
        if (is_array($value)!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_dataProvider = (array)$value;
    }

    /**
     * @param array $value
     * @return void
     */
    public function addDataProviderItem($value)
    {
        $dataProvider = $this->getDataProvider();
        $dataProvider[] = $value;
        $this->_dataProvider = $value;
    }


    /**
     * @return array
     */
    public function run()
    {

        

        $this->_result=null;
        $dataProvider = $this->getDataProvider();


        $this->_run($dataProvider, array());
        return $this->getResult();
    }


  
//http://docstore.mik.ua/orelly/webprog/pcook/ch04_26.htm

    protected function _run($items, $perms = array())
    {
        
        if (count($items)<1) {
           // print join(' ', $perms) . "\n";

            $result = $this->getResult();
            $result[] = $perms;//trim($string."");
            $this->_result = $result;


            return;
        }
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newitems = $items;
            $newperms = $perms;
            list($foo) = array_splice($newitems, $i, 1);
            array_unshift($newperms, $foo);
            $this->_run($newitems, $newperms);
        }
    }

    //function pc_next_permutation($p, $size) {
    // slide down the array looking for where we're smaller than the next guy for ($i = $size - 1; $p[$i] >= $p[$i+1]; --$i) { }
    // if this doesn't occur, we've finished our permutations
    // the array is reversed: (1, 2, 3, 4) => (4, 3, 2, 1) if ($i == -1) { return false; }
    // slide down the array looking for a bigger number than what we found before for ($j = $size; $p[$j] <= $p[$i]; --$j) { }
    // swap them $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;
    // now reverse the elements in between by swapping the ends for (++$i, $j = $size; $i < $j; ++$i, --$j) { $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp; } return $p; } $set = split(' ', 'she sells seashells'); // like array('she', 'sells', 'seashells') $size = count($set) - 1; $perm = range(0, $size); $j = 0; do { foreach ($perm as $i) { $perms[$j][] = $set[$i]; } } while ($perm = pc_next_permutation($perm, $size) and ++$j); foreach ($perms as $p) { print join(' ', $p) . "\n"; }



}
