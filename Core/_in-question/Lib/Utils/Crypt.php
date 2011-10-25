<?php
/**
 * Lib_Utils_Crypt
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
 * Lib_Utils_Crypt
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


class Lib_Utils_Crypt
{
    /**
     * @static
     * @var Lib_Utils_Crypt;
     */

    private static $_instance;



    /**
     * @var string
     */
    protected $_cryptSalt;

    /**
     * @param  string|mixed $salt
     * @return void
     */
    public function setCryptSalt($salt)
    {
        $this->requireInstanceIsNotSingleton(__METHOD__);
        $this->_cryptSalt = strtolower((string)$salt);

    }

    /**
     * @return string
     */
    public function getCryptSalt()
    {
        return (string)$this->_cryptSalt;
    }

	/**
	 * @var array|null
	 */
	protected $_errors;

	/**
	 * @return array
	 */
	public function getErrors()
	{
		if (is_array($this->_errors)!==true) {
			$this->_errors = array();
		}
		return $this->_errors;
	}

	/**
	 * @param  Exception $error
	 * @return void
	 */
	public function addError($error)
	{
		if (is_array($this->_errors)!==true) {
			$this->_errors = array();
		}
		$this->_errors[] = $error;
	}

	/**
	 * @return void
	 */
	public function resetErrors()
	{
		$this->_errors = array();
	}


    /**
     * @return mixed|null
     */
	public function getLastError()
	{
		$errors = $this->getErrors();
		$errors = (array)array_reverse($errors);
		$lastError = Lib_Utils_Array::getProperty($errors, 0, true);
		return $lastError;
	}


	/**
	 * @return string
	 */
	public function getCryptSecret()
	{
        $secret =
            "kqM6V1SRyTbI0ByDtkVU3rYmZM_P6J2Z4f3NTrBDqpUq-iqslMBZDmouFtbGDpp9";
        $salt = $this->getCryptSalt();

        $applicationPrefix = Bootstrap::getRegistry()->getApplicationPrefix();

        //var_dump($salt);
		$value = $secret.$salt.$applicationPrefix;

		$value = md5($value);
		return $value;
	}


	/**
	 * @param  mixed $value
	 * @return null|string
	 */
	public function encryptValue($value)
	{
		try {
			$this->resetErrors();
			$_value = array($value);
			$text = json_encode($_value);
			if (is_string($text)!==true) {
				throw new Exception("Encode Failed! at ".__METHOD__.__LINE__);
			}

            $encryptedText = $this->encryptRijndaelBase64(
				$this->getCryptSecret(),
				$text
			);
			if (is_string($text)!==true) {
				throw new Exception("Encrypt failed! at ".__METHOD__.__LINE__);
			}
			return $encryptedText;
		} catch(Exception $exception) {
            $this->addError($exception);
			return null;
		}
	}

	/**
	 * @param  string $text
	 * @return null|mixed
	 */
	public function decryptValue($text,$jsonAssoc = false)
	{
		try {
			$this->resetErrors();
			if (is_string($text)!==true) {
				throw new Exception("Decrypt failed! at ".__METHOD__.__LINE__);
			}
			$decryptedText = $this->decryptRijndaelBase64(
				$this->getCryptSecret(),
				$text
			);
			if (is_string($decryptedText)!==true) {
				throw new Exception("Decrypt failed! at ".__METHOD__.__LINE__);
			}

			$array = json_decode($decryptedText, $jsonAssoc);

			if (is_array($array)!==true) {
				throw new Exception("Decrypt failed! at ".__METHOD__.__LINE__);
			}

			$value = Lib_Utils_Array::getProperty($array, 0, true);

			return $value;
		} catch(Exception $exception) {
			$this->addError($exception);
			return null;
		}
	}

	/**
	 * @throws Exception
	 * @param  string $key
	 * @param  string $text
	 * @return string
	 */
	public function encryptRijndaelBase64($key, $text)
    {

        if (is_string($key) !== true) {
            $message = "Parameter 'key' must be a string! at " . __METHOD__;
            throw new Exception($message);
        }
        if (is_string($text) !== true) {
            $message = "Parameter 'text' must be a string! at " . __METHOD__;
            throw new Exception($message);
        }
        //neu
        $text = base64_encode($text);

        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $text =
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256,
                    $key,
                    $text,
                    MCRYPT_MODE_ECB,
                    $iv
                );
        //$text = base64_encode($text);
        $text = Lib_Utils_Base64::encodeUrlSafe($text);

        return $text;
    }

	/**
	 * @static
	 * @throws Exception
	 * @param  string $key
	 * @param  string $text
	 * @return string
	 */
	public static function decryptRijndaelBase64($key, $text)
	{
		if (is_string($key)!==true) {
			$message = "Parameter 'key' must be a string! at ".__METHOD__;
			throw new Exception($message);
		}
		if (is_string($text)!==true) {
			$message = "Parameter 'text' must be a string! at ".__METHOD__;
			throw new Exception($message);
		}

		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);

		//$text = base64_decode($text);
        $text = Lib_Utils_Base64::decodeUrlSafe($text);
		$text =
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_256,
				$key,
				$text,
				MCRYPT_MODE_ECB,
				$iv
			);

		//neu
		$text = base64_decode($text);

		//var_dump($text);
		return $text;
	}

    /**
     * @throws Exception
     * @param  string $errorMethod
     * @return void
     */
    public function requireInstanceIsNotSingleton($errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        $singleton = self::getInstance();
        if ($this === $singleton) {
            $message = "Method ".$errorMethod
                    . " is not invokable by singleton instance! at ".__METHOD__;
            throw new Exception($message);
        }

    }


    /**
     * @static
     * @return Lib_Utils_Crypt
     */
    public static function getInstance()
    {
        $instance = self::$_instance;
        if (((is_object($instance)) && ($instance !== null)) !==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @static
     * @param  mixed $value
     * @param bool $jsonAssoc
     * @return string
     */
    public static function decrypt($value, $jsonAssoc = false)
    {
        return self::getInstance()->decryptValue($value, $jsonAssoc);
    }

    /**
     * @static
     * @param  $value
     * @return null|string
     */
    public static function encrypt($value)
    {
        return self::getInstance()->encryptValue($value);
    }
}
