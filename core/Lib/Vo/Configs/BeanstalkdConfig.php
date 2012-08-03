<?php

/**
 * @author francis
 *
 *
 */
namespace Processus\Lib\Vo\Configs
{
    class BeanstalkdConfig extends \Processus\Abstracts\Vo\AbstractVO
    {
        public function getServerPort()
        {
            return $this->getValueByKey('port');
        }

        public function getServerHost()
        {
            return $this->getValueByKey('host');
        }
    }
}