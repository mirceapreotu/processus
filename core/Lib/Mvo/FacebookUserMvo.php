<?php

/**
 * @author francis
 *
 */
namespace Processus\Lib\Mvo
{

    class FacebookUserMvo extends UserMvo
    {

        /**
         * @return mixed
         */
        protected function getDataBucketPort()
        {
            $config = $this->getProcessusContext()
                ->getRegistry()
                ->getProcessusConfig()
                ->getCouchbaseConfig()
                ->getCouchbasePortByDatabucketKey("fbusers");

            return $config['port'];
        }

        /**
         * @param string $mId
         *
         * @return FacebookUserMvo
         */
        public function setMemId(\string $mId)
        {
            parent::setMemId("FacebookUserMvo_" . $mId);
            return $this;
        }

        /**
         * @return \Processus\Dto\FacebookUserDto
         */
        public function getDefaultDto()
        {
            $dto = new \Processus\Dto\FacebookUserDto();
            $dto->setData($this->getData());
            return $dto;
        }

        /**
         * @param string $size
         *
         * @return string
         */
        public function getImageUrl(\string $size)
        {
            return 'https://graph.facebook.com/' . $this->getFacebookUserId() . '/picture?type=' . $size;
        }

        /**
         * @return mixed
         */
        public function getFacebookUserId()
        {
            return $this->getProcessusContext()->getFacebookClient()->getUserId();
        }

        /**
         * @return array|mixed
         */
        public function isFirstTime()
        {
            return $this->getValueByKey('firstTime');
        }

        /**
         * @return array|mixed
         */
        public function getLink()
        {
            return $this->getValueByKey('link');
        }

        /**
         * @return array|mixed
         */
        public function getUserName()
        {
            return $this->getValueByKey('username');
        }

        /**
         * @return array|mixed
         */
        public function getGender()
        {
            return $this->getValueByKey('gender');
        }

        /**
         * @return array|mixed
         */
        public function getEmail()
        {
            return $this->getValueByKey('email');
        }

        /**
         * @return array|mixed
         */
        public function getLocale()
        {
            return $this->getValueByKey('locale');
        }

        /**
         * @return array|mixed
         */
        public function getVerified()
        {
            return $this->getValueByKey('verified');
        }

        /**
         * @return array|mixed
         */
        public function getAccessToken()
        {
            return $this->getValueByKey('accessToken');
        }

        /**
         * @param string $accessToken
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setAccessToken(\string $accessToken)
        {
            $this->setValueByKey('accessToken', $accessToken);
            return $this;
        }

        /**
         * @param $data
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setData($data)
        {
            parent::setData($data);
            return $this;
        }
    }

}
?>