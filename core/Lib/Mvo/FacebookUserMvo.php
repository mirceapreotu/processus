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
                ->getCouchbasePortByDatabucketKey("default");

            return $config['port'];
        }

        /**
         * @return mixed
         */
        protected function getMembaseHost()
        {
            $config = $this->getProcessusContext()
                ->getRegistry()
                ->getProcessusConfig()
                ->getCouchbaseConfig()
                ->getCouchbasePortByDatabucketKey("default");

            return $config['host'];
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
            return $this->getValueByKey("firstTime");
        }

        /**
         * @param $firstTime
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setFirstTime($firstTime)
        {
            $this->setValueByKey("firstTime", $firstTime);
            return $this;
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
         * @param $gender
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setGender($gender)
        {
            $this->setValueByKey('gender', $gender);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getEmail()
        {
            return $this->getValueByKey('email');
        }

        /**
         * @param $mail
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setEmail($mail)
        {
            $this->setValueByKey('email', $mail);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getLocale()
        {
            return $this->getValueByKey('locale');
        }

        /**
         * @param $locale
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setLocale($locale)
        {
            $this->setValueByKey('locale', $locale);
            return $this;
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

            $this->setImageUrlLarge($this->_generateUserImageUrl("large"));
            $this->setImageUrlSquare($this->_generateUserImageUrl("square"));
            $this->setImageUrlNormal($this->_generateUserImageUrl("normal"));
            $this->setImageUrlSmall($this->_generateUserImageUrl("small"));

            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getImageUrlSquare()
        {
            return $this->getValueByKey('imageUrlSquare');
        }

        /**
         * @param $imageUrl
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setImageUrlSquare($imageUrl)
        {
            $this->setValueByKey('imageUrlSquare', $imageUrl);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getImageUrlSmall()
        {
            return $this->getValueByKey('imageUrlSmall');
        }

        /**
         * @param $imageUrl
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setImageUrlSmall($imageUrl)
        {
            $this->setValueByKey('imageUrlSmall', $imageUrl);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getImageUrlNormal()
        {
            return $this->getValueByKey('imageUrlNormal');
        }

        /**
         * @param $imageUrl
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setImageUrlNormal($imageUrl)
        {
            $this->setValueByKey('imageUrlNormal', $imageUrl);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getImageUrlLarge()
        {
            return $this->getValueByKey('imageUrlLarge');
        }

        /**
         * @param $imageUrl
         *
         * @return \Processus\Lib\Mvo\FacebookUserMvo
         */
        public function setImageUrlLarge($imageUrl)
        {
            $this->setValueByKey('imageUrlLarge', $imageUrl);
            return $this;
        }

        /**
         * @param $type
         *
         * @return string
         */
        protected function _generateUserImageUrl($type)
        {
            return "https://graph.facebook.com/" . $this->getId() . "/picture?type=" . $type;
        }
    }

}
?>