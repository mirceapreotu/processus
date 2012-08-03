<?php

namespace Processus\Abstracts\Vo
{

    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/16/11
     * Time: 1:50 PM
     * To change this template use File | Settings | File Templates.
     */
    abstract class AbstractDTO extends AbstractVO implements \Processus\Interfaces\InterfaceDto
    {

        /**
         * @param $data
         *
         * @return AbstractDTO
         */
        public function setData($data)
        {
            parent::setData($data);
            return $this;
        }

        /**
         * @param null $rawData
         *
         * @return array|mixed
         */
        public function export($rawData = null)
        {
            if ($this->_useCache()) {
                $exportData = $this->_getCachedData();

                if (!$exportData['id'] = 0) {
                    foreach ($this->_getMapping() as $key => $item) {

                        $data = $this->getValueByKey($item['match']);

                        if (is_null($data)) {
                            $data = $item['default'];
                        }

                        $exportData[$key] = $data;
                    }
                }

                $this->_cacheData($this->_getDtoCachingId(), $exportData);
            }
            else
            {
                $exportData = array();

                foreach ($this->_getMapping() as $key => $item) {

                    $data = $this->getValueByKey($item['match']);

                    if (is_null($data)) {
                        $data = $item['default'];
                    }

                    $exportData[$key] = $data;
                }

            }

            return $exportData;
        }

        /**
         * @param $rawData
         *
         * @return array|mixed|string
         */
        protected function _generateCacheId($rawData)
        {
            $internalId = $this->getValueByKey("id");
            $cacheId    = NULL;

            if (!$internalId) {
                $cacheId = $this->_getDtoCachingId() . ":" . md5(json_encode($rawData));
            }
            else
            {
                $cacheId = $this->_getDtoCachingId() . ":" . $internalId;
            }

            return $cacheId;
        }

        /**
         * @param $cacheId
         * @param $cacheData
         *
         * @return bool
         */
        protected function _cacheData($cacheId, $cacheData)
        {
            return \Application\Factorys\CacheFactory::save($cacheId, $cacheData, $this->_getExpireTime());
        }

        /**
         * @return mixed
         */
        protected function _getCachedData()
        {
            return \Application\Factorys\CacheFactory::factory($this->_getDtoCachingId());
        }

        /**
         * @return \Processus\Lib\Db\Memcached
         */
        private function _getCache()
        {
            return $this->getProcessusContext()->getDefaultCache();
        }

        /**
         * @abstract
         */
        abstract protected function _getMapping();

        /**
         * @abstract
         * @return string
         */
        abstract protected function _getDtoCachingId();

        /**
         * @return int
         */
        protected function _getExpireTime()
        {
            return 60;
        }

        /**
         * @return bool
         */
        protected function _useCache()
        {
            return FALSE;
        }
    }
}

?>