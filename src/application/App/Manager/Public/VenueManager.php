<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/26/11
 * Time: 12:59 AM
 * To change this template use File | Settings | File Templates.
 */

    class App_Manager_Public_VenueManager extends App_Manager_AbstractManager
    {

        /**
         * @param $venueId
         * @return array|mixed|null
         */
        public function getVenueById($venueId)
        {
            $sqlStmt = '';
            return $this->getSimpleData($this->getDataConfig($sqlStmt, __METHOD__));
        }

        /**
         * @param $venueUrlName
         * @return array|mixed|null
         */
        public function getVenueByUrlName($venueUrlName)
        {
            $sqlStmt = '';
            return $this->getSimpleData($this->getDataConfig($sqlStmt, __METHOD__));
        }

        /**
         * @param $venueName
         * @return array|mixed|null
         */
        public function getVenueByName($venueName)
        {
            $sqlStmt = '';
            return $this->getSimpleData($this->getDataConfig($sqlStmt, __METHOD__));
        }

        /**
         * @param $venueAddress
         * @return array|mixed|null
         */
        public function getVenueByAddress($venueAddress)
        {
            $sqlStmt = '';
            return $this->getSimpleData($this->getDataConfig($sqlStmt, __METHOD__));
        }

        public function getVenueByCoords($long, $lat)
        {
            $sqlStmt = 'SELECT 		*
                        FROM 		venues
                        WHERE 		CONVERT(latitude, CHAR) LIKE 52.3662513
                                    AND
                                    CONVERT(longitude, CHAR) LIKE 4.8970410';
            return $this->getSimpleData($this->getDataConfig($sqlStmt, __METHOD__));
        }
    }
