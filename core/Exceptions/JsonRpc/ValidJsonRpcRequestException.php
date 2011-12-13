<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 12/12/11
 * Time: 3:48 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Exceptions\JsonRpc
{

    class ValidJsonRpcRequest extends \Processus\Exceptions\ProcessusException
    {
        /**
         * @param string $message
         * @param string $code
         * @param string $severity
         * @param string $filename
         * @param int    $lineno
         * @param        $previous
         */
        public function __construct($message = "", $code = "PRC-1000", $severity = "10", $filename = __FILE__, $lineno = __LINE__, $previous)
        {
            parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
        }
    }
}