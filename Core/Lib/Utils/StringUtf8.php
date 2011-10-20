<?php

/**
 * Lib_Utils_StringUtf8
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
 * Lib_Utils_StringUtf8
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
class Lib_Utils_StringUtf8
{


    /**
     * @static
     * @return string
     */
    public static function getTestString()
    {
         $text = "? ÖABCÄÖÜß123.!"
            . "This is the Euro symbol '€'."
            . "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏ"
            . "Две правильно насколько"
            . "123456#$%&'()*+,-./0123456789:;<=>?"
            . "@[\]^_`{|}~¡¢£¤¥¦§¨©ª«¬®¯°"
            . "±²³´µ¶·¸¹º»¼½¾÷–—‰‹›⁄€™";

         return $text;
    }


    //http://blogs.adobe.com/typblography/latin_charsets/Adobe_Latin_1.html
	public static function getGlyphsAdobeLatin1()
	{
		$glyphs = ' !"';
		$glyphs .= "#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$glyphs .= "[\]^_`abcdefghijklmnopqrstuvwxyz";
        $glyphs .= "{|}~¡¢£¤¥¦§¨©ª«¬®¯°±";
        $glyphs .= "²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅ";
		$glyphs .= "ÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝ";
        $glyphs .= "Þßàáâãäåæçèéêëìíîïðñòóôõö";
        $glyphs .= "÷øùúûüýþÿıŁłŒœŠšŸŽžƒˆˇ˘˙˚";
        $glyphs .= "˛˝–—‘’‚“”„†‡";
        $glyphs .= "•…‰‹›⁄€™−ﬁﬂ";
		return $glyphs;
	}

    /**
     * @static
     * @return string
     */
    public static function getGlyphsAlphaGerman()
    {
        return "abcdefghijklmnopqrstuvwxyzäöüß";
    }

    /**
     * @static
     * @return string
     */
    public static function getGlyphsAlphaNumGerman()
    {
        return "abcdefghijklmnopqrstuvwxyzäöüß012345679";
    }

    /**
     * @static
     * @return string
     */
    public static function getGlyphsPunct()
    {
        // @see: http://w3.pppl.gov/info/grep/Regular_Expressions.html
        $glyphs = "'";
        $glyphs .= '"';
        $glyphs .= "!#%&'();<=>?[\]*+,-./:^_{|}";
        return $glyphs;
    }

    /**
     * @static
     * @return string
     */
    public static function getGlyphsWhiteSpace()
    {
        //TODO: TEST !!!!
        $glyphs="\t\n\r\f";
        return $glyphs;
    }


    /**
     * @static
     * @return string
     */
    public static function getInternalEncoding()
    {
         $defaultEncoding = "ISO-8859-15";
         return $defaultEncoding;
    }


    /**
     * @static
     * @return array
     */
    public static function getEncodingList()
    {
        $encodingList = array(
            "UTF-8",
            "ISO-8859-15", //mit €
            "ISO-8859-1" // ohne €
        );
        return $encodingList;
    }

    /**
     * @static
     * @return array
     */
    public static function getMbEncodingList()
    {
        $encodingList = self::getEncodingList();
        $mbList = array("ASCII", "JIS", "UTF-8", "EUC-JP", "SJIS");
        $result = array_merge($encodingList, $mbList);
        return $result;
    }


    /**
     * @static
     * @param  string|mixed $text
     * @return bool
     */
    public static function isUtf8($text)
    {
        if (is_string($text)!==true) {
            return false;
        }

        try {
            $dummy = iconv("UTF-8", "UTF-8", $text);
            return true;

        } catch (Exception $e) {
            // iconv detected an illegal character
            return false;
        }

        return false;
    }


    /**
     * @static
     * @throws Exception
     * @param  string $text
     * @return null|string
     */
    public static function detectEncoding($text)
    {
        if (is_string($text) !== true) {
            $message = "Parameter text must be a string at ".__METHOD__;
            throw new Exception($message);
        }
        $encodingList = self::getEncodingList();
        $encoding = mb_detect_encoding($text, $encodingList);

        if ($encoding==="UTF-8") {
            $isUtf8 = self::isUtf8($text);
            if ($isUtf8===true) {
                return "UTF-8";
            }
            return null;
        }

        return $encoding;
    }

    public static function toUtf8($text, $translit)
    {

        if (is_string($text)!==true) {
            $message = "Parameter text must be a string at ".__METHOD__;
            throw new Exception($message);
        }
        if (is_bool($translit)!==true) {
            $message = "Parameter translit must be a bool at ".__METHOD__;
            throw new Exception($message);
        }
        if (strlen($text)===0) {
            return "";
        }


        if (self::isUtf8($text)) {
           return $text;
        }


        $result = null;

        $detectedEncoding = self::detectEncoding($text);
        if ((is_string($result)!==true) && ($detectedEncoding !== null)) {
            if ($translit === true) {
                try {
                    $result = iconv(
                        $detectedEncoding, "UTF-8//TRANSLIT", $text
                    );
                } catch (Exception $e) {
                }
            }
        }

        if ((is_string($result)!==true) && ($detectedEncoding !== null)) {

            try {
                $mbEncodingList = self::getMbEncodingList();
                $result = mb_convert_encoding($text, "UTF-8", $mbEncodingList);
            } catch (Exception $e) {
            }
        }

        if (is_string($result)!==true) {
            $result = utf8_encode($text);
        }

        if (is_string($result)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }
        return $text;


    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @return array
     */
    public static function split($text)
    {
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }
        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );

        }
        # Split at all position not after the start: ^
        # and not before the end: $
        return preg_split('/(?<!^)(?!$)/u', $text);
    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @param string|null $targetEncoding
     * @return array
     */
    public static function findIllegalCharacters($text, $targetEncoding = null)
    {
        $defaultEncoding = self::getInternalEncoding();
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }
        if ($targetEncoding === null) {
            $targetEncoding = $defaultEncoding;
        }
        if (Lib_Utils_String::isEmpty($targetEncoding)) {
            throw new Exception(
                "Parameter targetEncoding must be a string and not "
                . "empty or null at ".__METHOD__
            );
        }
        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );

        }
        $charList = self::split($text);
        $index = -1;
        $result = array();
        foreach ($charList as $char) {
            $index++;
            try {
                $plain =  iconv(
                    mb_detect_encoding($char), "".$targetEncoding."", $char
                );
            }catch(Exception $e) {
                $result[$index] = $char;
            }
        }
        return $result;
    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @param string|null $targetEncoding
     * @return string
     */
    public static function removeIllegalCharacters(
        $text, $targetEncoding = null
    )
    {
        $defaultEncoding = self::getInternalEncoding();
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }
        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }
        if ($targetEncoding === null) {
            $targetEncoding = $defaultEncoding;
        }
        if (Lib_Utils_String::isEmpty($targetEncoding)) {
            throw new Exception(
                "Parameter targetEncoding must be a string and not "
                . "empty or null at ".__METHOD__
            );
        }

        $charList = self::split($text);
        $index = -1;
        $result = "";
        foreach ($charList as $char) {
            $index++;
            try {
                $plain =  iconv(
                    mb_detect_encoding($char), "".$targetEncoding."", $char
                );
                $result .= $char;
            } catch (Exception $e) {

            }
        }
        return $result;
    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @param int $maxChars
     * @return string
     */
    public static function ensureMaxChars($text, $maxChars)
    {
        if (((is_int($maxChars)) && ($maxChars>0)) !== true) {
            throw new Exception(
                "Parameter mChars must be int>0 at ".__METHOD__
            );
        }

        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }
        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }

        $charList = self::split($text);
        $charNo = 0;
        $result = "";
        foreach ($charList as $char) {
            $charNo++;
            if ($charNo<=$maxChars) {
                $result .= $char;
            } else {
                break;
            }

        }
        return $result;

    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @param int $maxBytes
     * @return string
     */
    public static function ensureMaxBytes($text, $maxBytes)
    {
        if (((is_int($maxBytes)) && ($maxBytes>0)) !== true) {
            throw new Exception(
                "Parameter maxBytes must be int>0 at ".__METHOD__
            );
        }

        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }

        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }

        $charList = self::split($text);
        $charNo = 0;
        $result = "";
        foreach ($charList as $char) {
            $charNo++;

            $_text = $result.$char;
            if (strlen($_text)<=$maxBytes) {
                $result = $_text;
            } else {
                break;
            }

        }
        return $result;
    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @return int|void
     */
    public static function countChars($text)
    {
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }

        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }

        $charList = self::split($text);
        return count($charList);
    }

    /**
     * @static
     * @throws Exception
     * @param string $text
     * @return int
     */
    public static function countBytes($text)
    {
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }

        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }

        return (int)strlen($text);
    }


    /**
     * @static
     * @throws Exception|Exception
     * @param  $text
     * @param null|string $allowableTags
     * @param null|string $targetEncoding
     * @return string
     */
    public static function stripTags(
        $text,
        $allowableTags = null,
        $targetEncoding = null
    )
    {
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }

        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }

        $defaultEncoding = self::getInternalEncoding();

        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }

        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }
        if ($targetEncoding === null) {
            $targetEncoding = $defaultEncoding;
        }
        if (Lib_Utils_String::isEmpty($targetEncoding)) {
            throw new Exception(
                "Parameter targetEncoding must be a string and not "
                . "empty or null at ".__METHOD__
            );
        }

        $result = null;
        try {
            $iso =  iconv("UTF-8", $targetEncoding, $text);
            $iso = strip_tags($iso, $allowableTags);
            $utf8 = iconv($targetEncoding, "UTF-8", $iso);
            $result = $utf8;
        } catch (Exception $e) {
            throw new Exception(
                "Parameter text contains invalid chars at ".__METHOD__
            );
        }

        if (self::isUtf8($result)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }

        return $result;
    }

    /**
     * @static
     * @throws Exception|Exception
     * @param  string $text
     * @param null|string $targetEncoding
     * @param null|string $allowChars
     * @param null|bool $ignoreCase
     * @return string
     */
    public static function filter(
        $text,
        $targetEncoding = null,
        $allowChars = null,
        $ignoreCase = null
    )
    {


        $defaultEncoding = self::getInternalEncoding();
        if (is_string($text)!==true) {
            throw new Exception(
                "Parameter text must be a string at ".__METHOD__
            );
        }
        if (self::isUtf8($text)!==true) {
            throw new Exception(
                "Parameter text must be a utf8-encoded string at ".__METHOD__
            );
        }
        if ($targetEncoding === null) {
            $targetEncoding = $defaultEncoding;
        }
        if (Lib_Utils_String::isEmpty($targetEncoding)) {
            throw new Exception(
                "Parameter targetEncoding must be a string and not empty or "
                . "null at ".__METHOD__
            );
        }

        if ($ignoreCase === null) {
            $ignoreCase = false;
        }

        if (is_bool($ignoreCase)!==true) {
            throw new Exception(
                "Parameter ignoreCase must be a bool or null at ".__METHOD__
            );
        }

        if (((is_string($allowChars)) || ($allowChars===null))!==true) {
            throw new Exception(
                "Parameter allowChars must be a string or null at ".__METHOD__
            );
        }


        try {
            $text = self::removeIllegalCharacters($text, $targetEncoding);
        } catch (Exception $e){
            $message = "Error while trying to remove invalid characters at "
                . __METHOD__;
            $message .= " reason: ".$e->getMessage();
            throw new Exception($message);
        }

        if (is_string($allowChars) !== true) {
            $result = $text;
            if (self::isUtf8($result)!==true) {
                throw new Exception(
                    "Method returns invalid result at ".__METHOD__
                );
            }
            return $result;
        }
        if ($ignoreCase===true) {
            $allowCharsUpper = mb_strtoUpper($allowChars, "UTF-8");
            $allowCharsLower = mb_strtolower($allowChars, "UTF-8");
            $allowChars .= $allowCharsLower;
            $allowChars .= $allowCharsUpper;
            $allowCharsList = self::split(
                $allowChars.$allowCharsLower.$allowCharsUpper
            );
            $allowCharsList = array_unique($allowCharsList);
            
        } else {
            $allowCharsList = self::split($allowChars);
            $allowCharsList = array_unique($allowCharsList);
        }
        $charList = self::split($text);
        $result = "";

        foreach ($charList as $char) {

            if (in_array($char, $allowCharsList, true)) {
                $result .= $char;
            }
        }

        if (self::isUtf8($result) !== true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }

        return $result;

    }





    /**
      * @static
      * @throws Exception
      * @param  string|mixed $text
      * @param null $targetEncoding
      * @param null|string $allowChars
      * @param null|bool $ignoreCase
      * @return bool
      */
    public static function isInvalid(
        $text, $targetEncoding = null, $allowChars = null, $ignoreCase = null
    )
    {
        $result = true;
        $defaultEncoding = self::getInternalEncoding();
        if (is_string($text) !== true) {
            return $result;
        }
        if (self::isUtf8($text) !== true) {
            return $result;
        }
        if ($targetEncoding === null) {
            $targetEncoding = $defaultEncoding;
        }
        if (Lib_Utils_String::isEmpty($targetEncoding)) {
            throw new Exception(
                "Parameter targetEncoding must be a string and not empty or "
                . "null at " . __METHOD__
            );
        }

        if ($ignoreCase === null) {
            $ignoreCase = false;
        }

        if (is_bool($ignoreCase) !== true) {

            throw new Exception(
                "Parameter ignoreCase must be a bool or null at " . __METHOD__
            );
        }

        if (((is_string($allowChars)) || ($allowChars === null)) !== true) {
            throw new Exception(
                "Parameter allowChars must be a string or null at "
                . __METHOD__
            );
        }


        try {
            $filtered = self::filter(
                $text, $targetEncoding, $allowChars, $ignoreCase
            );

            if (is_string($filtered) !== true) {
                return $result;
            }

            if (self::countBytes($text) !== self::countBytes($filtered)) {
                return $result;
            }

            if (self::countChars($text) !== self::countChars($filtered)) {
                return $result;
            }

            return false;
        } catch (Exception $e) {
            //NOP

        }

        return $result;
    }


}
