<?php
/**
 * App_Manager_AbstractManager Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_AbstractManager
 *
 * @category	meetidaaa.com
 * @package		App_Manager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */
abstract class App_Manager_AbstractManager extends App_GaintS_Core_AbstractManager
{

    /**
     * @return App_Application
     */
    public function getApplication()
    {
        return App_Application::getInstance();
    }


	/**
	 * @return Lib_Db_Xdb_Client
	 */
	public function getDbClient()
	{
        return parent::getDbClient();
	}

    /**
     * @return string
     */
    public function getCurrentDate()
    {
        return $this->getApplication()->getDbClient();
    }

	/**
	 * @param  mixed $id
	 * @return bool
	 */
	public function isValidId($id)
	{
        $result = false;
        $id = Lib_Utils_TypeCast_String::asUnsignedBigIntString($id, null);
        if ($id === null) {
            return $result;
        }
        $int = (int)$id;
        return (bool)($int>0);
	}
}
