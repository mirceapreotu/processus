<?php
/**
 * App_FilterTest Class
 *
 * @category	meetidaaa.com
 * @package     Test_App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * App_FilterTest
 *
 * @category	meetidaaa.com
 * @package     Test_App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class App_FilterTest extends PHPUnit_Framework_TestCase
{
	
	public function testUmlaut()
	{
		$cleanName = App_Filter::clean('Hellö Würld');
		$this->assertEquals($cleanName,'HELLÖ WÜRLD');
	}
	
	
}
