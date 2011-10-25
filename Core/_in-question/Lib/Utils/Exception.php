<?php
/**
 * Lib_Utils_Exception
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_Exception
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Exception
{



	/**
	 * @static
	 * @param  Exception|null $exception
	 * @return null|string
	 */
	public static function getClass($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return get_class($exception);
	}

	/**
	 * @static
	 * @param  Exception|null $exception
	 * @return int|null
	 */
	public static function getCode($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return $exception->getCode();
	}


	/**
	 * @static
	 * @param  Exception|null $exception
	 * @return null|string
	 */
	public static function getMessage($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return $exception->getMessage();
	}

	/**
	 * @static
	 * @param  Exception|null $exception
	 * @return null|string
	 */
	public static function getFile($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return $exception->getFile();
	}

	/**
	 * @static
	 * @param  Exception|null $exception
	 * @return null|int
	 */
	public static function getLine($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return $exception->getLine();
	}
	/**
	 * @static
	 * @param  Exception|null $exception
	 * @return string|null
	 */
	public static function getTraceAsString($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return $exception->getTraceAsString();
	}

	/**
	 * @static
	 * @param  Exception $exception
	 * @return Exception|null
	 */
	public static function getPrevious($exception)
	{
		if ($exception instanceof Exception !== true) {
			return null;
		}

		return $exception->getPrevious();
	}



    /**
     * @param Exception
     * @return array
     */
    public static function toArray($exception)
    {
		$result = array();
        if ($exception instanceof Exception !== true) {
			return $result;
		}

		$result = array(
			"class" => self::getClass($exception),
			"code" => self::getCode($exception),
			"message" => self::getMessage($exception),
			"file" => self::getFile($exception),
			"line" => self::getLine($exception),
		);


        return $result;

    }

	/**
	 * @static
	 * @param  $exception
	 * @return array|null
	 */
	public static function export($exception)
	{
		$result = null;
		$array = self::toArray($exception);
		if (is_array($array)!==true) {
			return $result;
		}

		$numKeys = (int)count(array_keys($array));
		if (($numKeys>0)!==true) {
			return $result;
		}

		return $array;
	}



}