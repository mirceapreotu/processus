<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 8/22/11
 * Time: 12:46 AM
 * To change this template use File | Settings | File Templates.
 */

class Core_GaintS_Vo_Membase_FacebookUserMVO extends Core_GaintS_Vo_Membase_UserMVO
{
    public function __construct()
    {
        $this->_data["friends"] = array();
    }

    /**
     * @return array
     */
    public function getFriendsListWithData()
    {
        throw new Lib_Application_Exception("not implemented!");
        $friendsDataList = array();
        return $friendsDataList;
    }

    /**
     * @return mixed | null
     */
    public function getFriendsList()
    {
        return $this->_data['friends'];
    }

    /**
     * @param $friends
     * @return App_Vo_Membase_FacebookUserMVO
     */
    public function setFriends($friends)
    {
        $this->_data['friends'] = $friends;
        return $this;
    }

    /**
     * @return array|object
     */
    public function getData()
    {
        if(!array_key_exists('id',$this->_data))
        {
            $this->_data = $this->getFromMem();

            if($this->_data === false)
            {
                $facebookClient = Core_Facebook_Application::getInstance()->getFacebook();
                $fbUser = $facebookClient->fetchMe();
                $this->setData($fbUser);
                $successInMem = $this->saveInMem();

                $manager = new Core_Manager_App_UserManager();
                $manager->saveUserFb($fbUser);

                if($successInMem === false)
                {
                    throw new Lib_Application_Exception("Saving into RAM not successfull");
                }
            }
        }

        return $this->_data;
    }
}
