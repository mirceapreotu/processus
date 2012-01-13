<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/28/11
 * Time: 3:56 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Abstracts\JsonRpc
{
    abstract class AbstractJsonRpcResponse extends \Zend\Json\Server\Response
    {
        /**
         * Cast to JSON
         *
         * @return string
         */
        public function toJson()
        {
            if ($this->isError()) {

                $response = array();
                $error    = array();

                $error['code']    = $this->getError()->getCode();
                $error['message'] = $this->getError()->getMessage();
                $error['data']    = $this->getError()->getData();
                $error['stack']   = $this->getError()->getData()->getTraceAsString();

                $response['error'] = $error;
                $response['id']    = $this->getId();

            }
            else
            {

                $response                       = array();
                $response['result']             = $this->getResult();
                $response['id']                 = $this->getId();

            }

            if (null !== ($version = $this->getVersion())) {
                $response['jsonrpc'] = $version;
            }

            if ($this->_isDeveloper()) {

                $memory = array(
                    'usage'      => $this->_getSystem()->getMemoryUsage(),
                    'usage_peak' => $this->_getSystem()->getMemoryPeakUsage()
                );

                $app = array(
                    "start"    => $this->_getProfiler()->applicationProfilerStart(),
                    "end"      => $this->_getProfiler()->applicationProfilerEnd(),
                    "duration" => $this->_getProfiler()->applicationDuration()
                );

                $system = array(
                    "request_time" => $this->_getServerParams()->getRequestTime()
                );

                $currentUser = \Processus\ProcessusContext::getInstance()->getUserBo()->getFacebookUserMvo()->setDto(new \Application\Dto\FbBasicDto())->export();

                $requireList = \Processus\ProcessusContext::getInstance()->getBootstrap()->getFilesRequireList();

                $fileStack = array(
                    'list'  => $requireList,
                    'total' => count($requireList)
                );

                $debugInfo = array(
                    "memory"      => $memory,
                    "app"         => $app,
                    "system"      => $system,
                    "profiling"   => $this->_getProfiler()->getProfilerStack(),
                    'currentUser' => $currentUser,
                    'fileStack'   => $requireList,
                );

                $response['debug'] = $debugInfo;

            }

            return json_encode($response);
        }

        /**
         * @param Error $error
         *
         * @return \Processus\Abstracts\JsonRpc\AbstractJsonRpcResponse
         */
        public function setError(Error $error)
        {
            $this->_error = $error;
            return $this;
        }

        /**
         * @return \Processus\Lib\Profiler\ProcessusProfiler
         */
        protected function _getProfiler()
        {
            return \Processus\Lib\Profiler\ProcessusProfiler::getInstance();
        }

        /**
         * @return \Processus\Lib\System\System
         */
        protected function _getSystem()
        {
            return \Processus\Lib\System\System::getInstance();
        }

        /**
         * @return bool
         */
        protected function _isDeveloper()
        {
            return TRUE;
        }

        /**
         * @return \Processus\Lib\Server\ServerInfo
         */
        protected function _getServerParams()
        {
            return \Processus\Lib\Server\ServerInfo::getInstance();
        }
    }
}