<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/26/11
 * Time: 10:54 PM
 * To change this template use File | Settings | File Templates.
 */

    class App_Task_MapsDataToCouchDB
    {
        private $client;

        public function run()
        {

            echo "====== Start ======" . PHP_EOL;

            $sqlSmt = 'SELECT * FROM venues';
            $dbClient = App_Facebook_Application::getInstance()->getDbClient();
            $venues = $dbClient->getRows($sqlSmt);

            $item = new App_Model_Mvo_VenuesMvo();
            $this->client = new Elastica_Client();

            echo "====== Creating Variables ======" . PHP_EOL;

            foreach ($venues as $venue)
            {
                $item->setMemId($venue['id']);
                $item->setData($venue);
                $item->saveInMem();

                $item->setMemId($venue['urlname']);
                $item->setData($venue);
                $item->saveInMem();

                $mapsData = $this->geocode($item->getAddress());
                if ($mapsData)
                {
                    $couchDoc = array();
                    $couchDoc['docType'] = "venues_geo";
                    $couchDoc['venue'] = $item->getData();
                    $couchDoc['maps'] = $mapsData;
                    $this->saveIntoCouchDB($couchDoc);
                    $couchDoc['_id'] = $item->getUrlname();
                    $this->saveIntoCouchDB($couchDoc);
                    
                    echo "====== Finished save to couchDB ======" . PHP_EOL;
                }
            }
        }

        /**
         * @param $data
         * @return void
         */
        private function saveIntoCouchDB($data)
        {
            $couchDBClient = new Couch_Client('localhost:5984', 'beatguide');
            $couchDoc = $this->array_to_object($data);
            $couchDBClient->storeDoc($couchDoc);
        }

        /**
         * @param array $array
         * @return object | boolean
         */
        public function array_to_object($array = array())
        {
            if (!empty($array)) {
                $data = new stdClass();
                foreach ($array as $akey => $aval)
                {
                    $data->{$akey} = $aval;
                }
                return $data;
            }
            return false;
        }

        /**
         * @param $city
         * @return mixed
         */
        private function geocode($city)
        {
            $cityclean = str_replace(",", "", $city);
            $cityclean = str_replace(" ", "+", $cityclean);
            $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $cityclean . "&sensor=false";

            var_dump($details_url);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $details_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $resultObj = json_decode($result);

            if (!$resultObj->results)
            {
                return null;
            }

            $geoloc = $resultObj->results;
            return $geoloc[0];
        }

        /**
         * @param $item App_Model_Mvo_VenuesMvo
         * @return void
         */
        public function saveIntoElasticSearch($item)
        {
            $index = $this->client->getIndex('venues');
            $index->create(array(), true);
        }

    }
