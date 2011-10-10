<?php


/**
 * Lib_Utils_Vector_UnsignedBigIntString
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_Vector
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_Vector_UnsignedBigIntString
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_Vector
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Vector_UnsignedBigIntString
{

    /**
     * @static
     * @param  $array|null
     * @param  array|null $excludeValues
     * @param  bool $castFromString
     * @return array
     */
   public static function filter($array, $excludeValues, $castFromString)
   {

       $result = array();
       if (is_array($array)!==true) {
           return $result;
       }
       if (is_array($excludeValues)!==true) {
           $excludeValues = array();
       }
       $castFromString = (bool)$castFromString;
       if ($castFromString === true) {
           $_excludeValues = array();
           foreach ($excludeValues as $excludeValue) {
               $excludeValueCasted = self::_asUnsignedBigIntString(
                   $excludeValue,
                   null
               );
               $_excludeValues[]=$excludeValue;
               if ($excludeValueCasted !== null) {
                $_excludeValues[] = $excludeValueCasted;
               }
           }
           $excludeValues = $_excludeValues;
       }


       $_array = array();
       foreach ($array as $value) {

           $value = self::_asUnsignedBigIntString($value, null);
           if ($value === null) {
               continue;
           }
           if ((in_array($value, $excludeValues, true)) === true) {
               continue;
           }
           $_array[] = $value;
       }
       return $_array;
   }




   /**
	 * @static
	 * @param  mixed $value
	 * @param mixed $defaultValue
	 * @return string|mixed
	 */
	protected static function _asUnsignedBigIntString(
        $value,
        $defaultValue=null
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

    
}


