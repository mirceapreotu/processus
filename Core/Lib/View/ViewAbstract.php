<?php
/**
 * Lib_View_ViewAbstract Class
 *
 * @package     Lib_View
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_View
 *
 * @package Lib_View
 *
 * @abstract
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
abstract class Lib_View_ViewAbstract
{
	/**
	 * @var string
	 */
	public $title = 'Abstract view: Set Title';

	/**
	 * @var Lib_Ctrl_CtrlAbstract
	 */
	protected $_ctrl;

	/**
	 * Constructs a view
	 *
	 * @return Lib_View_ViewAbstract
	 */
	public function __construct($ctrl)
	{
		$this->_ctrl = $ctrl;
	}

	/**
	 * @return Lib_Ctrl_CtrlAbstract
	 */
	public function getCtrl()
	{
		return $this->_ctrl;
	}

	/**
	 * Shows the view
	 */
	public abstract function view();

}
