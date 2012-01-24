<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 1/24/12
 * Time: 2:51 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Vo\Configs;
class ExpiredTimeConfig extends \Processus\Abstracts\Vo\AbstractVO
{
    /**
     * @param $methodNS
     *
     * @return array|mixed
     */
    public function getExpiredTimeByMethod($methodNS)
    {
        return $this->getValueByKey($methodNS);
    }
}
