<?php
/**
 * Lib_Math_Permutation_Traits Class
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
class Lib_Math_Permutation_Traits
{

    /**
     * @var array
     */
	protected $_traits;

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
    public function getTraits()
    {
        if (is_array($this->_traits)!==true) {
            $this->_traits = array();
        }

        return $this->_traits;
    }

    /**
     *
     *
     *
     *
     * @throws Exception
     * @param  array|null $traits
     * @return void
     */
    public function setTraits($traits)
    {
        /* EXAMPLE

            $traits = array
             (
                array('Happy', 'Sad', 'Angry', 'Hopeful'),
                array('Outgoing', 'Introverted'),
                array('Tall', 'Short', 'Medium'),
                array('Handsome', 'Plain', 'Ugly')
            );
        */

        if ($traits===null) {
            $traits=array();
        }
        if (is_array($traits)!==true) {
            throw new Exception("Invalid parameter 'traits' at ".__METHOD__);
        }
        $this->_traits = (array)$traits;
    }

    /**
     * @param array $trait
     * @return void
     */
    public function addTrait(array $trait)
    {
        $traits = $this->getTraits();
        $traits[] = $trait;
        $this->_traits = $traits;
    }


    /**
     * @return array
     */
    public function run()
    {
        $this->_result=null;
        $traits = $this->getTraits();


        $this->_run(null, $traits, 0);
        return $this->getResult();
    }


    /**
     * @param  null|array $currentItem
     * @param  array $traits
     * @param  int $i
     * @return
     */
    protected function _run($currentItem, $traits, $i)
    {
        if ($i >= count($traits)) {
            //echo trim($string) ."<br/>";// "\n";

            $result = $this->getResult();
            $result[] = $currentItem;//trim($string."");
            $this->_result = $result;

            return;
        }

        if (is_array($currentItem)!==true) {
            $currentItem = array();
        }

        foreach ($traits[$i] as $trait) {

            $x = $currentItem;
            $x[] = $trait;

            $this->_run($x, $traits, $i + 1);
        }

    }
}
