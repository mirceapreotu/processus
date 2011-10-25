<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:45
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Util_Array
{


    /**
     * @static
     * @param  array|mixed|null $array
     * @param  string|int|mixed $name
     * @return bool
     */
    public static function hasProperty($array, $name)
    {
        $result = false;
        if (!is_array($array)) {
            return $result;
        }

        return (bool)
            (array_key_exists($name, $array)===true);
    }

    /**
     * @static
     * @param  array|mixed|null $array
     * @param  string|int|mixed $name
     * @return mixed|null
     */
    public static function getProperty($array, $name)
    {
        $result = null;
        if (!is_array($array)) {
            return $result;
        }
        if (!array_key_exists($name, $array)) {
            return $result;
        }
        return $array[$name];
    }





}
