<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/28/11
 * Time: 5:41 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Vo\Configs
{
    class ProfilerConfig extends \Processus\Abstracts\Vo\AbstractVO
    {

        /**
         * @param string $ip
         *
         * @return bool
         */
        public function checkIpForDeveloper(\string $ip)
        {
            $isDeveloper = TRUE;

            return $isDeveloper;
        }

    }
}