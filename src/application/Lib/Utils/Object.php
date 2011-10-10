<?php

/**
 * Lib_Utils_Object
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
 * Lib_Utils_Object
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
class Lib_Utils_Object
{

    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  string|mixed $name
     * @param null|bool $marshallExceptions
     * @param null|mixed $errorValue
     * @return bool|mixed
     */
    public static function hasPropertyPublic(
        $var,
        $name,
        $marshallExceptions = null,
        $errorValue = null

        // returns true or errorValue or throws Exception

    )
    {
        if ($marshallExceptions === null) {
            $marshallExceptions = true;
        }
        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool or null at "
                .__METHOD__
            );
        }
        $result = self::_hasPropertyPublic(
            $var, $name, $marshallExceptions, $errorValue
        );

        return $result;
    }

    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  string|mixed $name
     * @param null|bool $marshallExceptions
     * @param null|mixed $errorValue
     * @return mixed
     */
    public static function getPropertyPublic(
        $var,
        $name,
        $marshallExceptions = null,
        $errorValue = null
    )
    {
        if ($marshallExceptions === null) {
            $marshallExceptions = true;
        }
        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool or null at "
                .__METHOD__
            );
        }
        $result = self::_getPropertyPublic(
            $var, $name, $marshallExceptions, $errorValue
        );

        return $result;
    }


    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param string|mixed $name
     * @param  bool $marshallExceptions
     * @param  array|mixed $errorValue
     * @return mixed
     */
    protected static function _getPropertyPublic(
        $var,
        $name,
        $marshallExceptions = null,
        $errorValue = null
    )
    {

        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool at ".__METHOD__
            );
        }

        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }

        $result = null;

        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties at ".__METHOD__
                );
            }
            return $errorValue;
        }

        if (is_array($var)) {

            if (array_key_exists($name, $var)) {
                return $var[$name];
            }

            if ($marshallExceptions === true) {
                throw new Exception(
                    "Undefined index '".$name."' in array at ".__METHOD__
                );
            }
            return $errorValue;
        }

        try {

            if (property_exists($var, $name)) {
                return $var->$name;
            }

            if ($var instanceof ArrayAccess) {
                /**
                 * @var ArrayAccess $var
                 */
                if ($var->offsetExists($name)) {
                    return $var->offsetGet($name);
                }
            }


            $list = (array)get_object_vars($var);
            if (array_key_exists($name, $list)) {
                return $list[$name];
            }

            if ($var instanceof Iterator) {
                /**
                 * @var Iterator $var
                 */
                $list = array();
                foreach($var as $key => $value) {
                    if ($key === $name) {
                        return $value;
                    }
                    $list[$key] = $value;
                }
                if (array_key_exists($name, $list)) {
                    return $list[$name];
                }
            }

            $reflectionClass = new ReflectionClass($var);
            if ($reflectionClass->hasProperty($name)) {
                $reflectionProperty = $reflectionClass->getProperty($name);
                if (
                        ($reflectionProperty->isPublic())
                        && ($reflectionProperty->isStatic()!==true)
                ) {
                    return $reflectionProperty->getValue($var);
                }
            }

            throw new Exception("Property is not accessible");

        } catch (Exception $e) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "Error while get property '".$name."'  at "
                        .__METHOD__." details: ".$e->getMessage()
                );
            }

            return $errorValue;
        }


    }





        /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param string|mixed $name
     * @param  bool $marshallExceptions
     * @param  array|mixed $errorValue
     * @return bool|mixed
     */
    protected static function _hasPropertyPublic(
        $var,
        $name,
        $marshallExceptions = null,
        $errorValue = null
    )
    {

        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool at ".__METHOD__
            );
        }

        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }

        

        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties at ".__METHOD__
                );
            }
            return $errorValue;
        }

        if (is_array($var)) {
            if (array_key_exists($name, $var)) {
                return true;
            } else {
                return $errorValue;
            }
        }

        try {


            if (property_exists($var, $name)) {
                return true;
            }

            if ($var instanceof ArrayAccess) {
                /**
                 * @var ArrayAccess $var
                 */
                if ($var->offsetExists($name)) {
                    return true;
                }
            }


            $list = (array)get_object_vars($var);
            if (array_key_exists($name, $list)) {
                return true;
            }

            if ($var instanceof Iterator) {
                /**
                 * @var Iterator $var
                 */
                $list = array();
                foreach($var as $key => $value) {
                    if ($key === $name) {
                        return $value;
                    }
                    $list[$key] = $value;
                }
                if (array_key_exists($name, $list)) {
                    return true;
                }
            }

            $reflectionClass = new ReflectionClass($var);
            if ($reflectionClass->hasProperty($name)) {
                $reflectionProperty = $reflectionClass->getProperty($name);
                if (
                        ($reflectionProperty->isPublic())
                        && ($reflectionProperty->isStatic()!==true)
                ){
                    return true;
                }
            }

            return $errorValue;

        } catch (Exception $e) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "Error while get property '".$name."'  at "
                        .__METHOD__." details: ".$e->getMessage()
                );
            }

            return $errorValue;
        }


    }






    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  bool $marshallExceptions
     * @param  mixed $errorValue
     * @return array|mixed
     */
    public static function getPropertiesPublic(
        $var,
        $marshallExceptions = null,
        $errorValue = null
    )
    {
        if ($marshallExceptions === null) {
            $marshallExceptions = true;
        }
        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool or null at "
                .__METHOD__
            );
        }
        $result = self::_getPropertiesPublic(
            $var, $marshallExceptions, $errorValue
        );

        return $result;

    }

    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  bool $marshallExceptions
     * @param  array|mixed $errorValue
     * @return array|mixed
     */
    protected static function _getPropertiesPublic(
        $var,
        $marshallExceptions = null,
        $errorValue = null
    )
    {

        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool at ".__METHOD__
            );
        }

        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }

       
        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties at ".__METHOD__
                );
            }
            return $errorValue;
        }

        if (is_array($var)) {
            return $var;
        }


        try {

            $list = (array)get_object_vars($var);

            if ($var instanceof Iterator) {
                foreach($var as $key => $value) {
                    if (array_key_exists($key, $list)!==true) {
                        $list[$key] = $value;
                    }
                }
            }

            $reflectionClass = new ReflectionClass($var);
            $reflectionProperties = $reflectionClass->getProperties(
                ReflectionProperty::IS_PUBLIC
            );
            foreach($reflectionProperties as $reflectionProperty) {
                /**
                 * @var ReflectionProperty $reflectionProperty
                 */

                $reflectionPropertyName = $reflectionProperty->getName();

                if (array_key_exists($reflectionPropertyName, $list)) {
                    continue;
                }

                if (($reflectionProperty->isPublic()) && ($reflectionProperty->isStatic()!==true)) {
                    $list[$reflectionPropertyName] =
                            $reflectionProperty->getValue($var);
                }

            }

            return (array)$list;

        } catch (Exception $e) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "Error while explore 'var' at "
                        .__METHOD__." details: ".$e->getMessage()
                );
            }

            return $errorValue;
        }

       
    }



    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  bool $marshallExceptions
     * @param  mixed $errorValue
     * @return int|mixed
     */
    public static function getPropertiesPublicCount(
        $var,
        $marshallExceptions = null,
        $errorValue = null
    )
    {
        if ($marshallExceptions === null) {
            $marshallExceptions = true;
        }
        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool or null at "
                .__METHOD__
            );
        }
        $result = self::_getPropertiesPublicCount(
            $var, $marshallExceptions, $errorValue
        );

        return $result;
    }

    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  bool $marshallExceptions
     * @param  int|mixed $errorValue
     * @return int|mixed
     */
    protected static function _getPropertiesPublicCount(
        $var, $marshallExceptions, $errorValue
    )
    {
        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool at ".__METHOD__
            );
        }
        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }



        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties and is not countable"
                    ." at ".__METHOD__
                );
            }
            return $errorValue;
        }


        


        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties at ".__METHOD__
                );
            }
            return $errorValue;
        }

        if (is_array($var)) {
            return count(array_keys($var));
        }


        try {

            $list = (array)get_object_vars($var);

            if ($var instanceof Iterator) {
                foreach($var as $key => $value) {
                    if (array_key_exists($key, $list)!==true) {
                        $list[$key] = $value;
                    }
                }
            }

            $reflectionClass = new ReflectionClass($var);
            $reflectionProperties = $reflectionClass->getProperties(
                ReflectionProperty::IS_PUBLIC
            );
            foreach($reflectionProperties as $reflectionProperty) {
                /**
                 * @var ReflectionProperty $reflectionProperty
                 */

                $reflectionPropertyName = $reflectionProperty->getName();

                if (array_key_exists($reflectionPropertyName, $list)) {
                    continue;
                }

                if ($reflectionProperty->isPublic() !== true) {
                    continue;
                }
                if ($reflectionProperty->isStatic() === true) {
                    continue;
                }

                $list[$reflectionPropertyName] =
                        $reflectionProperty->getValue($var);
            }

            return (int)count((array)$list);

        } catch (Exception $e) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "Error while explore 'var' at "
                        .__METHOD__." details: ".$e->getMessage()
                );
            }

            return $errorValue;
        }



    }







    /**
     * @static
     * @param  mixed|null $var
     * @return bool
     */
    public static function isPrimitive($var)
    {
        return (bool)(self::_isPrimitive($var)===true);
    }



    /**
     * @static
     * @param  mixed $var
     * @param  string|mixed $key
     * @param null|string $delimiter
     * @param null|bool $marshallExceptions
     * @param null|mixed $errorValue
     * @return mixed|null
     */
    public static function getPropertyPublicRecursive(
        $var, $key, $delimiter=null, $marshallExceptions=null, $errorValue=null
    )
    {

        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }


        if ($delimiter===null) {
            $delimiter = ".";
        }

        if (is_string($delimiter)!==true) {
            throw new Exception("Invalid parameter 'delimiter' at ".__METHOD__);
        }
        if (strlen($delimiter)<1) {

            $result = self::_getPropertyPublic(
                $var, $key, $marshallExceptions, $errorValue
            );


            return $result;
        } else {
            $result = self::_getPropertyPublicRecursive(
                $var, $key, $delimiter, $marshallExceptions, $errorValue
            );
            return $result;
        }

        
    }





    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  string $key
     * @param null|string $delimiter
     * @param null|bool $marshallExceptions
     * @param null|mixed $errorValue
     * @return bool|mixed
     */
    public static function hasPropertyPublicRecursive(
        $var,
        $key,
        $delimiter=null,
        $marshallExceptions=null,
        $errorValue=null
        // returns true or errorValue or throws Exception
    )
    {

        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }

        if ($delimiter===null) {
            $delimiter = ".";
        }

        if (is_string($delimiter)!==true) {
            throw new Exception("Invalid parameter 'delimiter' at ".__METHOD__);
        }
        if (strlen($delimiter)<1) {
            $result = self::_hasPropertyPublic(
                $var, $key, $marshallExceptions, $errorValue
            );


            return $result;
        } else {
             $result = self::_hasPropertyPublicRecursive(
                $var, $key, $delimiter, $marshallExceptions, $errorValue
            );
            return $result;
        }

        

    }



    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  string|mixed $name
     * @param  string $delimiter
     * @param  bool $marshallExceptions
     * @param  mixed $errorValue
     * @return bool|mixed
     */
    protected static function _hasPropertyPublicRecursive(
        $var, $name, $delimiter, $marshallExceptions, $errorValue
    )
    {


        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool at ".__METHOD__
            );
        }
        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }

        if (is_string($delimiter)!==true) {
            throw new Exception("Invalid parameter 'delimiter' at ".__METHOD__);
        }
        if (strlen($delimiter)<1) {
            throw new Exception(
                "Invalid parameter 'delimiter' cant be empty at ".__METHOD__
            );
        }



        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties and is not countable"
                    ." at ".__METHOD__
                );
            }
            return $errorValue;
        }

        if (is_string($name)!==true) {
            try {
                $result = self::_hasPropertyPublic($var, $name, true, null);
                return $result;
            }catch(Exception $e) {
                if ($marshallExceptions === true) {
                    throw new Exception(
                        "Error while get nonstring-property '".$name."'"
                        ." at ".__METHOD__." details: ".$e->getMessage()
                    );
                }
                return $errorValue;
            }
        }



        $parts = array();
        try {
            $parts = explode($delimiter, $name);
            if (is_array($parts)!==true) {
                throw new Exception(
                    "Invalid conversion of property '".$name."' to array"
                );
            }

        } catch (Exception $e) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "Invalid parameter 'property' at ".__METHOD__
                    ." details: ".$e->getMessage()
                );
            } else {
                return $errorValue;
            }
        }


        if (count($parts)<1) {

            try {
                $result = self::_hasPropertyPublic($var, $name, true, null);
                return $result;
            }catch(Exception $e) {
                if ($marshallExceptions === true) {
                    throw new Exception(
                        "Error while get property '".$name."' count(parts)<1. "
                        ." at ".__METHOD__." details: ".$e->getMessage()
                    );
                }
                return $errorValue;
            }

        }


        $result = $var;

        $has = false;

        try {

            $has = false;
            foreach ($parts as $partIndex => $partName) {

                if (self::_hasPropertyPublic(
                        $result, $partName, false, null
                ) !== true) {

                    if ($marshallExceptions === true) {
                        $error = new Exception(
                            "Error: Unable to access key=".$partName." property=".$name
                            ." at ".__METHOD__
                        );
                        throw $error;
                    } else {
                        return $errorValue;
                    }





                } else {

                    $has = true;
                }

                $result = self::_getPropertyPublic(
                    $result, $partName, false, null
                );

            }
        } catch (Exception $e) {

            if ($marshallExceptions === true) {

                $error = new Exception(
                    "Error: Unable to access key=".$name
                    ." at ".__METHOD__." details: ".$e->getMessage()
                );
                throw $error;
            }

            return $errorValue;

        }


        return ($has===true);

    }






    /**
     * @static
     * @throws Exception
     * @param  mixed $var
     * @param  string|mixed $name
     * @param  string $delimiter
     * @param  bool $marshallExceptions
     * @param  mixed $errorValue
     * @return mixed|null
     */
    protected static function _getPropertyPublicRecursive(
        $var, $name, $delimiter, $marshallExceptions, $errorValue
    )
    {


        if (is_bool($marshallExceptions)!==true) {
            throw new Exception(
                "Parameter 'marshallExceptions' must be a bool at ".__METHOD__
            );
        }
        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
        }

        if (is_string($delimiter)!==true) {
            throw new Exception("Invalid parameter 'delimiter' at ".__METHOD__);
        }
        if (strlen($delimiter)<1) {
            throw new Exception(
                "Invalid parameter 'delimiter' cant be empty at ".__METHOD__
            );
        }



        if (self::_isPrimitive($var)===true) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "A primitive type cant have properties and is not countable"
                    ." at ".__METHOD__
                );
            }
            return $errorValue;
        }

        if (is_string($name)!==true) {
            try {
                $result = self::_getPropertyPublic($var, $name, true, null);
                return $result;
            }catch(Exception $e) {
                if ($marshallExceptions === true) {
                    throw new Exception(
                        "Error while get nonstring-property '".$name."'"
                        ." at ".__METHOD__." details: ".$e->getMessage()
                    );
                }
                return $errorValue;
            }
        }



        $parts = array();
        try {
            $parts = explode($delimiter, $name);
            if (is_array($parts)!==true) {
                throw new Exception(
                    "Invalid conversion of property '".$name."' to array"
                );
            }
        } catch (Exception $e) {
            if ($marshallExceptions === true) {
                throw new Exception(
                    "Invalid parameter 'property' at ".__METHOD__
                    ." details: ".$e->getMessage()
                );
            } else {
                return $errorValue;
            }
        }


        if (count($parts)<1) {

            try {
                $result = self::_getPropertyPublic($var, $name, true, null);
                return $result;
            }catch(Exception $e) {
                if ($marshallExceptions === true) {
                    throw new Exception(
                        "Error while get property '".$name."' count(parts)<1. "
                        ." at ".__METHOD__." details: ".$e->getMessage()
                    );
                }
                return $errorValue;
            }

        }


        $result = $var;

        try {

            foreach ($parts as $key => $val) {

                $result = self::_getPropertyPublic($result, $val, true, null);

            }
        } catch (Exception $e) {

            if ($marshallExceptions === true) {

                $error = new Exception(
                    "Error: Unable to access key=".$name
                    ." at ".__METHOD__." details: ".$e->getMessage()
                );
                throw $error;
            }

            return $errorValue;

        }


        return $result;

    }






    /**
        * @static
        * @param  mixed|null $var
        * @return bool
        */
       protected static function _isPrimitive($var)
       {



           $result = true;
           if (is_array($var)) {
               return false;
           }

           if (is_object($var)!==true) {
               return true;
           }

           if ($var === null) {
               return $result;
           }


           // check primitive types
           $isPrimitive = (
                   ($var === null)
                   || (is_null($var))
                   || (is_string($var))
                   || (is_int($var))
                   || (is_float($var))
                   || (is_bool($var))
                   || (is_resource($var))
           );
           if ($isPrimitive === true) {
               return $result;
           }

           if (is_object($var)) {
               return false;
           }


           return false;
       }


}


