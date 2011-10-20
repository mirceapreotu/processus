<?php

/**
 * Lib_Utils_Base64
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
 * Lib_Utils_Base64
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
class Lib_Utils_Base64
{

    /**
     * @static
     * @param  string $data
     * @return string|null
     */
    public static function encode($data)
    {
        $result = base64_encode($data);
        if (is_string($result)!==true) {
            return null;
        }
        return $result;
    }


    /**
     * @static
     * @param  string $data
     * @param null|bool $strict
     * @return string|null
     */
    public static function decode($data, $strict = false)
    {
        if (is_bool($strict)!==true) {
            $strict = false;
        }
        $result = base64_decode($data, $strict);

        if (is_string($result)!==true) {
            return null;
        }
        return $result;
    }


    /**
     * @static
     * @param  string $data
     * @return null|string
     */
    public static function encodeUrlSafe($data)
    {
        // @see: http://www.garykessler.net/library/base64.html

        // In the URL and Filename safe variant,
        // character 62 (0x3E) "+" is replaced with a "-" (minus sign)
        // and character 63 (0x3F) "/" is replaced with a "_" (underscore).

        $result = self::encode($data);

        if (is_string($result)!==true) {
            return null;
        }

        $result = str_replace(
            array(
                "+",
                "/",
            ),
            array(
                "-",
                "_",
            ),
            $result
        );

        if (is_string($result)!==true) {
            return null;
        }

        return $result;

    }


    /**
     * @static
     * @param  $data
     * @param bool $strict
     * @return null|string
     */
    public static function decodeUrlSafe($data, $strict = false)
    {
        // @see: http://www.garykessler.net/library/base64.html

        // In the URL and Filename safe variant,
        // character 62 (0x3E) "+" is replaced with a "-" (minus sign)
        // and character 63 (0x3F) "/" is replaced with a "_" (underscore).

        if (is_bool($strict)!==true) {
            $strict = false;
        }

        $data = str_replace(
            array(
                "-",
                "_",
            ),
            array(
                "+",
                "/",
            ),
            $data
        );

        $result = self::decode($data, $strict);

        if (is_string($result)!==true) {
            return null;
        }

        return $result;

    }

}


