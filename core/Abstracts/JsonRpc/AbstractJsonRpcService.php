<?php

/**
 * @author fightbulc
 *
 *
 */
namespace Processus\Abstracts\JsonRpc
{
    abstract class AbstractJsonRpcService extends \Processus\Abstracts\AbstractClass
    {

        /**
         * @return string
         */
        protected function getUserId()
        {
            return $this->getProcessusContext()->getUserBo()->getFacebookUserId();
        }

        /**
         * @return AbstractJsonRpcRequest
         */
        protected function _getRawRequest()
        {
            return $this->getProcessusContext()->getBootstrap()->getGateway()->getRequest();
        }

        /**
         * @return array
         */
        public function getApi()
        {
            return $this->getProcessusContext()->getBootstrap()->getGateway()->getServer()->getServiceMap()->toArray();
        }

        /**
         * @param string $method
         * @param        $request
         * @param        $duration
         * @param        $metaData
         *
         * @return bool|\Zend\Db\Statement\Pdo
         */
        protected function _logJsonRpc(\string $method, $request, $duration, $metaData = null)
        {
            return True;
        }

        /**
         * @return string
         */
        protected function _getLogTransactionTable()
        {
            return "log_json_rpc";
        }
    }
}

?>