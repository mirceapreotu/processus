<?php
/**
 * Lib_Math_Vector Class
 *
 * @package Lib_Math
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Math_Vector
 *
 * @package Lib_Math
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Math_Vector
{

	/**
	 * @var array
	 */
	protected $_vector = array();

	/**
	 * Internal; just for caching
	 * @var float
	 */
	protected $_length = null;

	/**
	 * @param bool $isAdd true for add, false for del
	 * @param Lib_Math_Vector $vector
	 *
	 * @return Lib_Math_Vector this vector
	 */
	protected function _add(Lib_Math_Vector $vector, $isAdd = true)
	{

		$newVector = array();

		$vectorArray = $vector->getArray();

        /** @noinspection PhpAssignmentInConditionInspection */
        while ($scalar = array_shift($this->_vector)) {

			if ($isAdd) {
				$newVector[] = $scalar + array_shift($vectorArray);
			} else {
				$newVector[] = $scalar - array_shift($vectorArray);
			}
		}

		$this->_vector = $newVector;

		$this->_length = null; // mark length as invalid

		return $this;
	}

	/**
	 * @param array $vector
	 * @return Lib_Math_Vector
	 */
	public function __construct($vector)
	{
		$this->_vector = $vector;
	}

	/**
	 * Checks dimension compatibility
	 * @throws Exception
	 * @param Lib_Math_Vector $vector
	 * @return Lib_Math_Vector
	 */
	public function ensureCompatibility(Lib_Math_Vector $vector)
	{
		if ($this->getDimension() != $vector->getDimension()) {

			throw new Exception('Vector dimensions do not match.');
		}
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDimension()
	{
		return count($this->_vector);
	}

	/**
	 * Method supports chaining!
	 *
	 * @return Lib_Math_Vector
	 */
	public function normalize()
	{

		$newVector = array();
		$length = $this->getLength();

		foreach ($this->_vector as $scalar) {

			$newVector[] = ($scalar / $length);
		}

		$this->_vector = $newVector;

		$this->_length = null; // mark length as invalid

		return $this;
	}

	/**
	 * Adds a vector
	 * Method supports chaining!
	 * @param Lib_Math_Vector $vector
	 * @return Lib_Math_Vector
	 */
	public function add(Lib_Math_Vector $vector)
	{

		return $this->_add($vector, true);
	}

	/**
	 * Subtracts a vector
	 * Method supports chaining!
	 * @param Lib_Math_Vector $vector
	 * @return Lib_Math_Vector
	 */
	public function del(Lib_Math_Vector $vector)
	{

		return $this->_add($vector, false);
	}

	/**
	 *
	 * @return array
	 */
	public function getArray()
	{
		return $this->_vector;
	}

	/**
	 *
	 * @return Lib_Math_Vector
	 */
	public function getClone()
	{
		$vector = new Lib_Math_Vector($this->_vector);
		return $vector;
	}

	/**
	 *
	 * @return float
	 */
	public function getLength()
	{
		// recompute, if cached value does not exist
		if ($this->_length == null) {

			$this->_length = 0;

			foreach ($this->_vector as $scalar) {

				$this->_length += ($scalar * $scalar);
			}
			$this->_length = sqrt($this->_length);
		}

		return $this->_length;
	}
}
