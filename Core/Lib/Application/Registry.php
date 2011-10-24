<?php
/**
 * Lib_Application_Registry
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Application
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Application_Registry
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Application
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */


class Lib_Application_Registry
{
	/**
	 * implement in subclasses !
	 * @TODO Move to config
	 * @return string
	 */
	public function getApplicationPrefix()
	{
        return Bootstrap::getRegistry()->getApplicationPrefix();
	}

	/**
	 * @throws Exception
	 * @param  string $id
	 * @return string
	 */
	public function newApplicationModulePrefix($moduleId)
	{
		if (Lib_Utils_String::isEmpty($moduleId)===true) {
			$message = "Parameter 'moduleId' must be a string";
			$message .= " and cant be empty!";
			$message .= " at method = ".__METHOD__;
			throw new Exception($message);
		}
		$prefix = $this->getApplicationPrefix();
		if (Lib_Utils_String::isEmpty($prefix)===true) {
			$message = "Value 'prefix' must be a string and cant be empty!";
			$message .= " at method = ".__METHOD__;
			throw new Exception($message);
		}
		$prefix = trim($prefix);
		$id = trim($moduleId);

		$prefix .= "_".$id;
		return $prefix;


	}

    /**
     *
     * @return ArrayObject|null
     */
    public function getConfig()
    {
        $key = 'CONFIG';
        $value = $this->_getProperty($key);
        return $value;

    }

    /**
     * @param  $name
     * @return mixed|null
     */
    public function getProperty($name)
    {
        return $this->_getProperty($name);
    }

    /**
     * @param  $name
     * @param  $value
     * @return Lib_Registry_Registry
     */
    public function setProperty($name, $value)
    {
        return $this->_setProperty($name, $value);
    }

    /**
     * @param  $name
     * @return mixed|null
     */
    protected function _getProperty($name)
    {
        $result = null;

        try
        {
            $result = Zend_Registry::get($name);
        }
        catch (Exception $exception)
        {
        }

        return $result;
    }

    /**
     * @param  $name
     * @param  $value
     * @return Lib_Registry_Registry
     */
    protected function _setProperty($name, $value)
    {
        $result = $this;
        Zend_Registry::set($name, $value);
        return $result;
    }

}

?>
