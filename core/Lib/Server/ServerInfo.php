<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/28/11
 * Time: 3:05 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Server
{
    class ServerInfo extends \Processus\Abstracts\Vo\AbstractVO
    {

        /**
         * @var \Processus\Lib\Server\ServerInfo
         */
        private static $_Instance;

        /**
         * @static
         * @return \Processus\Lib\Server\ServerInfo
         */
        public static function getInstance()
        {
            if (!self::$_Instance) {
                self::$_Instance = new ServerInfo();
            }

            return self::$_Instance;
        }

        /**
         * @return mixeds
         */
        public function getUniqueId()
        {
            return $this->getValueByKey('UNIQUE_ID');
        }

        /**
         * @return mixeds
         */
        public function getHttpPost()
        {
            return $this->getValueByKey('HTTP_HOST');
        }

        /**
         * @return mixeds
         */
        public function getContentLength()
        {
            return $this->getValueByKey('CONTENT_LENGTH');
        }

        /**
         * @return mixeds
         */
        public function getHttpOrigin()
        {
            return $this->getValueByKey("HTTP_ORIGIN");
        }

        /**
         * @return mixeds
         */
        public function getHttpXRequestedWith()
        {
            return $this->getValueByKey("HTTP_X_REQUESTED_WITH");
        }

        /**
         * @return mixeds
         */
        public function getUserAgent()
        {
            return $this->getValueByKey('HTTP_USER_AGENT');
        }

        /**
         * @return mixeds
         */
        public function getContentType()
        {
            return $this->getValueByKey('CONTENT_TYPE');
        }

        /**
         * @return mixeds
         */
        public function getHttpAccept()
        {
            return $this->getValueByKey('HTTP_ACCEPT');
        }

        /**
         * @return mixeds
         */
        public function getHttpReferrer()
        {
            return $this->getValueByKey('HTTP_REFERER');
        }

        /**
         * @return mixeds
         */
        public function getHttpacceptEncoding()
        {
            return $this->getValueByKey('HTTP_ACCEPT_ENCODING');
        }

        /**
         * @return mixeds
         */
        public function getHttpAcceptLanguage()
        {
            return $this->getValueByKey('HTTP_ACCEPT_LANGUAGE');
        }

        /**
         * @return mixeds
         */
        public function getHttpAcceptCharset()
        {
            return $this->getValueByKey('HTTP_ACCEPT_CHARSET');
        }

        /**
         * @return mixeds
         */
        public function getHttpCookie()
        {
            return $this->getValueByKey('HTTP_COOKIE');
        }

        /**
         * @return mixeds
         */
        public function getRequestMethod()
        {
            return $this->getValueByKey('REQUEST_METHOD');
        }

        public function getRequestTime()
        {
            return $this->getValueByKey('REQUEST_TIME');
        }

        /**
         * @param string $key
         *
         * @return mixeds
         */
        public function getValueByKey(\string $key)
        {
            return $_SERVER[$key];
        }
    }
}