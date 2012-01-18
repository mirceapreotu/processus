<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/21/11
 * Time: 6:29 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Exceptions\JsonRpc
{
    class AuthException extends \Processus\Abstracts\AbstractException
    {
        public function __construct($message = "", $code = 1000, $severity = 10, $filename = __FILE__, $lineno = __LINE__, $previous = array())
        {
            parent::__construct("Authorisation failed", $code, $severity, $filename, $lineno, $previous);
        }
    }
}