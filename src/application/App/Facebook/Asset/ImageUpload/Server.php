<?php
/**
 * App_Facebook_Asset_ImageUpload_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Facebook_ImageUpload
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Facebook_Asset_ImageUpload_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Facebook_Asset_ImageUpload
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class App_Facebook_Asset_ImageUpload_Server
    extends App_Asset_ImageUpload_Server
{

    /**
     * @override
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }
    




    


}
