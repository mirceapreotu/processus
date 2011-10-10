<?php
/**
 * App_Model_Record_FbPerson
 *
 *
 *
 * @category	basilicom
 * @package		App_Model_Record
 *
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Record_FbPerson
 *
 *
 *
 * @category	basilicom
 * @package		App_Model_Record
 *
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version		$Id:$
 */

class App_Model_Record_FbPerson extends App_Model_Record_Abstract
{

    const DB_TABLE = "FbPerson";

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

            "externalKey", //pk varchar(64) unsigned NOT NULL
            "personId", // unique bigint(20) unsigned NOT NULL
            "accessToken", // varchar(255) DEFAULT NULL,

		);
	}


	public $externalKey;
    public $personId;
    public $accessToken;

	/**
	 * @return void
	 */
	public function parse()
	{
		$this->setExternalKey($this->getDbRowFieldValue("externalKey"));
        $this->setPersonId($this->getDbRowFieldValue("personId"));
        $this->setAccessToken($this->getDbRowFieldValue("accessToken"));
	}

	/**
	 * @return bool
	 */
	public function exists()
	{
		return (((int)$this->getExternalKey())>0);
	}



	/**
	 * @param  string $value
	 * @return void
	 */
	public function setExternalKey($value)
	{
		$defaultValue = null;
        $manager = App_Manager_Fb_Person::getInstance();
        if ($manager->isValidExternalKey($value)) {
            $this->externalKey = $manager->castExternalKey($value);
        } else {
            $this->externalKey = $defaultValue;
        }

	}

	/**
	 * @return string|null
	 */
	public function getExternalKey()
	{
		return $this->externalKey;
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
	public function setAccessToken($value)
	{
		$defaultValue = null;

		$this->accessToken = Lib_Utils_TypeCast_String::asString(
			$value,
			$defaultValue
		);
	}

	/**
	 * @return int|null
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}



}
