<?php
/**
 * App_Model_Bo_Fb_User
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Model_Bo_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

/**
 * App_Model_Bo_Fb_User
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Model_Bo_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

class App_Model_Bo_Fb_User
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
     * @var array|null
     */
    protected $_friendsIdList;


    /**
     * @var App_Model_Record_PersonExtended
     */
    protected $_personExtendedRecord;


    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }

    /**
     * @return App_Model_Bo_Fb_User
     */
    public function getViewerBO()
    {
        return $this->getApplication()->getViewerBO();
    }


    /**
     * @return App_Manager_Fb_Person
     */
    public function getManagerPerson()
    {
        return $this->getApplication()->getManagerPerson();
    }


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
        return $this->getManagerPerson()
                ->isValidExternalKey($externalKey);
    }

    /**
     * @return bool
     */
    public function hasValidExternalKey()
    {
        $externalKey = $this->getExternalKey();
        return (bool)($this->isValidExternalKey($externalKey)===true);
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return (bool)($this->getPersonRecord()->exists() === true);
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
            $row = $this->getManagerPerson()
                    ->loadPersonRowById($id);
            $record = new App_Model_Record_Person($row, "");
            $this->setPersonRecord($record);
            return;
        }

        // or try to load by externalKey
        $externalKey = $this->getExternalKey();
        if ($this->isValidExternalKey($externalKey)) {
            $row = $this->getManagerPerson()
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

            $row = $this->getManagerPerson()
                    ->loadFbPersonRowByPersonId($id);
            $record = new App_Model_Record_FbPerson($row, "");
            $this->setFbPersonRecord($record);
            return;
        }

        // or try to load by externalKey
        $externalKey = $this->getExternalKey();
        if ($this->isValidExternalKey($externalKey)) {
            $row = $this->getManagerPerson()
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
            $row = $this->getManagerPerson()
                    ->loadPersonExtendedRowById($id);
            $record = new App_Model_Record_PersonExtended($row, "");
            $this->setPersonExtendedRecord($record);
            return;
        }




        $record = new App_Model_Record_PersonExtended();
        $this->setPersonExtendedRecord($record);
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
        $result = false;


        $viewerBO = $this->getViewerBO();

        $viewerId = $viewerBO->getPersonRecord()->getId();
        if ($viewerBO->exists()!==true) {
            return $result;
        }

        $userId = $this->getId();

        if ($this->hasId() !== true) {
            if ($this->exists()===true) {
                $userId = $this->getPersonRecord()->getId();
            }
        }

        $viewerId = (string)$viewerId;
        $userId = (string)$userId;

        return (bool)($userId === $viewerId);
    }










    /**
     * @return App_Model_Dto_Person
     */
    public function newPersonDto($imageFormat = null)
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

        $imageUrls = $this->getProfileImageUrls();
        $dto->setImageUrls($imageUrls);

        /*
        $imageUrl = $this->getPersonRecord()->getImageUrl();
        if (Lib_Utils_String::isEmpty($imageFormat) !== true) {
            $imageUrl .= "?type=".$imageFormat;
        }
        */

        if ($imageFormat === null) {
            $imageFormat = "medium";
        }
        $imageUrl = Lib_Utils_Array::getProperty($imageUrls, $imageFormat);

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
     * @param null $imageFormat
     * @return App_Model_Dto_Person|null
     */
    public function newPersonDtoIfExists($imageFormat = null)
    {
        $result = null;
        if ($this->exists() !== true) {
            return $result;
        }

        return $this->newPersonDto($imageFormat);
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
     * @return array
     */
    public function getProfileImageUrls()
    {
        $result = array(
            "small" => null,
            "medium" => null,
            "large" => null,
            "square" => null,
        );

        $externalKey = $this->getExternalKey();
        if ($this->hasValidExternalKey()) {
            $externalKey = $this->getExternalKey();
            $url = "https://graph.facebook.com/".$externalKey."/picture";
            $result["small"] = $url."?type=small";
            $result["medium"] = $url."?type=medium";
            $result["large"] = $url."?type=large";
            $result["square"] = $url."?type=square";
            return $result;
        }

        $fbPersonRecord = $this->getFbPersonRecord();
        if ($fbPersonRecord->exists() !== true) {
            return $result;
        }

        $externalKey = $fbPersonRecord->getExternalKey();
        if ($this->isValidExternalKey($externalKey)) {
            $url = "https://graph.facebook.com/".$externalKey."/picture";
            $result["small"] = $url."?type=small";
            $result["medium"] = $url."?type=medium";
            $result["large"] = $url."?type=large";
            $result["square"] = $url."?type=square";
            return $result;
        }
        return $result;
    }




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
