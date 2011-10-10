<?php
/**
 * App_Model_Record_PersonExtended
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
 * App_Model_Record_PersonExtended
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

class App_Model_Record_PersonExtended extends App_Model_Record_Abstract
{

    const DB_TABLE = "PersonExtended";

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

            "personId", // unique bigint(20) unsigned NOT NULL
            "firstname",
            "lastname",
            "country",

            "zipcode",
            "streetAddress",
            "city",
            
            "created",
            "modified",

		);
	}



    public $personId;
    public $firstname;
    public $lastname;
    public $country;

    public $zipcode;
    public $streetAddress;
    public $city;

    public $created;
    public $modified;

	/**
	 * @return void
	 */
	public function parse()
	{
        $this->setPersonId($this->getDbRowFieldValue("personId"));

        $this->setFirstname($this->getDbRowFieldValue("firstname"));
        $this->setLastname($this->getDbRowFieldValue("lastname"));
        $this->setCountry($this->getDbRowFieldValue("country"));

        $this->setCity($this->getDbRowFieldValue("city"));
        $this->setZipcode($this->getDbRowFieldValue("zipcode"));
        $this->setStreetAddress($this->getDbRowFieldValue("streetAddress"));



        $this->setCreated($this->getDbRowFieldValue("created"));
        $this->setModified($this->getDbRowFieldValue("modified"));
	}

	/**
	 * @return bool
	 */
	public function exists()
	{
		return (((int)$this->getPersonId())>0);
	}






    /**
	 * @param  string|int $value
	 * @return void
	 */
	public function setPersonId($value)
	{
		$defaultValue = null;

		$this->personId = Lib_Utils_TypeCast_String::asUnsignedInt(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return int|null
	 */
	public function getPersonId()
	{
		return $this->personId;
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
	 * @return int|null
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
	 * @return int|null
	 */
	public function getLastname()
	{
		return $this->lastname;
	}




    /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setCountry($value)
	{
		$defaultValue = null;

		$this->country = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return int|null
	 */
	public function getCountry()
	{
		return $this->country;
	}



     /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setCity($value)
	{
		$defaultValue = null;

		$this->city = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getCity()
	{
		return $this->city;
	}


     /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setZipcode($value)
	{
		$defaultValue = null;

		$this->zipcode = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return int|null
	 */
	public function getZipcode()
	{
		return $this->zipcode;
	}







         /**
	 * @param  string|null $value
	 * @return void
	 */
	public function setStreetAddress($value)
	{
		$defaultValue = null;

		$this->streetAddress = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return string|null
	 */
	public function getStreetAddress()
	{
		return $this->streetAddress;
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
	 * @return int|null
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
	 * @return int|null
	 */
	public function getModified()
	{
		return $this->modified;
	}





}
