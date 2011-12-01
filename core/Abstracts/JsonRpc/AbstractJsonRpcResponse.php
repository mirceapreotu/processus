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
    class AbstractJsonRpcResponse extends \Zend\Json\Server\Response
    {
        /**
         * Cast to JSON
         *
         * @return string
         */
        public function toJson()
        {
            if ($this->isError()) {
                $response = array('error' => $this->getError()->toArray(),
                                  'id'    => $this->getId());
            } else {

                $response           = array();
                $response['result'] = $this->getResult();
                $response['id']     = $this->getId();

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

                    $debugInfo = array(
                        "memory"    => $memory,
                        "app"       => $app,
                        "system"    => $system,
                        "profiling" => $this->_getProfiler()->getProfilerStack()
                    );

                    $response['debug'] = $debugInfo;

                }
            }

            if (null !== ($version = $this->getVersion())) {
                $response['jsonrpc'] = $version;
            }

            return \Zend\Json\Json::encode($response);
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