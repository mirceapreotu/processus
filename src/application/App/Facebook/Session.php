<?php
/**
 * App_Facebook_Session Class
 *
 * @category	meetidaaa.com
 * @package		App_Facebook
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Facebook_Session
 *
 * @category	meetidaaa.com
 * @package		App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 */
class App_Facebook_Session extends Lib_Session_AbstractSession
{



    /**
     * override
     * @return array
     */
    public function getNamespaceNamesAvailable()
    {
        return array(
            self::NAMESPACE_NAME_DEFAULT,
        );
    }

    /**
     * @return Zend_Session_Namespace
     */
    public function getNamespaceDefault()
    {
        return $this->getNamespace(self::NAMESPACE_NAME_DEFAULT);
    }


}
