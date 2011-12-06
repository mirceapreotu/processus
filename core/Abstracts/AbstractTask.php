<?php

namespace Processus\Abstracts
{
    abstract class AbstractTask extends \Processus\Abstracts\Manager\AbstractManager
    {
        abstract public function run();
    }
}

?>