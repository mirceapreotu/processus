<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 8/22/11
 * Time: 12:46 AM
 * To change this template use File | Settings | File Templates.
 */

class App_GaintS_Vo_Membase_FacebookUserMVO extends App_GaintS_Vo_Membase_UserMVO
{

    public function getFriendsListWithData()
    {

        $friendsDataList = array();

        $userData = $this->getData();


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
}
