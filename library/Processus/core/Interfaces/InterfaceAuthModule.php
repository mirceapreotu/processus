<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/31/11
     * Time: 7:37 PM
     * To change this template use File | Settings | File Templates.
     */

    namespace Processus\Interfaces
    {
        interface InterfaceAuthModule
        {
            function setAuthData($authData);
            function isAuthorized();
        }
    }