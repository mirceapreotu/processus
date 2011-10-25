<?php
/**
 * Lib_Utils_CryptRC4
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
 * Lib_Utils_CryptRC4
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
 
 
class Lib_Utils_CryptRC4
{

    /**
     * @static
     * @param  $str
     * @return string
     */
    public static function hex2data($str)
	{
	    $p = '';
	    for ($i=0; $i < strlen($str); $i=$i+2)
	    {
	        $p .= chr(hexdec(substr($str, $i, 2)));
	    }
	    return $p;
	}

    /**
     * @static
     * @param  $str
     * @return string
     */
	public static function data2hex($str)
	{
		$out = bin2hex($str);
		return $out;
	}

    /**
     * @static
     * @param  $key
     * @param  $pt
     * @return int|string
     */
	public static function rc4Encrypt($key, $pt)
    {
		$s = array();
		for ($i=0; $i<256; $i++) {
			$s[$i] = $i;
		}
		$j = 0;
		//$x;
		for ($i=0; $i<256; $i++) {
			$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
		}
		$i = 0;
		$j = 0;
		$ct = '';
		//$y;
		for ($y=0; $y<strlen($pt); $y++) {
			$i = ($i + 1) % 256;
			$j = ($j + $s[$i]) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
			$ct .= $pt[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		}
		return $ct;
	}



}
