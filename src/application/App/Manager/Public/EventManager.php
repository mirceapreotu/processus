<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/26/11
 * Time: 12:19 AM
 * To change this template use File | Settings | File Templates.
 */

    class App_Manager_Event extends App_Manager_AbstractManager
    {

        /**
         * @param $cityId
         * @return array|mixed|null
         */
        public function getEventByCityId($cityId)
        {
              $sqlStmt = 'SELECT
                        e.id,
                        e.name,
                        e.datetime_start,
                        e.datetime_end,
                        e.cost_door,
                        GROUP_CONCAT(DISTINCT g.name ORDER BY g.name ASC SEPARATOR ", ") AS genres
                      FROM
                        events AS e
                        INNER JOIN dj_event_relations AS djr ON djr.event_id = e.id
                        INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = djr.dj_id
                        INNER JOIN base_genres AS g ON g.id = dgr.genre_id
                      WHERE
                        e.city_id =:city_id
                        AND e.status="complete"
                      GROUP BY e.id';

            $getDataConfig = $this->getDataConfig($sqlStmt, __METHOD__, array("city_id" => $cityId));
            $rawData = $this->getSimpleData($getDataConfig);

            return $rawData;
        }

        /**
         * @param $urlName
         * @return array|mixed|null
         */
        public function getEventDataByUrlName($urlName)
        {
            $sqlStmt = 'SELECT
                        e.id,
                        e.name,
                        e.datetime_start,
                        e.datetime_end,
                        e.cost_door,
                        GROUP_CONCAT(DISTINCT g.name ORDER BY g.name ASC SEPARATOR ", ") AS genres
                      FROM
                        events AS e
                        INNER JOIN dj_event_relations AS djr ON djr.event_id = e.id
                        INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = djr.dj_id
                        INNER JOIN base_genres AS g ON g.id = dgr.genre_id
                      WHERE
                        e.urlname =:urlName
                        AND e.status="complete"
                      GROUP BY e.id';

            $getDataConfig = $this->getDataConfig($sqlStmt, __METHOD__, array("urlName" => $urlName));
            $rawData = $this->getSimpleData($getDataConfig);

            return $rawData;
        }

        /**
         * @param $id
         * @return array|mixed|null
         */
        public function getEventDataById($id)
        {
            $sqlStmt = 'SELECT
                        e.id,
                        e.name,
                        e.datetime_start,
                        e.datetime_end,
                        e.cost_door,
                        GROUP_CONCAT(DISTINCT g.name ORDER BY g.name ASC SEPARATOR ", ") AS genres
                      FROM
                        events AS e
                        INNER JOIN dj_event_relations AS djr ON djr.event_id = e.id
                        INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = djr.dj_id
                        INNER JOIN base_genres AS g ON g.id = dgr.genre_id
                      WHERE
                        e.id =:eventId
                        AND e.status="complete"
                      GROUP BY e.id';

            $getDataConfig = $this->getDataConfig($sqlStmt, __METHOD__, array("eventId" => $id));
            $rawData = $this->getSimpleData($getDataConfig);

            return $rawData;
        }
    }
