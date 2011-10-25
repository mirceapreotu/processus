<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:45
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Util_String
{


    /**
     * @static
     * @param  string $str
     * @param  string $prefix
     * @param bool $ignoreCase
     * @return bool
     */
    public static function startsWith($str, $prefix, $ignoreCase=true)
    {
        $result = false;

        if (is_string($str)!==true) {
            return $result;
        }

        if (is_string($prefix)!==true) {
            return $result;
        }

        $lenHaystack = strlen($str);
        $lenNeedle = strlen($prefix);

        $length = (int)($lenHaystack - $lenNeedle);

        if ($length < 0) {

            return false;
        }

        $beginningStr = substr($str, 0, $lenNeedle);

        if ($ignoreCase) {
            return (strcmp($prefix, $beginningStr) === 0 );
        } else {
            return (strcasecmp($prefix, $beginningStr) === 0 );
        }
    }


    /**
     * @static
     * @param  string $string
     * @param  string $prefix
     * @param bool $ignoreCase
     * @return null|string
     */
    public static function removePrefixIfExist(
                $string, $prefix, $ignoreCase=true
            )
    {
        $result = null;
        if (!is_string($string)) {
            return $result;
        }
        $result = $string;

        if (!is_string($prefix)) {
            return $result;
        }

        if (self::startsWith($string, $prefix, $ignoreCase)!==true) {
            return $result;
        }

        $result = "".substr($string, strlen($prefix));
        return $result;


    }


    /**
     * @static
     * @throws Redisek_Exception
     * @param  string $template
     * @param  array $data
     * @param  bool $keepUnparsedMarker
     * @return string
     */
    public static function parseTemplate(
        $template, $data, $keepUnparsedMarker
    )
    {
        if (!is_bool($keepUnparsedMarker)) {
            // this forces exceptions
            $keepUnparsedMarker = false;
        }

        if ($template === null) {
            return "";
        }
        if ($data === null) {
            $data = array();
        }

        $pattern = "/\{([^\{\}]*)\}/i";

        $subject = $template;

        // I do not remember, where I found that snippet.
        $offset = 0;
        $match_count = 0;
        while (preg_match(
            $pattern,
            $subject,
            $matches,
            PREG_OFFSET_CAPTURE,
            $offset))
        {
            // Increment counter
            $match_count++;

            // Get byte offset and byte length
            // (assuming single byte encoded)
            $match_start = $matches[0][1];
            $match_length = strlen($matches[0][0]);

            // (Optional) Transform $matches to the format it
            // is usually set as (without PREG_OFFSET_CAPTURE set)
            $newmatches = null;
            foreach ($matches as $k => $match) {
                $newmatches[$k] = $match[0];
            }
            $matches = $newmatches;

            // Your code here
            $match = $matches[0];

            $marker = $match;
            $property = $matches[1];

            $value = null;
            if (Redisek_Util_Array::hasProperty($data, $property)) {
                $value = $data[$property];
            } else {
                if ($keepUnparsedMarker === true) {
                    $value = $marker;
                }

            }

            $replace = (string)$value;


            if (((is_string($subject)) && (is_string($replace)))!==true) {
                $e = new Redisek_Exception(
                    "subject/replace must be a string marker=".$marker
                );
                $e->createFault(
                    null, __METHOD__, array()
                );
                throw $e;
            }


            $subject = substr_replace(
                $subject,
                $replace,
                $match_start,
                $match_length
            );

            // Update offset to the end of the match
            //$offset = $match_start + $match_length;

            $offset = $match_start + strlen($replace);
        }



        //return $match_count;
        return "".$subject;


    }




}
