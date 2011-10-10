<?php
/**
 * App_Model_Dto_Utils_Crypt
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dto_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Dto_Utils_Crypt
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dto_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class App_Model_Dto_Utils_Crypt
{
    /**
     * @return bool
     */
    public function isCryptEnabled()
    {
        if (Bootstrap::getRegistry()
                ->isServerStage(
                    Bootstrap::SERVER_STAGE_DEVELOPMENT
                )
        ) {
            return false;
        }

        return true;
    }

    /**
     * @var App_Model_Dto_Utils_Crypt
     */
    private static $_instance;

     /**
     * @static
     * @return App_Model_Dto_Utils_Crypt
     */
    public static function getInstance()
    {
        if (is_object(self::$_instance)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    /**
     * @var Lib_Utils_Crypt
     */
    private $_cryptUtil;


    /**
     * @return Lib_Utils_Crypt
     */
    public function getCryptUtil()
    {
        if (($this->_cryptUtil instanceof Lib_Utils_Crypt)!==true) {
            $this->_cryptUtil = new Lib_Utils_Crypt();
        }
        return $this->_cryptUtil;
    }


    /**
     * @param  mixed $value
     * @param  string|null $salt
     * @return null|string
     */
    public function encryptValue($value, $salt)
    {
        if ($this->isCryptEnabled() !== true) {
            return $value;
        }
        //return $value;
        $cryptUtil = $this->getCryptUtil();
        $cryptUtil->setCryptSalt($salt);
        return $cryptUtil->encryptValue($value);
    }

    /**
     * @param  mixed $value
     * @param  string|mixed $salt
     * @param bool $jsonAssoc
     * @return mixed|null
     */
    public function decryptValue($value, $salt, $jsonAssoc = false)
    {
        if ($this->isCryptEnabled() !== true) {
            return $value;
        }
        //$salt = "FOO";
        //return $value;
        $cryptUtil = $this->getCryptUtil();
        $cryptUtil->setCryptSalt($salt);
        return $cryptUtil->decryptValue($value, $jsonAssoc);        
    }




}
