<?php
/**
 * Lib_View_FormAbstract Class
 *
 * @package Lib_View
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
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
 * @version    $Id$
 *
 */
abstract class Lib_View_FormAbstract extends Lib_View_ViewAbstract
{

	/**
	 * @var object
	 */
	public $form;

	/**
	 * @var object
	 */
	public $errors;

    /**
     * @param string $key
     * @return string|mixed
     */
    public function getValue($key)
    {
        if (property_exists($this->form, $key)) {
            return $this->form->$key;
        }
        return '';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getErrors($key)
    {
        if (property_exists($this->errors, $key)) {

            if (is_array($this->errors->$key)) {
                return join(', ', $this->errors->$key); 
            }
            return $this->errors->$key;
        }
        return '';
    }

	/**
	 * Constructs a view
	 *
	 * @param Lib_Ctrl_CtrlAbstract $ctrl
	 * @return Lib_View_FormAbstract
	 */
	public function __construct($ctrl)
	{
		parent::__construct($ctrl);

	    $this->form     = new StdClass();
	    $this->errors   = new StdClass();
	}
}
