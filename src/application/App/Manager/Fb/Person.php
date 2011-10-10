<?php
/**
 * App_Manager_Fb_Person Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Fb
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_Fb_Person
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_Fb_Person extends App_Manager_AbstractManager
{



    /**
     * @var App_Manager_Fb_Person
     */
    private static $_instance;

    /**
     * @static
     * @return App_Manager_Fb_Person
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }


    /**
     * @override
     * @return App_Facebook_Db_Xdb_Client
     */
	public function getDbClient()
	{
        return $this->getApplication()->getDbClient();
	}

    /**
     * @return null|string
     */
    public function getCurrentDate()
    {
        return $this->getApplication()->getCurrentDate();
    }


    /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @param  string $externalKey
     * @return bool
     */
    public function isValidExternalKey($externalKey)
    {
        $isValid = $this->getFacebook()->isValidUserId($externalKey);
        return (bool)($isValid === true);
    }


    /**
     * @param  $externalKey
     * @return string|null
     */
    public function castExternalKey($externalKey)
    {
        // fb: bigint-string
        // vz: string
        $externalKey = Lib_Utils_TypeCast_String::asUnsignedBigIntString(
            $externalKey,
            null
        );

        return $externalKey;
    }

    /**
     *
     * @param  array $externalKeyList
     * @return array
     */
    public function castExternalKeyList($externalKeyList)
    {

        // fb: int
        // vz: string
        if (is_array($externalKeyList) !== true) {
            return $externalKeyList;
        }



        $list = Lib_Utils_Vector_UnsignedBigIntString::filter(
            $externalKeyList,
            array(0,"0"),
            true
        );
        return $list;



    }


     /**
     * @param  int $id
     * @return bool
     */
    public function personRowExists($id)
    {
        $result = false;
        if ($this->isValidId($id) !== true) {
            return $result;
        }

        $sql = "SELECT
                COUNT(*) AS IDCOUNT
                FROM Person
                WHERE
                    Person.id=:id
                LIMIT 1;
                ";



        $params = array(
            "id" => (int)$id
        );
        $dbClient = $this->getDbClient();
        $row = $dbClient->getRow($sql, $params);
        $count = (int)Lib_Utils_Array::getProperty($row, "IDCOUNT");
        return (bool)($count>0);
    }


    /**
     * @param  int $id
     * @return array|null
     */
    public function loadPersonRowById($id)
    {
        if ($this->isValidId($id) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();

        $sql = "SELECT
                    Person.*
                    FROM Person
                    WHERE
                    Person.id=:id
                    LIMIT 1;";


        $params = array(
            "id"=>(int)$id,
        );
        $row = $dbClient->getRow($sql, $params, false);
        if (is_array($row)!==true) {
            $row = null;
        }
        return $row;
    }

    /**
     * @param  string $externalKey
     * @return array|null
     */
    public function loadPersonRowByExternalKey($externalKey)
    {
       
        if (
                (is_string($externalKey))
                || (is_int($externalKey))
                || ($externalKey===null)) {
            //ok
        } else {
            throw new Exception(
                "Invalid Parameter externalKey! at ".__METHOD__
            );
        }
        if ($this->isValidExternalKey($externalKey) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();


        $sql = "SELECT
                Person.*
                FROM Person
                INNER JOIN
                FbPerson
                ON (Person.id = FbPerson.personId)
                WHERE
                FbPerson.externalKey=:externalKey
                LIMIT 1;";

        $params = array(
            "externalKey"=>$this->castExternalKey($externalKey),
        );
       
        $row = $dbClient->getRow($sql, $params, false);
       
        if (is_array($row)!==true) {
            $row = null;
        }
        return $row;
    }









    // ++++++++++++++++++++ facebook ++++++++++++++++++++++++++

    /**
     * @param  int $id
     * @return array|null
     */
    public function loadFbPersonRowById($id)
    {

        if ($this->isValidId($id) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();
        $sql = "SELECT
                    FbPerson.*
                    FROM FbPerson
                    WHERE
                    FbPerson.personId=:id
                    LIMIT 1;";
        $params = array(
            "id"=>(int)$id,
        );
        $row = $dbClient->getRow($sql, $params, false);
        if (is_array($row)!==true) {
            $row = null;
        }
        return $row;
    }


    /**
     * @param  string $externalKey
     * @return array|null
     */
    public function loadFbPersonRowByExternalKey($externalKey)
    {


        if (
                (is_string($externalKey))
                || (is_int($externalKey))
                || ($externalKey===null)) {
            //ok
        } else {
            throw new Exception(
                "Invalid Parameter externalKey! at ".__METHOD__
            );
        }
        if ($this->isValidExternalKey($externalKey) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();

        

        $sql = "SELECT
                    FbPerson.*
                    FROM FbPerson
                    WHERE
                    FbPerson.externalKey=:externalKey
                    LIMIT 1;";
        $params = array(
            "externalKey"=>$this->castExternalKey($externalKey),
        );
        $row = $dbClient->getRow($sql, $params, false);
        if (is_array($row)!==true) {
            $row = null;
        }
        return $row;
    }


    /**
     * @param  string $externalKey
     * @return int|null
     */
    public function loadFbPersonIdByExternalKey($externalKey)
    {


        $result = null;
        if (
                (is_string($externalKey))
                || (is_int($externalKey))
                || ($externalKey===null)) {
            //ok
        } else {
            throw new Exception(
                "Invalid Parameter externalKey! at ".__METHOD__
            );
        }
        if ($this->isValidExternalKey($externalKey) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();
        $sql = "SELECT
                    Person.id
                    FROM Person
                    INNER JOIN FbPerson
                    ON (FbPerson.personId = Person.id)
                    WHERE
                    FbPerson.externalKey=:externalKey
                    LIMIT 1;";
        $params = array(
            "externalKey"=>$this->castExternalKey($externalKey),
        );
        $row = $dbClient->getRow($sql, $params, false);
        if (is_array($row)!==true) {
            return $result;
        }

        $id = null;
        if (isset($row["id"])) {
            $id = Lib_Utils_TypeCast_String::asUnsignedInt($row["id"], null);
        }

        if (is_int($id) !== true) {
            $id = null;
        }
        return $id;
    }

    /**
     * @param  array $externalKeyList
     * @return array
     */
    public function loadFbPersonIdListByExternalKeyList($externalKeyList)
    {


	    $result = array();
	    if (is_array($externalKeyList) !== true) {
		    throw new Exception(
                "Invalid Parameter externalKeyList at ".__METHOD__
            );
	    }
	    $_externalKeyList = array();
	    foreach ($externalKeyList as $externalKey) {
		    if ($this->isValidExternalKey($externalKey) !== true) {
			    continue;
		    }
		    $_externalKeyList[] = $externalKey;
	    }

        $externalKeyList = $this->castExternalKeyList($_externalKeyList);

	    $externalKeyList = (array)array_unique($externalKeyList);
	    if ((count($externalKeyList) > 0) !== true) {
		    return $result;
	    }

	    $dbClient = $this->getDbClient();
	    $sql = "SELECT
                    Person.id
                    FROM Person
                    INNER JOIN FbPerson
                    ON (FbPerson.personId=Person.id)
                    WHERE
                    FbPerson.externalKey IN
                        (" . $dbClient->implode(",", $externalKeyList) . ")
                    ;";

	    $params = array(
	    );
	    $rows = $dbClient->getRowsAndDontValidateParamsMissing(
		    $sql,
		    $params,
		    false
	    );

	    $idList = array();
	    foreach ($rows as $row) {
		    $id = (int)$row["id"];
		    if ($id > 0) {
			    $idList[] = $id;
		    }
	    }
	    $result = (array)array_unique($idList);
	    return $result;
    }

	/**
     * @param  int $id
     * @return array|null
     */
    public function loadFbPersonRowByPersonId($personId)
    {


        if ($this->isValidId($personId) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();
        $sql = "SELECT
                    FbPerson.*
                    FROM FbPerson
                    INNER JOIN Person
                    ON (Person.id=FbPerson.personId)
                    WHERE
                    Person.id=:personId
                    LIMIT 1;";
        $params = array(
            "personId"=>(int)$personId,
        );
        $row = $dbClient->getRow($sql, $params, false);
        if (is_array($row)!==true) {
            $row = null;
        }
        return $row;
    }







    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    /**
     * @throws Exception
     * @param  array $rowUpdate
     * @return int
     */
    public function updatePersonRow($rowUpdate)
    {

        if (is_array($rowUpdate) !== true) {
            throw new Exception(
                "Parameter rowUpdate must be an array at ".__METHOD__
            );
        }
        $id = Lib_Utils_Array::getProperty($rowUpdate, "id");
        $id = Lib_Utils_TypeCast_String::asUnsignedInt($id, null);
        if ($this->isValidId($id) !== true) {
            throw new Exception("Invalid row.id at ".__METHOD__);
        }

        $dbClient = $this->getDbClient();
        $currentDate = $this->getCurrentDate();
        $rowUpdate["modified"] = $currentDate;
        $where = "Person.id=:id LIMIT 1;";
        $params = array(
            "id" => (int)$id,
        );
        $affectedRows = (int)$dbClient->update(
            "Person",
            $rowUpdate,
            $where,
            $params
        );
        return $affectedRows;
    }



    /**
     * @throws Exception
     * @param  array $fbPersonRow
     * @param  array $personRow
     * @return
     */

    public function autoRegisterPlatformUser(
        $fbPersonRow,
        $personRow
    ) {




        if (Lib_Utils_Array::isEmpty($fbPersonRow)) {
            throw new Exception(
                "Parameter fbPersonRow must be an array and cant be empty at "
                        . __METHOD__
            );
        }
        if (Lib_Utils_Array::isEmpty($personRow)) {
            throw new Exception(
                "Parameter personRow must be an array and cant be empty at "
                        . __METHOD__
            );
        }

        $externalKey = $fbPersonRow["externalKey"];
        if ($this->isValidExternalKey($externalKey) !== true) {
            throw new Exception("Invalid ExternalKey at ".__METHOD__);
        }


        $dbClient = $this->getDbClient();
        $currentDate = $this->getCurrentDate();

        // (1) Lets check, if we have a fbPersonRow
        $_fbPersonRow = $this->loadFbPersonRowByExternalKey($externalKey);

        // We dont have a fbPersonRow
        if (is_array($_fbPersonRow) !== true) {

            // we dont have a fbPersonRow
            // insert personRow and insert/update fbPersonRow

            $rowInsert = $personRow;
            $personRow["id"] = null; //autoinc
            $personRow["created"] = $currentDate;
            $personRow["modified"] = $currentDate;
            $personId = (int)$dbClient->insert("Person", $rowInsert, true);
            if ($this->isValidId($personId) !== true) {
                throw new Exception(
                    "Invalid personId after insert personRow at ".__METHOD__
                );
            }

            $rowInsert = (array)$fbPersonRow;
            $rowInsert["personId"] = (int)$personId;
            $rowInsert["externalKey"] = $this->castExternalKey($externalKey);
            $rowUpdate = (array)$fbPersonRow;
            //unset($rowUpdate["id"]);
            unset($rowUpdate["externalKey"]);
            $dbClient->insertOrUpdate("FbPerson", $rowInsert, $rowUpdate);

            // that's it.
            return;

        }

        // (2) we have a fbPersonRow:
        // update fbPersonRow and insert personRow
        $_personRow = $this->loadPersonRowByExternalKey($externalKey);
        // but no personRow
        if (is_array($_personRow) !== true) {

            // insert personRow
            $rowInsert = $personRow;
            $personRow["created"] = $currentDate;
            $personRow["modified"] = $currentDate;
            $personId = (int)$dbClient->insert("Person", $rowInsert, true);
            if ($this->isValidId($personId) !== true) {
                throw new Exception(
                    "Invalid personId after insert personRow at "
                            . __METHOD__
                );
            }

            //insert/update fbPersonRow
            $rowInsert = (array)$fbPersonRow;
            $rowInsert["personId"] = (int)$personId;
            $rowInsert["externalKey"] = $this->castExternalKey($externalKey);
            $rowUpdate = (array)$fbPersonRow;
            $rowUpdate["personId"] = (int)$personId;
            $dbClient->insertOrUpdate("FbPerson", $rowInsert, $rowUpdate);

            // that's it.
            return;
        }

        // (3) we have a fbPersonRowa AND a personRow
        // update fbPersonRow and update personRow

        $personId = (int)$_personRow["id"];
        $rowInsert = (array)$personRow;
        $rowInsert["id"] = (int)$personId;
        $rowInsert["created"] = $currentDate;
        $rowInsert["modified"] = $currentDate;
        $rowUpdate = (array)$personRow;
        unset($rowUpdate["id"]);
        $dbClient->insertOrUpdate("Person", $rowInsert, $rowUpdate);

        //insert/update fbPersonRow
        $rowInsert = (array)$fbPersonRow;
        $rowInsert["personId"] = (int)$personId;
        $rowInsert["externalKey"] = $this->castExternalKey($externalKey);
        $rowUpdate = (array)$fbPersonRow;
        //unset($rowUpdate["id"]);
        unset($rowUpdate["externalKey"]);
        $dbClient->insertOrUpdate("FbPerson", $rowInsert, $rowUpdate);

        // that's it.
        return;






    }



    /**
     * @throws Exception
     * @param  array $me
     * @return void
     */
    public function autoRegisterFacebookUserByFacebookMe($me)
    {


        
        $facebook = $this->getFacebook();
        

        $meId = Lib_Utils_Array::getProperty($me, "id");
        if ($this->isValidExternalKey($meId)!==true) {
            throw new Exception("Invalid parameter me.id at ".__METHOD__);
        }


        /*

         me = array(9) {
            ["id"]=> string(15) "100001680154141"
            ["name"]=> string(11) "Seb Basinet"
            ["first_name"]=> string(3) "Seb"
            ["last_name"]=> string(7) "Basinet"
            ["link"]=> string(54) "http://www.facebook.com/profile.php?id=100001680154141"
            ["gender"]=> string(4) "male"
            ["timezone"]=> int(1)
            ["locale"]=> string(5) "en_GB"
            ["updated_time"]=> string(24) "2011-03-19T16:45:55+0000" }

         */

        $externalKey = $me["id"];
        $manager = $this;

        if ($manager->isValidExternalKey($externalKey) !== true) {
            throw new Exception(
                "Invalid externalKey at ".__METHOD__." for ".get_class($this)
            );
        }

        $fbPersonRow = array(
            "externalKey" => $externalKey,
            "accessToken" => (string)$facebook->getAccessToken()
        );
        $personRow = array(
            "displayName" => $me["name"],
            "firstname" => (string)Lib_Utils_Array::getProperty(
                $me,
                "first_name"
            ),
            "lastname" => (string)Lib_Utils_Array::getProperty(
                $me,
                "last_name"
            ),
            "profileUrl" => (string)Lib_Utils_Array::getProperty(
                $me,
                "link"
            ),
            "imageUrl" => $facebook->getProfileImageUrl($me["id"], null),
            "isDeleted" => false
        );



        $this->autoRegisterPlatformUser($fbPersonRow, $personRow);
    }




    /**
     * @param  int $personId
     * @return array|null
     */
    public function loadPersonExtendedRowById($personId)
    {
        if ($this->isValidId($personId) !== true) {
            return null;
        }
        $dbClient = $this->getDbClient();
        $sql = "SELECT
                    PersonExtended.*
                    FROM PersonExtended
                    WHERE
                    PersonExtended.personId=:personId
                    LIMIT 1;";
        $params = array(
            "personId"=>(int)$personId,
        );
        $row = $dbClient->getRow($sql, $params, false);
        if (is_array($row)!==true) {
            $row = null;
        }
        return $row;
    }



/**
     * @param  string $displayName
     * @return array
     */
    public function parseDisplayName($displayName)
    {
        $result = array(
            "_displayName" => $displayName,
            "displayName" => "",
            "firstname" => "",
            "lastname" => "",
        );


        if (is_string($displayName)!==true) {
            return $result;
        }
        $displayName = trim($displayName);
        $displayName = "".str_replace("  ", " ", $displayName);

        $parts = (array)explode(" ", $displayName);
        if (count($parts)===0) {
            return $result;
        }
        if (count($parts)===1) {
            $result["firstname"] = "".Lib_Utils_Array::getProperty($parts, 0);
            $result["displayName"] = $result["firstname"];
            return $result;
        }

        $result["lastname"] = "".array_pop($parts);
        if (count($parts)>0) {
            $result["firstname"] = "".implode(" ", $parts);
        }
        $result["displayName"] = $result["firstname"]." ".$result["lastname"];

        return $result;
    }


    
}
