<?php


/**
 * Lib_Utils_Id
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
 * Lib_Utils_Id
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
class Lib_Utils_Id
{

     private static $map = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
        'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31





        '='  // padding char
    );

   private static $flippedMap = array(
        'A'=>'0', 'B'=>'1', 'C'=>'2', 'D'=>'3', 'E'=>'4', 'F'=>'5', 'G'=>'6', 'H'=>'7',
        'I'=>'8', 'J'=>'9', 'K'=>'10', 'L'=>'11', 'M'=>'12', 'N'=>'13', 'O'=>'14', 'P'=>'15',
        'Q'=>'16', 'R'=>'17', 'S'=>'18', 'T'=>'19', 'U'=>'20', 'V'=>'21', 'W'=>'22', 'X'=>'23',
        'Y'=>'24', 'Z'=>'25', '2'=>'26', '3'=>'27', '4'=>'28', '5'=>'29', '6'=>'30', '7'=>'31',




    );


    /**
     * NOTICE: DOES NOT WORK FOR VERY BIG NUMBERS !!!!
     * on my 32bit pc: "1234567890123456"  works, but not more digits
     * @static
     * @throws Exception
     * @param  $id
     * @param null $password
     * @return string|null
     */
    public static function shorten($id, $password , $validate)
    {
        $padLength = null;
        // password AND padding -> decode: wrong results!

        if (is_numeric($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        if ($padLength !== null) {
            if (is_numeric($padLength)!==true) {
                throw new Exception("Invalid parameter 'padLength' at "
                                    .__METHOD__);
            }
        }

        if ($password !== null) {
            if (((is_string($password))||(is_numeric($password)))!==true) {
                throw new Exception("Invalid parameter 'password' at "
                                    .__METHOD__);

            }
        }

        if (is_bool($validate)!==true) {
            throw new Exception("Invalid parameter 'validate' at ".__METHOD__);
        }


        if ($padLength === null) {
            $padLength = false;
        }
        $result = self::_alphaId($id, false, $padLength, $password);

        if (is_string($result)!==true) {
            return null;
        }

        if ($validate === true) {
            $text = self::unshorten($result, $password);

            if (("".$id) !== ("".$text)) {
                throw new Exception("Method returns invalid result at "
                                    .__METHOD__
                );
            }


        }


        return $result;

    }

    /**
     * @static
     * @throws Exception
     * @param  $text
     * @param null $password
     * @return int|number|string
     */
    public static function unshorten($text, $password = null)
    {

        $padLength = null;
        // password AND padding -> decode: wrong results!
        if (is_string($text)!==true) {
            throw new Exception("Invalid parameter 'text' at ".__METHOD__);
        }

        if ($padLength !== null) {
            if (is_numeric($padLength)!==true) {
                throw new Exception("Invalid parameter 'padLength' at "
                                    .__METHOD__);
            }
        }

        if ($password !== null) {
            if (((is_string($password))||(is_numeric($password)))!==true) {
                throw new Exception("Invalid parameter 'password' at "
                                    .__METHOD__);

            }
        }

        if ($padLength === null) {
            $padLength = false;
        }
        $result = self::_alphaId($text, true, $padLength, $password);
        return $result;

    }







    /**
     * @static
     * @param  $in
     * @param bool $to_num
     * @param bool $pad_up
     * @param null $passKey
     * @return int|number|string
     */
    protected static function _alphaId(
        $in,
        $to_num = false,
        $pad_up = false,
        $passKey = null
    )
    {
        //@see: https://github.com/BlackMac/sl-shortener/blob/master/alphaid.inc.php

        $index =
            "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $passhash = hash('sha256', $passKey);
            $passhash = (strlen($passhash) < strlen($index))
                    ? hash('sha512', $passKey)
                    : $passhash;

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($passhash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        $base = strlen($index);

        if ($to_num) {
            // Digital number  <<--	 alphabet letter code
            $in = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number  -->>	 alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }





    /**
     * this one works without math,
     * e.g. : $in = "123456789012345678901234567890";
     *    Use padding false when encoding for urls
     *
     * @return base32 encoded string
     * @author Bryan Ruiz
     **/
    public static function base32encode($input, $padding = true) {
        if(empty($input)) return "";
        $input = str_split($input);
        $binaryString = "";
        for($i = 0; $i < count($input); $i++) {
            $binaryString .= str_pad(base_convert(ord($input[$i]), 10, 2)
                , 8, '0', STR_PAD_LEFT);
        }
        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32 = "";
        $i=0;
        while($i < count($fiveBitBinaryArray)) {
            $base32 .= self::$map[base_convert(str_pad($fiveBitBinaryArray[$i]
                        , 5,'0'), 2, 10)];
            $i++;
        }
        if($padding && ($x = strlen($binaryString) % 40) != 0) {
            if($x == 8) $base32 .= str_repeat(self::$map[32], 6);
            else if($x == 16) $base32 .= str_repeat(self::$map[32], 4);
            else if($x == 24) $base32 .= str_repeat(self::$map[32], 3);
            else if($x == 32) $base32 .= self::$map[32];
        }
        return $base32;
    }

    public static function base32decode($input) {
        if(empty($input)) return;
        $paddingCharCount = substr_count($input, self::$map[32]);
        $allowedValues = array(6,4,3,1,0);
        if(!in_array($paddingCharCount, $allowedValues)) return false;
        for($i=0; $i<4; $i++){
            if($paddingCharCount == $allowedValues[$i] &&
                substr($input, -($allowedValues[$i])) !=
                str_repeat(self::$map[32], $allowedValues[$i])) return false;
        }
        $input = str_replace('=','', $input);
        $input = str_split($input);
        $binaryString = "";
        for($i=0; $i < count($input); $i = $i+8) {
            $x = "";
            if(!in_array($input[$i], self::$map)) return false;
            for($j=0; $j < 8; $j++) {
                $x .= str_pad(base_convert(
                                  self::$flippedMap[$input[$i + $j]], 10, 2),
                              5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for($z = 0; $z < count($eightBits); $z++) {
                $binaryString .= ( ($y = chr(
                    base_convert($eightBits[$z], 2, 10))) || ord($y) == 48
                ) ? $y:"";
            }
        }
        return $binaryString;
    }





}


