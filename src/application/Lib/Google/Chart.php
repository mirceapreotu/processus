<?php
/**
 * Lib_Google_Chart Class
 *
 * @package     Lib_Google
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_Google_Chart
 *
 * @package     Lib_Google
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Google_Chart
{

	/**
	 * @var string
	 */
	const URL = 'http://chart.apis.google.com/chart?';

	/**
	 * @var string
	 */
	const TYPE_RADAR = 'r';

	/**
	 * @var string
	 */
	protected $_type = 'r';

	/**
	 * @var int
	 */
	protected $_width = 300;

	/**
	 * @var int
	 */
	protected $_height = 300;

	/**
	 * @var array
	 */
	protected $_data = array();

	/**
	 * @var array
	 */
	protected $_labels = array();

	/**
	 * @var string
	 */
	protected $_title = '';

	/**
	 * @var string
	 */
	protected $_titleColor = '000000';

	/**
	 * @var float
	 */
	protected $_titleSize = 11.5;

	/**
	 * @return string
	 */
	public function getUrl()
	{

		$params = array(
			'chxl' => '0:|' . join('|', $this->getLabels()),
			'chxt' => 'x,y',
			'chs' => $this->getSize(),
			'cht' => $this->getType(),
			'chco' => 'FF0000',
			'chd' => 't:' . join(',', $this->getData()) . ',' .
				array_shift($this->getData()),
			'chls' => '2,4,0',
			'chm' => 'B,FF000080,0,0,0,',
			'chtt' => $this->getTitle(),
			'chts' => $this->getTitleColor() . ',' . $this->getTitleSize()
		);

		$url = self::URL;

		foreach ($params as $key => $value) {

			$url .= $key . '=' . $value . '&';
		}

		return $url;

	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->_height;
	}

	/**
	 * @param int $height
	 * @return void
	 */
	public function setHeight($height)
	{
		$this->_height = $height;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->_width;
	}

	/**
	 * @param  $width
	 * @return void
	 */
	public function setWidth($width)
	{
		$this->_width = $width;
	}

	/**
	 * @return string
	 */
	public function getSize()
	{
		return $this->getWidth() . 'x' . $this->getHeight();
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * @param array $data
	 * @return void
	 */
	public function setData($data)
	{
		$this->_data = $data;
	}

	/**
	 * @return array
	 */
	public function getLabels()
	{
		return $this->_labels;
	}

	/**
	 * @param  $labels
	 * @return void
	 */
	public function setLabels($labels)
	{
		$this->_labels = $labels;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * @param string $type
	 * @return void
	 */
	public function setType($type)
	{
		$this->_type = $type;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}

	/**
	 * @param  $title
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->_title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitleColor()
	{
		return $this->_titleColor;
	}

	/**
	 * @param string $titleColor
	 * @return void
	 */
	public function setTitleColor($titleColor)
	{
		$this->_titleColor = $titleColor;
	}

	/**
	 * @return float
	 */
	public function getTitleSize()
	{
		return $this->_titleSize;
	}

	/**
	 * @param float $titleSize
	 * @return void
	 */
	public function setTitleSize($titleSize)
	{
		$this->_titleSize = $titleSize;
	}
}
