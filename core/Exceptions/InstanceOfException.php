<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 1/2/12
 * Time: 5:23 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Exceptions
{

    class InstanceOfException extends \Processus\Exceptions\ProcessusException
    {

        public function __construct($message = "", $code = 1000, $severity = 10, $filename = __FILE__, $lineno = __LINE__, $previous = array())
        {
            parent::__construct("Class is not instanceOf", $code, $severity, $filename, $lineno, $previous);
        }
    }
}