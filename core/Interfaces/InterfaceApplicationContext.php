<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/16/11
 * Time: 11:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Interfaces
{
    interface InterfaceApplicationContext
    {
        public function getDefaultCache();

        public function getMasterMySql();

        public function getRegistry();

        public function getFacebookClient();

        public function getUserBo();

        public function getProfiler();
    }
}