<?php
/**
 * App_View_Phpinfo Class
 *
 * @category	meetidaaa.com
 * @package		App_View
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_View_Phpinfo
 *
 * Display PHPInfo 
 *
 * @category	meetidaaa.com
 * @package		App_View
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */
class App_View_Phpinfo extends Lib_View_ViewAbstract
{
	
	/**
	 * Spits out the main html for the ExtJS application
	 */
	public function view() 
	{
		include SRC_PATH.'/view/phpinfo.php';
	}
}