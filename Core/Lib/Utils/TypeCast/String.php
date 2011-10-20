<?php

/**
 * Lib_Utils_TypeCast_String
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_TypeCast
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_String
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_TypeCast
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_TypeCast_String
{

    /**
	 * @static
	 * @param  mixed $value
	 * @param mixed $defaultValue
	 * @return string|mixed
	 */
	public static function asString(
        $value,
        $defaultValue = null
    )
	{
		$result = $defaultValue;
		if (is_string($value)) {
			return $value;
		}
		return $result;
	}

	/**
	 * @static
	 * @param  mixed $value
	 * @param mixed $defaultValue
	 * @return int|mixed
	 */
	public static function asUnsignedInt(
        $value,
        $defaultValue = null
    )
	{
		$result = $defaultValue;
		if ($value===null) {
			return $result;
		}

		if (is_int($value)) {
			return $value;
		}
		if ( (is_string($value)) && (ctype_digit($value)) ) {
			return (int)$value;
		}

		return $result;
	}


    /**
     *
	 * @static
	 * @param  mixed $value
	 * @param mixed $defaultValue
	 * @return string|mixed
	 */
	public static function asUnsignedBigIntString(
        $value,
        $defaultValue = null
    )
	{
		$result = $defaultValue;
		if ($value===null) {
			return $result;
		}

		if ((is_int($value)) && ($value>=0)) {
			return (string)$value;
		}
		if ( (is_string($value)) && (ctype_digit($value)) ) {
            if (((int)$value)>=0) {
                return (string)$value;
            }
		}

		return $result;
	}


	/**
	 * @static
	 * @param  $value
	 * @param int $precision
	 * @param mixed $defaultValue
	 * @return float|mixed
	 */
	public static function asFloat(
        $value,
        $precision = 0,
        $defaultValue = null
    )
	{
		$result = $defaultValue;
		if ($value===null) {
			return $result;
		}

		if (is_int($value)) {
			$value = (float)$value;
		}

		if ( is_float($value) ) {
			if ($precision>0) {
				return round($value, $precision);
			} else {
				return (float)$value;
			}
		}
		if ( (is_string($value)) && (is_numeric($value)) ) {

			$_value = (float)$value;
			if ($precision>0) {
				$_value = round($value, $precision);
				if (is_float($_value)) {
					return $_value;
				}
			} else {
				return (float)$value;
			}
		}

		return $result;
	}




	/**
	 * @static
	 * @param  mixed$value
	 * @param mixed $defaultValue
	 * @return bool|mixed
	 */
	public static function asBool(
        $value,
        $defaultValue = null
    )
	{
		$result = $defaultValue;
		if (is_bool($value)) {
			return $value;
		}

		if ($value === 1) {
			return true;
		}
		if ($value === 0) {
			return false;
		}

		if ($value === "1") {
			return true;
		}
		if ($value === "0") {
			return false;
		}

		return $result;
	}

	/**
	 * @static
	 * @param  mixed $value
	 * @param  array $haystack
     * @param bool $ignoreCase
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public static function asEnum(
        $value,
        $haystack,
        $ignoreCase = null,
        $defaultValue = null
    )
	{

		$result = $defaultValue;

        if ($ignoreCase === null) {
            $ignoreCase = false;
        }
        $ignoreCase = (bool)$ignoreCase;
		$haystack = (array)$haystack;

        if ( (is_string($value)) && ($ignoreCase === true) ) {

	        foreach ($haystack as $item) {
                if (is_string($item)!==true) {
                    continue;
                }

                $_item = strtolower($item);
                $_value = strtolower($value);
                if (is_string($_item)!==true) {
                    continue;
                }
                if ($_value === $_item) {
                    return $item;
                }
            }
            return $result;
        }

        if (in_array($value, $haystack) !== true) {
            return $result;
        }

        foreach ($haystack as $item) {
            if ($item === $value) {
                return $value;
            }
        }
        return $result;
	}

}


