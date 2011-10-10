<?php
/**
 * App_Model_Record_Person
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Record
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Record_Person
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Model_Record
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

class App_Model_Record_Person extends App_Model_Record_Abstract
{

    const DB_TABLE = "Person";

	/**
	 * override
	 * @return string
	 */
	public function getDbTable()
	{
		return self::DB_TABLE;
	}


	/**
	 * override
	 * @return array
	 */
	public function getDbFields()
	{

        return array(
			"id",// bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            "firstname",
            "lastname",
            "displayname",
            "imageUrl", //
            "profileUrl", //
            "email", //
            "agb", //
            "isDeleted", //
            "isBlocked", //
            "created",
            "modified",

		);
	}

    public $id;
    public $firstname;
    public $lastname;
    public $displayName;
    public $imageUrl;
    public $profileUrl;
    public $email;
    public $agb;
    public $isDeleted;
    public $isBlocked;
    public $created;
    public $modified;





	/**
	 * @return void
	 */
	public function parse()
	{

		$this->setId($this->getDbRowFieldValue("id"));
        $this->setFirstname($this->getDbRowFieldValue("firstname"));
        $this->setLastname($this->getDbRowFieldValue("lastname"));
        $this->setDisplayName($this->getDbRowFieldValue("displayName"));
        $this->setImageUrl($this->getDbRowFieldValue("imageUrl"));
        $this->setImageUrl($this->getDbRowFieldValue("profileUrl"));
        $this->setEmail($this->getDbRowFieldValue("email"));
        $this->setAgb($this->getDbRowFieldValue("agb"));
        $this->setIsDeleted($this->getDbRowFieldValue("isDeleted"));
        $this->setIsBlocked($this->getDbRowFieldValue("isBlocked"));
        $this->setCreated($this->getDbRowFieldValue("created"));
        $this->setModified($this->getDbRowFieldValue("modified"));

	}

	/**
	 * @return bool
	 */
	public function exists()
	{
		return (((int)$this->getId())>0);
	}



    /**
     * @return bool
     */
    public function isRegistered()
    {
        if ($this->exists() !== true) {
            return false;
        }
        /*
        if ($this->isDeleted === true) {
            return false;
        }
         
         */
        return true;
    }



	/**
	 * @param  string|int $value
	 * @return void
	 */
	public function setId($value)
	{
		$defaultValue = null;

		$this->id = Lib_Utils_TypeCast_String::asUnsignedInt(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return int|null
	 */
	public function getId()
	{
		return $this->id;
	}



    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setFirstname($value)
	{
		$defaultValue = null;

		$this->firstname = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}


    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setLastname($value)
	{
		$defaultValue = null;

		$this->lastname = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getLastname()
	{
		return $this->lastname;
	}



    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setDisplayName($value)
	{
		$defaultValue = null;

		$this->displayName = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getDisplayName()
	{
		return $this->displayName;
	}



    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setImageUrl($value)
	{
		$defaultValue = null;

		$this->imageUrl = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getImageUrl()
	{
		return $this->imageUrl;
	}

    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setProfileUrl($value)
	{
		$defaultValue = null;

		$this->profileUrl = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getProfileUrl()
	{
		return $this->profileUrl;
	}


    /**
      * @param  string|null $value
      * @return void
      */
     public function setEmail($value)
     {
         $defaultValue = null;

         $this->email = Lib_Utils_TypeCast_String::asString(
             $value,
             $defaultValue
         );
     }

     /**
      * @return string|null
      */
     public function getEmail()
     {
         return $this->email;
     }







    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setAgb($value)
	{
		$defaultValue = null;

		$this->agb = Lib_Utils_TypeCast_String::asBool(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getAgb()
	{
		return $this->agb;
	}

   
    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setIsDeleted($value)
	{
		$defaultValue = null;

		$this->isDeleted = Lib_Utils_TypeCast_String::asBool(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getIsDeleted()
	{
		return $this->isDeleted;
	}




    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setIsBlocked($value)
	{
		$defaultValue = null;

		$this->isBlocked = Lib_Utils_TypeCast_String::asBool(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getIsBlocked()
	{
		return $this->isBlocked;
	}



    /**
      * @param  string|null $value
      * @return void
      */
     public function setCreated($value)
     {
         $defaultValue = null;

         $this->created = Lib_Utils_TypeCast_String::asString(
             $value,
             $defaultValue
         );
     }

     /**
      * @return string|null
      */
     public function getCreated()
     {
         return $this->created;
     }

    /**
      * @param  string|null $value
      * @return void
      */
     public function setModified($value)
     {
         $defaultValue = null;

         $this->modified = Lib_Utils_TypeCast_String::asString(
             $value,
             $defaultValue
         );
     }

     /**
      * @return string|null
      */
     public function getModified()
     {
         return $this->modified;
     }

}
