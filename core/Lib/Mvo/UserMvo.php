<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Mvo;

class UserMvo extends \Processus\Abstracts\Vo\AbstractMVO implements \Processus\Interfaces\InterfaceUser
{
    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->getValueByKey("first_name");
    }

    /**
     * @param string $firstTime
     *
     * @return FacebookUserMvo
     */
    public function setFirstname(\string $firstTime)
    {
        $this->setValueByKey('first_name', $firstTime);
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->getValueByKey("last_name");
    }

    /**
     * @param string $lastname
     *
     * @return \Processus\Lib\Mvo\UserMvo
     */
    public function setLastName(\string $lastname)
    {
        $this->setValueByKey("last_name", $lastname);
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getValueByKey("name");
    }

    /**
     * @param string $fullName
     *
     * @return \Processus\Lib\Mvo\UserMvo
     */
    public function setFullName(\string $fullName)
    {
        $this->setValueByKey("name", $fullName);
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getCreated()
    {
        return $this->getValueByKey("created");
    }

    /**
     * @param int $created
     *
     * @return \Processus\Lib\Mvo\UserMvo
     */
    public function setCreated(\int $created)
    {
        $this->setValueByKey("created", $created);
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getFacebookId()
    {
        return $this->getValueByKey("id");
    }

    /**
     * @return array|mixed
     */
    public function getName()
    {
        return $this->getValueByKey("name");
    }

    /**
     * @return array|mixed
     */
    public function getUserName()
    {
        return $this->getValueByKey("username");
    }

    /**
     * @param string $username
     *
     * @return \Processus\Lib\Mvo\UserMvo
     */
    public function setUserName(\string $username)
    {
        $this->setValueByKey("username", $username);
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getIsAppUser()
    {
        return $this->getValueByKey('isAppUser');
    }

    /**
     * @param bool $is
     *
     * @return \Processus\Lib\Mvo\UserMvo
     */
    public function setIsAppUser(\boolean $is)
    {
        $this->setValueByKey('isAppUser', $is);
        return $this;
    }
}

?>