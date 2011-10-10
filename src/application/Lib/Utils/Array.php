<?php

/**
 * Lib_Utils_Array
 *
 * Array Utils
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_Array
 *
 * Array Utils
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Array
{


    /**
     * returns true if $value===null or value is array
     * @static
     * @param  mixed $value
     * @return bool
     */
    public static function isArrayOrNull($value)
    {
        return (bool)((is_array($value))||($value===null));
    }


    /**
     * @static
     * @throws Exception
     * @param  $array
     * @param  $delimiter
     * @param  $property
     * @param  $value
     * @return array
     */
    public static function setPropertyRecursive(
        $array,
        $delimiter,
        $property,
        $value
    )
    {
        throw new Exception("IMPLEMENT".__METHOD__);
        if (is_array($array)!==true) {
            $array = array();
        }

        $propertyParts = explode($delimiter, $property);
        if (is_array($propertyParts)!==true) {
            $array[$property] = $value;
            return $array;
        }

        if (count($propertyParts)<2) {
            $array[$property] = $value;
            return $array;
        }

        //$key = array_pop($propertyParts);
        $path = implode($delimiter, $propertyParts);
        //$propertyTree = Lib_Utils_String::explodeRecursive($delimiter, $path);

        $parts = explode($delimiter, $path);
        if (is_array($parts)!==true) {
            return array();
        }
        $parts = (array)array_reverse($parts);
        $propertyTree = $value;

        foreach($parts as $partName) {
           $propertyTree = array($partName => $propertyTree);
        }


        //fuck, you must unsetPropertyRecursive before merge: TODO


        $array = (array)array_merge_recursive($array, $propertyTree);

        return $array;


    }



    public static function unsetPropertyRecursive(
        $array, $delimiter, $property
    ) {

        throw new Exception("IMPLEMENT".__METHOD__);

        if (is_array($array)!==true) {
            $array = array();
        }

        $propertyParts = explode($delimiter, $property);
        if (is_array($propertyParts)!==true) {
            return $array;
        }

        if (count($propertyParts)===1) {
            unset($array[$property]);

            return $array;
        }

        $partsCount = (int)count($propertyParts);
        $i=1;
        $part = new ArrayObject($array);
        //var_dump($part);
        //return $part["Cms"];
        foreach($propertyParts as $partName) {

            if ($i>$partsCount) {
                 return __LINE__;
                return "breaked";
                break;
            }
            if ($i===$partsCount) {
                // return __LINE__;
                //return "ff";
                //return $partName;
                $part[$partName] =" xxxxxxxxx";
               // return $part;
                //unset($part[$partName]);
                break;
            }
            //$part = self::getProperty($part, $partName);
            $xpart = self::getProperty($part, $partName);
            if (is_array($xpart)) {
                $part[$partName] = new ArrayObject($xpart);
            }

            if ($part===null) {
                return "nothing";
            }

            /**
             * @var ArrayObject $part
             */
            try {
                if ($part instanceof ArrayObject !== true) {
                    //svar_dump($part);
                    return $part;
                    return "ccccccccccc";
                }
                if ($part->offsetExists($partName)!==true) {
                    return "nix";
                }

            } catch(Exception $e) {
                break;
            }
            $part = $part->offsetGet($partName);

            //var_dump($part);
            if ((is_array($part)) || (($part instanceof ArrayObject))) {
            } else {
                return $part;
                // return __LINE__;
                return $partName;
                break;
            }
            if ($i===$partsCount) {
 return __LINE__;
                break;


            }
//var_dump($i);



            $i++;
            //var_dump($i);

        }
//return $array;

        return $part;

    }


    /**
     * returns -1 if we dont have an array, or needle is not found in array
     * @static
     * @param  array|null $array
     * @param  mixed $needle
     * @param null|bool $strict
     * @return int
     */
    public static function indexOf($array, $needle, $strict = null)
    {
        $result = -1;
        if (is_bool($strict)!==true) {
            $strict = true;
        }

        if (is_array($array) !== true) {
            return $result;
        }


        $index = array_search(
                    $needle,
                    $array,
                    $strict
                );
        if ( (is_int($index)) && ($index>=0) ) {
            return $index;
        }
        return $result;
    }



    /**
     * returns an array that has key-value-pairs,
     * where each key will be try to be casted an unsigned int
     * invalid keys will be dropped
     * @static
     * @param  $array
     * @param  array|null $blacklist
     * @param  array|null $whitelist
     * @param  bool $castFromString
     * @return array
     */
    public static function filterKeysUseUnsignedInt(
        $array,
        $blacklist,
        $whitelist,
        $castFromString
    )
    {
        $result = array();
        if (is_array($array)!==true) {
            return $result;
        }

        $castFromString = (bool)$castFromString;

        if ($castFromString) {
            if (is_array($blacklist)) {
                $blacklist = Lib_Utils_Vector_UnsignedInt::filter(
                    $blacklist,
                    null,
                    true
                );
            }
            if (is_array($whitelist)) {
                $whitelist = Lib_Utils_Vector_UnsignedInt::filter(
                    $whitelist,
                    null,
                    true
                );
            }
        }


        foreach ($array as $key => $value) {

            if ($castFromString === true) {
                $key = self::_asUnsignedInt($key, null);
            }
            if (((is_int($key)) && ($key>=0))!==true) {
                // we dont have an unsigned int
                continue;
            }

            $inBlacklist =
                    (is_array($blacklist)
                    && (in_array($key, $blacklist, true)));
            $inWhitelist =
                    (is_array($whitelist)
                    && (in_array($key, $whitelist, true)));
            $useWhitelist = is_array($whitelist);

            if ($inBlacklist) {

                if (($useWhitelist) && ($inWhitelist)) {
                    $result[$key] = $value;
                    continue;
                } else {

                    // ignore that key
                    continue;
                }
            }

            if ($useWhitelist) {

                if ($inWhitelist) {
                    $result[$key] = $value;
                    continue;
                } else {
                    // ignore that key
                    continue;
                }

            } else {
                $result[$key] = $value;
                continue;
            }
        }
        return $result;
    }



    /**
     * @static
     * @throws Exception
     * @param  array $array
     * @param  array $propertyList
     * @return
     */
    public static function unsetProperties($array,$propertyList)
    {
        if (is_array($array)!==true) {
            $message = "Parameter array must be an array! at ".__METHOD__;
            throw new Exception($message);
        }
        if (is_array($propertyList)!==true) {
            return $array;
        }

        foreach ($propertyList as $propertyName) {
            try {
                unset($array[$propertyName]);
            } catch (Exception $e) {

            }
        }

        return $array;
    }


    /**
     * @static
     * @throws Exception
     * @param  array $array
     * @param  mixed $property
     * @return array
     */
    public static function groupBy($array, $property)
    {
        if (is_array($array)!==true) {
            $message = "Parameter array must be an array! at ".__METHOD__;
            throw new Exception($message);
        }

        $dict = array();

        foreach ($array as $item) {

            $groupValue = null;

            try {
                $groupValue = $item[$property];
            }catch(Exception $e){
                //NOP
            }

            $dictGroup = null;
            try {
                $dictGroup = $dict[$groupValue];
            }catch(Exception $e){
                //NOP
            }
            if (is_array($dictGroup)!==true) {
                $dict[$groupValue] = array();
            }

            $dict[$groupValue][] = $item;

        }
        return $dict;
    }



    /**
     * @static
     * @throws Exception
     * @param  array $array
     * @param  mixed $property
     * @return array
     */
    public static function groupByCollectFirstItems($array, $property)
    {
        if (is_array($array)!==true) {
            $message = "Parameter array must be an array! at ".__METHOD__;
            throw new Exception($message);
        }

        $dict = self::groupBy($array, $property);
        $_dict = array();
        foreach ($dict as $key => $list) {
            try {
                $firstItem = $list[0];
                $_dict[$key] = $firstItem;
            } catch(Exception $e) {
                //NOP
            }
        }
        return $_dict;
    }

    /**
     * @static
     * @throws Exception
     * @param  array $array
     * @param  mixed $property
     * @return array
     */
    public static function groupByCollectLastItems($array, $property)
    {
        if (is_array($array)!==true) {
            $message = "Parameter array must be an array! at ".__METHOD__;
            throw new Exception($message);
        }

        $dict = self::groupBy($array, $property);
        $_dict = array();
        foreach ($dict as $key => $list) {
            try {
                if (is_array($list)!==true) {
                    continue;
                }

                if ((count($list)>0) !==true) {
                    continue;
                }
                $lastItem = array_pop($list);
                $_dict[$key] = $lastItem;
            } catch(Exception $e) {
                //NOP
            }
        }
        return $_dict;
    }



    /**
     *
     * @param array|mixed $array
     * @param mixed $name
     * @param bool $strict
     * @return mixed|null
     */
    public static function getProperty($array, $name, $strict = true)
    {
        try {
            if ((is_array($array) && (isset($array[$name]))) !==true) {
                return null;
            }
            return $array[$name];
        } catch (Exception $e) {
            return null;
        }


        /*

        $result = null;
        try {
            if (is_array($array)!==true) {
                return $result;
            }

            $hasProperty = self::hasProperty($array, $name, $strict);

            if ($hasProperty !== true) {
                return $result;
            }

            $result = $array[$name];

        } catch(Exception $exception) {
            //NOP
        }
        return $result;

         */
    }


    /**
     *
     * @param array $array
     * @param string $name
     * @param bool $strict
     * @return boolean
     */
    public static function hasProperty($array, $name, $strict = true)
    {
        try {
            if (is_array($array) !== true) {
                return false;
            }
            return array_key_exists($name, $array);
           
        } catch (Exception $e) {
            return false;
        }

        return false;
        /*
        $result = false;
        try {
            if (is_array($array)!==true) {
                return $result;
            }

            if ($strict === true) {
                $keys = (array)array_keys($array);
                $result = (bool)in_array($name, $keys, $strict);
            } else {
                (bool)$result = array_key_exists($name, $array);
            }
        } catch (Exception $exception) {
            //NOP
        }
        return (bool)$result;

         */
    }



    /**
     *
     * @param type $array
     * @return array
     */
    public static function getPropertyNames($array)
    {
        $result = array();
        try {
            if (is_array($array)!==true) {
                return $result;
            }

            $result = (array)array_keys($array);
        } catch (Exception $exception) {
            //NOP
        }
        return $result;
    }

    /**
     *
     * @param type $array
     * @return boolean
     */
    public static function hasProperties($array)
    {
        $result = false;
        try {
            if (is_array($array)!==true) {
                return $result;
            }

            $propertyNames = (array)array_keys($array);

            $result = (bool)(count($propertyNames)>0);
        } catch (Exception $exception) {
            //NOP
        }
        return $result;
    }



    /**
     *
     * @param type $array array
     * @param type $name mixed
     * @param type $value mixed
     * @param type $strict boolean
     * @return void
     */
    public static function setPropertyIfExists(
        $array, $name, $value, $strict = true
    )
    {
        $result = null;
        try {
            if (is_array($array)!==true) {
                return $result;
            }

            $hasProperty = self::hasProperty($array, $name, $strict);
            if ($hasProperty !== true) {
                return $result;
            } else {
                $array[$name] = $value;
            }
        } catch (Exception $exception) {
            //NOP
        }

        return $result;
    }






    /**
     *
     * @param array $array
     * @return mixed|null
     */
    public static function pop(&$array)
    {
        $result = null;
        try {
            if (is_array($array)!==true) {
                return $result;
            }

            if ((count($array) > 0) !== true) {
                return $result;
            }

            $result = array_pop($array);
        } catch (Exception $exception) {
            //NOP
        }

        return $result;

    }


	/**
	 * @static
	 * @param  $array
	 * @return bool
	 */
	public static function isEmpty($array)
	{
		$result = true;

		try {
			if (is_array($array)!==true) {
				return $result;
			}

			if (count($array)>0) {
				return false;
			}

			if (count(array_keys($array))>0) {
				return false;
			}

		}catch(Exception $exception) {
			//NOP
		}

		return $result;
	}

	/**
	 * @static
	 * @param  $array
	 * @param  mixed $name
	 * @param bool $strict
	 * @return
	 */
	public static function removeProperty($array,$name, $strict = true)
	{
		try {
			if (is_array($array)!==true) {
				return;
			}

			if ($strict === null) {
				$strict = true;
			}
			$strict = (bool)$strict;
			if (self::hasProperty($array, $name, $strict)===true) {
				unset($array[$name]);
			}

		}catch(Exception $exception) {
			//NOP
		}
	}




    /**
     * @param  array $array
     * @param  array $values
     * @param  bool $strict
     * @return array
     */
    public function excludeValues($array, $values, $strict)
    {
        $array = (array)$array;
        $values = (array)$values;
        $strict = (bool)$strict;

        $_array = array();
        foreach ($array as $value) {
            if (in_array($value, $values, $strict) !== true) {
                $_array[] = $value;
            }
        }
        return $_array;
    }


    /**
	 * @static
	 * @param  mixed $value
	 * @param mixed $defaultValue
	 * @return int|mixed
	 */
	protected static function _asUnsignedInt($value, $defaultValue=null)
	{
		$result = $defaultValue;
		if ($value===null) {
			return $result;
		}

        if ( (is_string($value)) && (ctype_digit($value)) ) {
            $value = (int)$value;
        }

		if ((is_int($value)) && ($value>=0)) {
			return $value;
		}

		return $result;
	}


}


