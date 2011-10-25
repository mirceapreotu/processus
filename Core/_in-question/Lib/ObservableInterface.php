<?php
/**
 * Lib_Observable Class
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Observable
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
interface Lib_ObservableInterface
{

	/**
	 * Returns the event manager
	 *
	 * @return Lib_EventManager
	 */
	public function getEventManager();

}
