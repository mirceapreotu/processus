<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:45
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Util
{


    // +++++++++++++++++ working with KeyPrefixes ++++++++++++++++++++++++++

    


    /**
     * @static
     * @param  string $key
     * @param  string|null $delimiter
     * @param  array $prefixParts
     * @return string
     */
    public static function addKeyPrefix($key, $delimiter, $prefixParts)
    {
        $key = "".$key;
        $result = $key;
        if ($delimiter === null) {
            $delimiter = ".";
        }
        $delimiter = "".$delimiter;


        if (!is_array($prefixParts)) {
            return $result;
        }

        $items = (array)$prefixParts;
        $items[] = $key;

        $parts = array();
        foreach($items as $item) {
            $item = "".$item;
            if (strlen($item)<1) {
                continue;
            }
            $parts[] = $item;
        }

        $key = "".implode($delimiter, $parts);

        $result = $key;
        return $result;
    }

    /**
     * @static
     * @param  string $key
     * @param  string|null $delimiter
     * @param  array $prefixParts
     * @return string
     */
    public static function removeKeyPrefix($key, $delimiter, $prefixParts)
    {
        $key = "".$key;
        $result = $key;
        if ($delimiter === null) {
            $delimiter = ".";
        }
        $delimiter = "".$delimiter;

        if (!is_array($prefixParts)) {
            return $result;
        }

        if (!is_array($prefixParts)) {
            return $result;
        }

        $items = (array)$prefixParts;
        $items[] = $key;

        $parts = array();
        foreach($items as $item) {
            $item = "".$item;
            if (strlen($item)<1) {
                continue;
            }
            $parts[] = $item;
        }

        $prefix = "".implode($delimiter, $parts);

        if (is_string($prefix)!==true) {
            return $result;
        }
        if (strlen($prefix)<1) {
            return $result;
        }

        $prefix = $prefix.$delimiter;

        if (self::stringStartsWith($key, $prefix, false)!==true) {
            return $result;
        }
        $result = "".substr($key, strlen($prefix));
        return $result;
    }








}
