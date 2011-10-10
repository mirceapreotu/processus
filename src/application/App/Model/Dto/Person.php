<?php
/**
 * App_Model_Dto_Person
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dto
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Dto_Person
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dto
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class App_Model_Dto_Person extends App_Model_Dto_DtoAbstract
{
     /**
     * @var null|string
     */
    protected $_cryptSalt = "Person";

    protected $_cryptEnabled = true;


     /** override
     * @return bool
     */
    public function isCryptEnabled()
    {
        if (Bootstrap::getRegistry()->isCmsContext()) {
            return false;
        }

        return (bool)parent::isCryptEnabled();
    }


    /**
     * @param  string $value
     * @return void
     */
    public function setFirstname($value)
    {
        $this->firstname = $value;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setLastname($value)
    {
        $this->lastname = $value;
    }

    /**
     * @param  $value
     * @return void
     */
    public function setDisplayName($value)
    {
        $this->displayName = $value;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setImageUrl($value)
    {
       
        if (Lib_Utils_String::isEmpty($value)) {
            unset($this->imageUrl);
            return;
        }
        $this->imageUrl = $value;
        // flash pulls facebook content: use proxy !
        //$this->useImageProxy = true;

    }


    /**
     * @param  array|null $value
     * @return
     */
    public function setImageUrls($value)
    {
        if (is_array($value)!==true) {
            unset($this->imageUrls);
            return;
        }

        $this->imageUrls = $value;

    }



    /**
     * @param  string $value
     * @return void
     */
    public function setProfileUrl($value)
    {
        $this->profileUrl = $value;
    }

    /**
     * @param  bool $value
     * @return void
     */
    public function setIsFriend($value)
    {
        $this->isFriend = $value;
    }


    /**
     * @param  bool $value
     * @return void
     */
    public function setIsViewer($value)
    {
        $this->isViewer = ($value===true);
    }

    public function setIsRegistered($value)
    {
        if (is_bool($value) !== true) {
            unset($this->isRegistered);
        }
        $this->isRegistered = (bool)$value;
    }



    // additiona for cms

    /**
     * @param  string $value
     * @return void
     */
    public function setCity($value)
    {
        $this->city = (string)$value;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setZipcode($value)
    {
        $this->zipcode = (string)$value;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setStreetAddress($value)
    {
        $this->streetAddress = (string)$value;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setCountry($value)
    {
        $this->country = (string)$value;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setEmail($value)
    {
        $this->email = (string)$value;
    }

    /**
     * @param  string|bool $value
     * @return void
     */
    public function setAgb($value)
    {
        $this->agb = Lib_Utils_TypeCast_String::asBool($value, null);
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setExternalKey($value)
    {
        $this->externalKey = (string)$value;
    }







}
