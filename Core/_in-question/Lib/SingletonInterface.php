<?php
/**
 * Lib_SingletonInterface Interface
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_SingletonInterface
 *
 * @package Lib
 *
 * @abstract
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
interface Lib_SingletonInterface
{

	/**
	 * Returns an instance
	 *
	 * @return StdObject
	 */
	public static function getInstance();
	
}
