<?php

namespace Application\JsonRpc\V1\App
{
    use Processus\Lib\Auth\FacebookAuth;

    class Auth extends FacebookAuth
    {

        private $_isAuthorized = FALSE;
    }
}

?>