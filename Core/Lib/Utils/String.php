<?php

/**
 * Lib_Utils_String
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
 * Lib_Utils_String
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
class Lib_Utils_String
{

    /**
     * @static
     * @param  string $string
     * @param  string $delimiter
     * @param array(), $value
     * @param null|int $limit
     * @return array
     */
    public static function splitRecursive($string, $delimiter, $value = array(), $limit = null)
    {
        //$parts = explode(".","foo.bar.baz");
        if ($limit === null) {
            $parts = explode($delimiter, $string);
        } else {
            $parts = explode($delimiter, $string, $limit);
        }

        if (is_array($parts)!==true) {
            return array();
        }
        $parts = array_reverse($parts);
        $part = $value;
        foreach($parts as $partName) {
            $part = array($partName => $part);
        }
        return $part;
    }

    /**
     * @static
     * @param  string $delimiter
     * @param  array $list
     * @param null|int $limit
     * @return array
     */
    public static function explodeStringListRecursive(
        $delimiter,
        $list,
        $limit = null
    )
    {

        if (is_array($list)!==true) {
            return array();
        }

        $result = array();

        foreach($list as $string) {
            $tree = self::explodeRecursive($delimiter, $string, $limit);
            $result = array_merge_recursive($result, $tree);
            //$result = $tree;
        }

        return $result;

    }



	/**
	 * NOTICE: IS NOT UTF8-SAFE ! WILL BREAK IF YOU USE RUSSIAN GLYPHS!
	 * @static
	 * @throws Exception
	 * @param  $string
	 * @param  $allowChars
	 * @param null $maxChars
	 * @param bool $ignoreCase
	 * @return mixed|string
	 */
	public static function filter(
		$string,
		$allowChars,
		$maxChars = null,
		$ignoreCase = true
	)
	{
		if (is_string($string)!==true) {
			$message = "Parameter 'string' must be a string";
			$message .= " at method ".__METHOD__;
			throw new Exception(
				$message
			);
		}

		if ($allowChars === null) {
			return $string;
		}

		if (is_string($allowChars) !== true) {
			$message = "Parameter 'allowChars' must be a string or null";
			$message .= " at method ".__METHOD__;
			throw new Exception(
				$message
			);
		}

		$ignoreCase = (bool)$ignoreCase;

		//$allowChars = 'abcdefghijklmnopqrstuvwxyz[]';
		//$allowChars = '[a-z][]123';
		$allowChars = preg_quote($allowChars);
		$search = '/[^'.$allowChars.']/';
		if ($ignoreCase === true) {
			$search .= 'i';
		}
		$replace = '';

		$filtered = preg_replace($search, $replace, $string);

		if (is_string($filtered)!==true) {
			throw new Exception("Invalid result at ".__METHOD__.__LINE__);
		}

		if ($maxChars === null) {
			return $filtered;
		}

		if (is_int($maxChars)!==true) {
			$message = "Parameter 'maxChars' must be a int (greater 0)";
			$message .= " or null at method ".__METHOD__;
			throw new Exception($message);
		}

		if (($maxChars>0)!==true) {
			$message = "Parameter 'maxChars' must be a int (greater 0)";
			$message .= " or null at method ".__METHOD__;
			throw new Exception($message);
		}

		if (strlen($filtered) > 0) {
			$filtered = substr($filtered, 0, $maxChars);
		}

		if (is_string($filtered)!==true) {
			throw new Exception("Invalid result at ".__METHOD__.__LINE__);
		}

		return $filtered;
	}



	public static function isEmpty($string)
	{
        // kein string? => isEmpty!
        if (!is_string($string)) {
            return true;
        }

        // leer-string? => isEmpty!
        if (trim($string) === '') {
            return true;
        }

        return false;
	}



    /**
     * Binary safe string part comparison.
     * This function checks if a string ends with a given postfix.
     * <code>endsWith</code> is binary safe and per default case insensitive.
     * Use parameter $ignoreCase = false for a case sensitive comparison.
     *
     * @param \b String $str
     * @param \b String $postfix
     * @param \b boolean $ignoreCase
     * @return boolean
     */
    public static function endsWith($str, $postfix, $ignoreCase=true)
    {
        $result = false;
        if (is_string($str) !== true) {
            return $result;
        }
        if (is_string($postfix) !== true) {
            return $result;
        }
        
        $lenHaystack = strlen($str);
        $lenNeedle = strlen($postfix);

        $startOffset = (int) ($lenHaystack - $lenNeedle);

        if ($startOffset < 0)
                return false;

        $endStr = substr($str, $startOffset);

        if ($ignoreCase) {
                return (strcmp($postfix, $endStr) === 0 );
        } else {
                return (strcasecmp($postfix, $endStr) === 0 );
        }
    }

    /**
     * Binary safe string part comparison.
     * This function checks if a string starts with a given prefix.
     * <code>startsWith</code> is binary safe and per default case insensitive.
     * Use parameter $ignoreCase = false for a case sensitive comparison.
     *
     * @param \b String $str
     * @param \b String $postfix
     * @param \b boolean $ignoreCase
     * @return boolean
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
     * @param string $str
     * @param string $prefix
     * @param boolean $ignoreCase
     * @return string
     */
    public static function removePrefix($str, $prefix, $ignoreCase=true)
    {
        if (is_string($str) !== true) {
            return null;            
        }
        if (is_string($prefix) !== true) {
            return $str;            
        }

        if (self::startsWith($str, $prefix, $ignoreCase)) {

            $str = substr($str, strlen($prefix));

        }

        return $str;
    }
    
    /**
     * @param string $str
     * @param string $postfix
     * @param boolean $ignoreCase
     * @return string
     */
    public static function removePostfix($str, $postfix, $ignoreCase=true)
    {
        if (is_string($str) !== true) {
            return null;            
        }
        if (is_string($postfix) !== true) {
            return $str;            
        }

        if (self::endsWith($str, $postfix, $ignoreCase)) {
            $start = 0;
            if (strlen($str) >= strlen($postfix)) {
                $len = strlen($str)-strlen($postfix);
                $str = substr($str, $start, $len);
            }
        }
        return $str;
    }
    


	/**
	 * @static
	 * @param  string $string
	 * @param  string $delimiter
	 * @return string|null
	 */
	public static function getPostfixByDelimiter($string,$delimiter)
    {
		$result = null;
        if (!is_string($string)) {
			return $result;
		}
        if (!is_string($delimiter)) {
			return $result;
		}
        if (strpos($string, $delimiter) === FALSE) {
			return $result;
		}
        try {
            $parts = explode($delimiter, $string);
            $parts = (array)$parts;
			
			if ((count($parts)>=2)!==true) {
				return $result;
			}
            $postfix = array_pop($parts);
			if (is_string($postfix)!==true) {
				return $result;
			}
			$result = $postfix;
			return $result;
        }catch(Exception $e){
            //NOP//
			//var_dump($e);
        }
        return $result;
    }

    /**
     * @static
     * @param  string $string
     * @param  string $delimiter
     * @return null|string
     */
    public static function getPrefixByDelimiter($string, $delimiter)
    {
        try {

            $parts = explode($delimiter, $string);

            if ($parts === false) {

                return null;
            }

            if (count($parts) <= 1) {
                return null;
            }

            return $parts[0];

        } catch (Exception $e){
            
            return null;
        }
    }


	/**
	 * @static
	 * @param  string $string
	 * @param  string $delimiter
	 * @return null|string
	 */
	public static function getPrefixByDelimiterSlow($string, $delimiter)
	{
		$result = null;
        if (!is_string($string)) {
			return $result;
		}
        if (!is_string($delimiter)) {
			return $result;
		}
        if (strpos($string, $delimiter) === FALSE) {
			return $result;
		}
        try {
            $parts = explode($delimiter, $string);
            $parts = (array)$parts;

			if ((count($parts)>=2)!==true) {
				return $result;
			}

            $prefix = Lib_Utils_Array::getProperty($parts, 0, true);
			if (is_string($prefix)!==true) {
				return $result;
			}

			$result = $prefix;
			return $result;
        }catch(Exception $e){
            //NOP//
			//var_dump($e);
        }
        return $result;
	}



	public static function removePrefixByDelimiterIfExists($string,$delimiter)
	{
		$result = null;
        if (!is_string($string)) {
			return $result;
		}
        if (!is_string($delimiter)) {
			return $result;
		}
        if (strpos($string, $delimiter) === FALSE) {
			return $string;
		}

		$result = $string;
		
        try {
            $parts = explode($delimiter, $string);
            $parts = (array)$parts;

			if ((count($parts)>=2)!==true) {
				return $string;
			}

            array_shift($parts);

			$result = implode($delimiter, $parts);
			if (is_string($result)) {
				return $result;
			}

			return null;
        }catch(Exception $e){
            //NOP//
			//var_dump($e);
        }
        return $result;
	}


	public static function removePostfixByDelimiterIfExists($string,$delimiter)
	{
		$result = null;
        if (!is_string($string)) {
			return $result;
		}
        if (!is_string($delimiter)) {
			return $result;
		}
        if (strpos($string, $delimiter) === FALSE) {
			return $string;
		}

		$result = $string;

        try {
            $parts = explode($delimiter, $string);
            $parts = (array)$parts;

			if ((count($parts)>=2)!==true) {
				return $string;
			}

            array_pop($parts);

			$result = implode($delimiter, $parts);
			if (is_string($result)) {
				return $result;
			}

			return null;
        }catch(Exception $e){
            //NOP//
			//var_dump($e);
        }
        return $result;
	}

    /**
     * @static
     * @param string $string
     * @param int $maxCharLength
     * @param int $maxByteLength
     * @return string
     */
    public static function filterUtf8(
        $string, $maxCharLength=80, $maxByteLength=80
    )
    {

        // 1) strip out all non-iso convertable characters:

        $isoString = utf8_decode($string);

        // 2) strip illegal ISO characters

        $allowedChars='öäüÖÄÜßa-zA-Z0-9.,:;!"$%&/()=_+[]{}ß\'? -';
        $allowedCharsIso = utf8_decode($allowedChars);
        $filteredIsoString = preg_replace(
            '/[^'.$allowedCharsIso.']/', '', $isoString
        );

        // 3) ensure byte length

        $filteredIsoString = substr($filteredIsoString, 0, $maxByteLength);

        // 4) ensure character length

        $utf8String = utf8_encode($filteredIsoString);
        $utf8String = iconv_substr($utf8String, 0, $maxCharLength);

        return $utf8String;
    }

    /**
     * @param string $str
     * @return void
     */
    public static function cleanForSimpleSearch($str)
    {
        // 1st: pad, lowercase
        $str = ' ' . strtolower($str) . ' ';

        // replace some special characters:

        $search  =
            array('ö',  'ä',  'ü',  'Ö',  'Ä',  'Ü',  'ß',  "'", '.', ';', '-');
        $replace =
            array('oe', 'ae', 'ue', 'oe', 'äe', 'ue', 'ss', '',  ' ', ' ', ' ');

        $str = str_replace(
            $search,
            $replace,
            $str
        );

        // remove excess spaces

        $str = preg_replace("/  +/", " ", $str);

        return $str;
    }

}


