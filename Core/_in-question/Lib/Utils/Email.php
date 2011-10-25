<?php

/**
 * Lib_Utils_Email
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
 * Lib_Utils_Email
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
class Lib_Utils_Email
{



		
    /**
     * @see: http://www.linuxjournal.com/article/9585
     * @static
     * @param  string|mixed $email
     * @param bool $checkDNS
     * @return bool
     */
	public static function isValidAddress(
        $email,
        $checkDNS = true
    )
	{
		try {
		   $isValid = true;

		   if (empty($email)) {
               return false;
           }
		   if (is_string($email)!==true) {
               return false;
           }

		   $atIndex = strrpos($email, "@");
		   if (is_bool($atIndex) && !$atIndex) {
		      return false;
		   } else {
		      $domain = substr($email, $atIndex+1);
		      $local = substr($email, 0, $atIndex);
		      $localLen = strlen($local);
		      $domainLen = strlen($domain);
		      if ($localLen < 1 || $localLen > 64) {
		         // local part length exceeded
		         $isValid = false;
		      }
		      else if ($domainLen < 1 || $domainLen > 255) {
		         // domain part length exceeded
		         $isValid = false;
		      }
		      else if ($local[0] == '.' || $local[$localLen-1] == '.') {
		         // local part starts or ends with '.'
		         $isValid = false;
		      }
		      else if (preg_match('/\\.\\./', $local)) {
		         // local part has two consecutive dots
		         $isValid = false;
		      }
		      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
		         // character not valid in domain part
		         $isValid = false;
		      }
		      else if (preg_match('/\\.\\./', $domain)) {
		         // domain part has two consecutive dots
		         $isValid = false;
		      }
		      else if
				(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
		                 str_replace("\\\\","",$local))) {
		         // character not valid in local part unless
		         // local part is quoted
		         if (!preg_match('/^"(\\\\"|[^"])+"$/',
		             str_replace("\\\\","",$local))) {
		            $isValid = false;
		         }
		      }

		      if ($checkDNS === true) {
			      if (
                        $isValid
                        && !(checkdnsrr($domain,"MX")
                        || checkdnsrr($domain,"A"))) {
			         // domain not found in DNS
			         $isValid = false;
			      }
		      }
		   }
		   return (bool)$isValid;
		}catch(Exception $e){

		}
		return false;
	}



}


