<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/1/11
 * Time: 2:28 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Auth;
class LowAuth implements \Processus\Interfaces\InterfaceAuthModule
{
    /**
     * @param $authData
     *
     * @return bool
     */
    public function setAuthData($authData)
    {
        return TRUE;
    }

    public function isAuthorized()
    {

    }
}

