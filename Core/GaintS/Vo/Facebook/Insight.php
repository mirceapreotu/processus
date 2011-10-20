<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 2:38 AM
 * To change this template use File | Settings | File Templates.
 *
 * {
"id": "147117031982180",
"name": "Bet Tycoon - Betting Game",
"description": "Bet Tycoon is the first Social Betting Game on Facebook. With Bet Tycoon you can challenge your friends by betting on every topic you like, e.g. on real events, like the Soccer, NBA, NFL or Entertainment - or even on self-created private topics.",
"category": "Games",
"link": "https://www.facebook.com/apps/application.php?id=147117031982180",
"canvas_name": "crowdpark-game",
"icon_url": "http://photos-a.ak.fbcdn.net/photos-ak-snc1/v43/8/147117031982180/app_2_147117031982180_4671.gif",
"logo_url": "http://photos-d.ak.fbcdn.net/photos-ak-snc1/v43/8/147117031982180/app_1_147117031982180_5263.gif",
"company": "CROWDPARK",
"daily_active_users": "69591",
"weekly_active_users": "353601",
"monthly_active_users": "1000173"
}
 *
 */

    class App_GaintS_Vo_Facebook_Insight
    {

        /**
         * @var \Facebook
         */
        private $_facebook;

        /**
         * @var string
         */
        private $_insightId;

        /**
         * @var array
         */
        private $_insightRawData;

        public function __construct()
        {

        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->_insightRawData['name'];
        }

        /**
         * @return void
         */
        protected function getInsightRawData()
        {

        }

        /**
         * @param $value
         * @return void
         */
        public function setInsightId($value)
        {
            $this->_insightId = $value;
            $this->getInsightRawData();
        }

        /**
         * @return string
         */
        public function getInsightId()
        {
            return $this->_insightId;
        }

        /**
         * @return array
         */
        public function getInsightData()
        {
            $data = array();

            return $data;
        }

        public function getMau()
        {

        }

        public function getWau()
        {

        }

        public function getDau()
        {

        }

        /**
         * @return string
         */
        public function getUrl()
        {
            return $this->_insightRawData['link'];
        }

        /**
         * @return string
         */
        public function getDescription()
        {
            return $this->_insightRawData['description'];
        }

        /**
         * @return Facebook
         */
        protected function getClient()
        {
            return $this->_facebook;
        }

    }
