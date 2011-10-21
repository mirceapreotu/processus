<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/9/11
 * Time: 12:53 PM
 * To change this template use File | Settings | File Templates.
 */

    class App_JsonRpc_V1_Public_Service_Events extends App_JsonRpc_V1_Public_Service
    {

        public function __construct()
        {
            $this->_manager = new App_Manager_Public_EventManager();
        }
        
        /**
         * @return App_Manager_Public_EventManager
         * @see App_GaintS_Core_AbstractJsonRPCService::getManager()
         */
        protected function getManager()
        {
            return $this->_manager;
        }
        
        
        /**
         * @param $urlname
         * @return array
         */
        public function filterByUrlName($filterObject)
        {
            $dbClient = App_Facebook_Application::getInstance()->getDbClient();

            $urlname = $filterObject['urlname'];

            $sql = '
            SELECT
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
              e.urlname = "' . $urlname . '"
              AND e.status="complete"
            GROUP BY e.id
            ';

            $response['event'] = (object)$this->getManager()->getEventDataByUrlName($urlname);

            // venue data

            $sql = '
            SELECT
              v.id,
              v.name,
              v.urlname,
              v.latitude,
              v.longitude,
              v.address,
              v.district
            FROM
              events AS e
              INNER JOIN venues AS v ON v.id = e.venue_id
            WHERE
              e.id = ' . $response['event']->id . '
            ';
            
            $venueManager = new App_Manager_Public_VenueManager();
            $response['venue'] = (object)$venueManager->getVenueByEventId($response['event']->id);

            // artist data

            $sql = '
            SELECT
              dj.id,
              dj.name,
              dj.urlname,
              dj.country,
              dj.url_soundcloud,
              REPLACE(dj.url_avatar, "crop", "small") AS url_avatar,
              dj.url_facebook,
              GROUP_CONCAT(DISTINCT g.name ORDER BY g.name ASC SEPARATOR ", ") AS genres
            FROM
              dj_event_relations AS djr
              INNER JOIN djs AS dj ON dj.id = djr.dj_id
              INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = dj.id
              INNER JOIN base_genres AS g ON g.id = dgr.genre_id
            WHERE
              djr.event_id = ' . $response['event']->id . '
            GROUP BY dj.id
            ORDER BY dj.name ASC
            ';
            
            $artistManager = new App_Manager_Public_ArtistManager();
            $r = (object)$artistManager->getArtistByEvent($response['event']->id);

            foreach ($r as $obj)
            {
              $response['artists'][] = $obj;
            }

            $trackManager = new App_Manager_App_Tracking();
            $trackManager->trackEventByUrlName($urlname);

            return $response;
        }

    }
