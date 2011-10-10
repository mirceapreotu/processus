<?php
/**
 * App_Model_Bo_User
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Model_Bo
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

/**
 * App_Model_Bo_User
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Model_Bo
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

class App_Model_Bo_User
{

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_externalKey;

    /**
     * @var App_Model_Record_Person|null
     */
    protected $_personRecord;

    /**
     * @var App_Model_Record_FbPerson|null
     */
    protected $_fbPersonRecord;

    /**
     * @var App_Model_Record_VzPerson|null
     */
    protected $_vzPersonRecord;



    /**
     * @var array|null
     */
    protected $_friendsIdList;


    /**
     * @var App_Model_Record_PersonExtended
     */
    protected $_personExtendedRecord;



     /**
     * @return bool
     */
    public function hasId()
    {
        $id = $this->getId();
        return $this->isValidId($id);
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @throws Exception
     * @param  int|null $id
     * @return void
     */
    public function setId($id)
    {
        $id = Lib_Utils_TypeCast_String::asUnsignedInt($id, null);
        if ($id === null) {
            $this->_id = null;
            return;
        }

        if ($this->isValidId($id)) {
            $this->_id = (int)$id;
        }
    }

    /**
     * @param  string $uid
     * @return void
     */
    public function setUID($uid)
    {
        $dto = new App_Model_Dto_Person();
        $dto->setUID($uid);
        $id = $dto->getId();
        if ($this->isValidId($id) !== true) {
            $id = null;
        }
        $this->setId($id);
    }

    /**
     * @return string
     */
    public function getUID()
    {
        $dto = new App_Model_Dto_Person();
        $dto->setId($this->getId());
        $uid = $dto->uid;
        return $uid;
    }


    /**
     * @param  $externalKey
     * @return void
     */
    public function setExternalKey($externalKey)
    {
        $this->_externalKey = $externalKey;
    }

    /**
     * @return string
     */
    public function getExternalKey()
    {
        return $this->_externalKey;
    }



    /**
     * @param  mixed|int  $id
     * @return bool
     */
    public function isValidId($id)
    {
        $id = Lib_Utils_TypeCast_String::asUnsignedInt($id, null);
        return (((is_int($id)) && ($id > 0)) === true);
    }

    /**
     * @param  string $externalKey
     * @return bool
     */
    public function isValidExternalKey($externalKey)
    {
        return App_Manager_Person::getInstance()
                ->isValidExternalKey($externalKey);
    }


    /**
     * @return App_Registry
     */
    public function getRegistry()
    {
        return Bootstrap::getRegistry();
    }
    /**
     * @return Lib_Db_Xdb_Client
     */
    public function getDbClient()
    {
        return $this->getRegistry()->getXdbClient();
    }



    /**
     * @throws Exception
     * @return App_Model_Record_Person
     */
    public function getPersonRecord()
    {
        
        if (($this->_personRecord instanceof App_Model_Record_Person)
                !== true) {
            $this->loadPersonRecord();
        }
        if (($this->_personRecord instanceof App_Model_Record_Person)
                !== true) {
            $message = "Method returns an invalid result! fix me! at "
                        . __METHOD__;
            throw new Exception($message);
        }
        return $this->_personRecord;
    }

    /**
     * @return void
     */
    public function loadPersonRecord()
    {
        $id = $this->getId();
        if ($this->isValidId($id)===true) {
            $row = App_Manager_Person::getInstance()
                    ->loadPersonRowById($id);
            $record = new App_Model_Record_Person($row, "");
            $this->setPersonRecord($record);
            return;
        }

        // or try to load by externalKey
        $externalKey = $this->getExternalKey();
        if ($this->isValidExternalKey($externalKey)) {
            $row = App_Manager_Person::getInstance()
                    ->loadPersonRowByExternalKey($externalKey);

            

            $record = new App_Model_Record_Person($row, "");
            $this->setPersonRecord($record);            
            return;
        }


        $record = new App_Model_Record_Person();
        $this->setPersonRecord($record);
    }





    /**
     * @throws Exception
     * @param App_Model_Record_Person|null $record
     * @return void
     */
    public function setPersonRecord(
        App_Model_Record_Person $record = null
    )
    {
        if ($record === null) {
            $this->destroyPersonRecord();
            return;
        }
        if (($record instanceof App_Model_Record_Person) !== true) {
            throw new Exception(
                "Parameter record must be an instanceof "
                . "App_Model_Record_Person or null! at " . __METHOD__
            );
        }
        $this->_personRecord = $record;

        if ($record->exists()) {
                $this->setId($record->getId());
        }
    }



    /**
     * @return void
     */
    public function destroyPersonRecord()
    {
        $this->_personRecord = null;
    }



    /**
     * @throws Exception
     * @return App_Model_Record_FbPerson
     */
    public function getFbPersonRecord()
    {
        if (($this->_fbPersonRecord instanceof App_Model_Record_FbPerson)
                !== true) {
            $this->loadFbPersonRecord();
        }
        if (($this->_fbPersonRecord instanceof App_Model_Record_FbPerson)
                !== true) {
            $message = "Method returns an invalid result! fix me! at "
                        . __METHOD__;
            throw new Exception($message);
        }
        return $this->_fbPersonRecord;
    }

    /**
     * @return void
     */
    public function loadFbPersonRecord()
    {
        $id = $this->getId();

        if ($this->isValidId($id)) {

            $row = App_Manager_Person::getInstance()
                    ->loadFbPersonRowByPersonId($id);
            $record = new App_Model_Record_FbPerson($row, "");
            $this->setFbPersonRecord($record);
            return;
        }

        // or try to load by externalKey
        $externalKey = $this->getExternalKey();
        if ($this->isValidExternalKey($externalKey)) {
            $row = App_Manager_Person::getInstance()            
                    ->loadFbPersonRowByExternalKey($externalKey);
            $record = new App_Model_Record_FbPerson($row, "");
            $this->setFbPersonRecord($record);
            return;
        }

        $record = new App_Model_Record_FbPerson();
        $this->setFbPersonRecord($record);
        return;
    }




    /**
     * @throws Exception
     * @param App_Model_Record_FbPerson $record
     * @return void
     */
    public function setFbPersonRecord(
        App_Model_Record_FbPerson $record = null
    )
    {
        if ($record === null) {
            $this->destroyFbPersonRecord();
            return;
        }
        if (($record instanceof App_Model_Record_FbPerson) !== true) {
            throw new Exception(
                "Parameter record must be an instanceof "
                . "App_Model_Record_FbPerson or null! at " . __METHOD__
            );
        }
        $this->_fbPersonRecord = $record;
        if ($record->exists()) {
            $personId = $record->getPersonId();
            if ($this->isValidId($personId)) {
                $this->setId($personId);
            }

            $externalKey = $record->getExternalKey();
            if ($this->isValidExternalKey($externalKey)) {
                $this->setExternalKey($externalKey);
            }
        }
    }



    /**
     * @return void
     */
    public function destroyFbPersonRecord()
    {
        $this->_fbPersonRecord = null;
    }










    /**
     * @throws Exception
     * @return App_Model_Record_VzPerson
     */
    public function getVzPersonRecord()
    {
        if (($this->_vzPersonRecord instanceof App_Model_Record_VzPerson)
                !== true) {
            $this->loadVzPersonRecord();
        }
        if (($this->_vzPersonRecord instanceof App_Model_Record_VzPerson)
                !== true) {
            $message = "Method returns an invalid result! fix me! at "
                        . __METHOD__;
            throw new Exception($message);
        }
        return $this->_vzPersonRecord;
    }

    /**
     * @return void
     */
    public function loadVzPersonRecord()
    {
        $id = $this->getId();

        if ($this->isValidId($id)) {

            $row = App_Manager_Person::getInstance()
                    ->loadVzPersonRowByPersonId($id);
            $record = new App_Model_Record_VzPerson($row, "");
            $this->setVzPersonRecord($record);
            return;
        }

        // or try to load by externalKey
        $externalKey = $this->getExternalKey();
        if ($this->isValidExternalKey($externalKey)) {
            $row = App_Manager_Person::getInstance()
                    ->loadVzPersonRowByExternalKey($externalKey);
            $record = new App_Model_Record_VzPerson($row, "");
            $this->setVzPersonRecord($record);
            return;
        }

        $record = new App_Model_Record_VzPerson();
        $this->setVzPersonRecord($record);
        return;
    }




    /**
     * @throws Exception
     * @param App_Model_Record_VzPerson $record
     * @return void
     */
    public function setVzPersonRecord(
        App_Model_Record_VzPerson $record = null
    )
    {
        if ($record === null) {
            $this->destroyVzPersonRecord();
            return;
        }
        if (($record instanceof App_Model_Record_VzPerson) !== true) {
            throw new Exception(
                "Parameter record must be an instanceof "
                . "App_Model_Record_VzPerson or null! at " . __METHOD__
            );
        }
        $this->_vzPersonRecord = $record;
        if ($record->exists()) {
            $personId = $record->getPersonId();
            if ($this->isValidId($personId)) {
                $this->setId($personId);
            }

            $externalKey = $record->getExternalKey();
            if ($this->isValidExternalKey($externalKey)) {
                $this->setExternalKey($externalKey);
            }
        }
    }



    /**
     * @return void
     */
    public function destroyVzPersonRecord()
    {
        $this->_vzPersonRecord = null;
    }









        /**
     * @throws Exception
     * @return App_Model_Record_PersonExtended
     */
    public function getPersonExtendedRecord()
    {
        if (($this->_personExtendedRecord
                instanceof App_Model_Record_PersonExtended)
                !== true) {
            $this->loadPersonExtendedRecord();
        }
        if (($this->_personExtendedRecord
                instanceof App_Model_Record_PersonExtended)
                !== true) {
            $message = "Method returns an invalid result! fix me! at "
                        . __METHOD__;
            throw new Exception($message);
        }
        return $this->_personExtendedRecord;
    }

    /**
     * @return void
     */
    public function loadPersonExtendedRecord()
    {
        $id = $this->getId();
        if ($this->isValidId($id)===true) {
            $row = App_Manager_Person::getInstance()
                    ->loadPersonExtendedRowById($id);
            $record = new App_Model_Record_PersonExtended($row, "");
            $this->setPersonExtendedRecord($record);
            return;
        }




        $record = new App_Model_Record_PersonExtended();
        $this->setPersonRecord($record);
    }





    /**
     * @throws Exception
     * @param App_Model_Record_PersonExtended|null $record
     * @return void
     */
    public function setPersonExtendedRecord(
        App_Model_Record_PersonExtended $record = null
    )
    {
        if ($record === null) {
            $this->destroyPersonExtendedRecord();
            return;
        }
        if (($record instanceof App_Model_Record_PersonExtended) !== true) {
            throw new Exception(
                "Parameter record must be an instanceof "
                . "App_Model_Record_PersonExtended or null! at " . __METHOD__
            );
        }
        $this->_personExtendedRecord = $record;

    }


    /**
     * @return void
     */
    public function destroyPersonExtendedRecord()
    {
        $this->_personExtendedRecord = null;
    }













    /**
     * @throws Exception
     * @return
     */
    public function getFriendsIdList()
    {
        if (is_array($this->_friendsIdList)) {
            return $this->_friendsIdList;
        }

        $this->loadFriendsIdList();
        if (is_array($this->_friendsIdList) !== true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }
        return $this->_friendsIdList;

    }
    /**
     * @return void
     */
    public function loadFriendsIdList()
    {
        throw new Exception("Implement ".__METHOD__);
        /*
        $vzUserRecord = $this->getVzUserRecord();
        $osapiKeyList = $vzUserRecord->getOsapiFriends();
        $friendsIdList = (array)App_Manager_Subscriber::getInstance()
                ->loadVzSubscriberIdListByOsapiKeyList($osapiKeyList);
        $this->_friendsIdList = $friendsIdList;

         */
    }

    /**
     * @return void
     */
    public function destroyFriendsIdList()
    {
        $this->_friendsIdList = null;
    }


    /**
     * @return bool
     */
    public function isViewer()
    {
        if (Bootstrap::getRegistry()->isCmsContext()) {
            return false;
        }

        $viewerBO = Bootstrap::getRegistry()->getViewerBO();

        if ($viewerBO->hasId() !== true) {
            return false;
        }

        if ($this->hasId() !== true) {
            return false;
        }

        if ($this->getId() === $viewerBO->getId()) {
            return true;
        }
        return false;
    }


    public function newPersonDto($imageFormat = null)
    {
        return $this->newPersonDtoForVz($imageFormat);
    }

    /**
     * @return App_Model_Dto_Person
     */
    public function newPersonDtoForVz($imageFormat = null)
    {
        $dto = new App_Model_Dto_Person();
        $dto->setId(
            $this->getPersonRecord()->getId()
        );


        $firstname = $this->getPersonRecord()->getFirstname();
        $lastname = $this->getPersonRecord()->getLastname();
        $imageUrl = $this->getPersonRecord()->getImageUrl();
        $displayName = "";
        if (is_string($firstname)) {
            $displayName .= $firstname;
            if (is_string($lastname)) {
                $displayName .= " ".$lastname;
            }
        } else {
            if (is_string($lastname)) {
                $displayName .= $lastname;
            }
        }



        $dto->setFirstname(
            $firstname
        );
        $dto->setLastname(
            $lastname
        );
        $dto->setDisplayName(
            $displayName
        );

        $imageUrl = $this->getPersonRecord()->getImageUrl();
        if (Lib_Utils_String::isEmpty($imageFormat) !== true) {
            $imageUrl .= "?type=".$imageFormat;
        }
        $dto->setImageUrl(
            $imageUrl
        );

        $dto->setIsViewer(
            $this->isViewer()
        );


        $dto->setIsRegistered((bool)$this->getPersonRecord()->isRegistered());

        
        return $dto;
    }



    /**
     * @return App_Model_Dto_Person
     */
    public function newPersonDtoForFacebook($imageFormat = null)
    {
        $dto = new App_Model_Dto_Person();
        $dto->setId(
            $this->getPersonRecord()->getId()
        );



        $firstnameFb = $this->getPersonRecord()->getFirstname();
        $lastnameFb = $this->getPersonRecord()->getLastname();
        $displayNameFb = $this->getPersonRecord()->getDisplayName();


        $firstname = $firstnameFb;
        $lastname = $lastnameFb;
        $displayName = $displayNameFb;


        $personExtendedRecord = $this->getPersonExtendedRecord();
        if ($personExtendedRecord->exists()) {
            $firstname = $personExtendedRecord->getFirstname();
            $lastname = $personExtendedRecord->getLastname();
            $displayName = (string)$personExtendedRecord->getFirstname()
                    . " ".(string)$personExtendedRecord->getLastname();
        }

        $dto->setFirstname(
            $firstname
        );
        $dto->setLastname(
            $lastname
        );
        $dto->setDisplayName(
            $displayName
        );

        $imageUrl = $this->getPersonRecord()->getImageUrl();
        if (Lib_Utils_String::isEmpty($imageFormat) !== true) {
            $imageUrl .= "?type=".$imageFormat;
        }
        $dto->setImageUrl(
            $imageUrl
        );

        $dto->setIsViewer(
            $this->isViewer()
        );

        $dto->setIsRegistered((bool)$this->getPersonRecord()->getAgb());


        return $dto;
    }



    /**
     * @return App_Model_Dto_Person
     */
    public function newPersonDtoForCms($imageFormat = null)
    {

        $dto = $this->newPersonDto($imageFormat);

        $dto->setAgb($this->getPersonRecord()->getAgb());
        $dto->setEmail($this->getPersonRecord()->getEmail());
        $dto->setProfileUrl($this->getPersonRecord()->getProfileUrl());

        $dto->setCity($this->getPersonExtendedRecord()->getCity());
        $dto->setZipcode($this->getPersonExtendedRecord()->getZipcode());
        $dto->setStreetAddress(
            $this->getPersonExtendedRecord()->getStreetAddress()
        );
        $dto->setCountry(
            $this->getPersonExtendedRecord()->getCountry()
        );
        //$dto->setExternalKey($this->getFbPersonRecord()->getExternalKey());


        return $dto;
    }



    /**
     * @return App_Manager_Person
     */
    /*
    public function getManagerPerson()
    {
        return App_Manager_Person::getInstance();
    }
    */

    /**
     * @throws Exception
     * @param  $errorMethod
     * @return void
     */
    public function requireUserId($errorMethod)
    {
        //return;
        if ($this->hasId() !== true) {
            $method = __METHOD__;
            if (Lib_Utils_String::isEmpty($errorMethod)!==true) {
                $method = $errorMethod;
            }

            $message = "Invalid UserBO.id at ".__METHOD__;
            throw new Exception($message);
        }
    }
}
