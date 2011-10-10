<?php
    /**
     * Created by IntelliJ IDEA.
     * User: francis
     * Date: 10/5/11
     * Time: 10:27 PM
     * To change this template use File | Settings | File Templates.
     */

    class App_JsonRpc_V1_Public_Service_Filter extends App_JsonRpc_V1_Public_Service
    {
        /**
         * @param $filterObject
         * @return array
         */
        public function filter($filterObject)
        {
            $conds = array();

            $dbClient = App_Facebook_Application::getInstance()->getDbClient();

            $response = array(
                'requested' => $filterObject,
                'hits' => 0,
                'results' => array(
                    'districts' => array(),
                    'costs' => array(),
                    'genres' => array(),
                    'venues' => array(),
                    'events' => array(),
                )
            );

            $raw = array(
                'districts' => array(),
                'costs' => array(),
                'genres' => array(),
                'venues' => array(),
                'events' => array(),
            );

            // requested event filter

            $req_time = $filterObject['time'];
            $req_district = $filterObject['district'];
            $req_cost = $filterObject['cost'];
            $req_genre = $filterObject['genre'];
            $req_venue = $filterObject['venue'];

            // ### //
            // sql time

            $now = '("' . date('Y-m-d H:i:s') . '" between e.datetime_start AND e.datetime_end)';

            if ($req_time == 'today') {
                $timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") = "' . date('Y-m-d') . '" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'))) . '" OR ' . $now;
            }

            elseif ($req_time == 'tomorrow')
            {
                $timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") = "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'))) . '" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y'))) . '"';
            }

            elseif ($req_time == 'weekend')
            {
                $saturday_date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (6 - date('N')), date('Y')));
                $sunday_date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (8 - date('N')), date('Y')));
                $timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") >= "' . $saturday_date . '" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") <= "' . $sunday_date . '"';
            }

            elseif ($req_time == 'thisweek')
            {
                $sunday_date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (8 - date('N')), date('Y')));
                $timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") >= "' . date('Y-m-d') . '" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") <= "' . $sunday_date . '" OR ' . $now;
            }

            elseif ($req_time == 'nextweek')
            {
                $next_monday_date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (8 - date('N')), date('Y')));
                $next_sunday_date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (15 - date('N')), date('Y')));
                $timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") >= "' . $next_monday_date . '" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") <= "' . $next_sunday_date . '"';
            }

            // sql district

            if ($req_district != 'all')
            {
                $conds[] = 'v.district="' . $req_district . '"';
            }

            // sql costs

            if ($req_cost != 'all')
            {
                if($req_cost == 'free')
                {
                    $conds[] = 'e.has_costs="no"';
                }
                elseif($req_cost == 'maybe')
                {
                    $conds[] = 'e.has_costs="maybe"';
                }
                else
                {
                    $conds[] = '(e.cost_door IS NULL OR e.cost_door <= ' . $req_cost . ')';
                }
            }

            // sql genre

            if ($req_genre != 'all') {
                $conds[] = 'g.name="' . $req_genre . '"';
            }

            // venue

            if ($req_venue != 'all') {
                $conds[] = 'v.name="' . $req_venue . '"';
            }

            // ### //
            // fetch data from db

            $sql = '
            SELECT
              e.id,
              e.venue_id,
              e.name,
              e.urlname,
              DATE_FORMAT(e.datetime_start, "%d.%m") AS date,
              DATE_FORMAT(e.datetime_start, "%H:%i") AS time,
              DATE_FORMAT(e.datetime_end, "%Y-%m-%dT%H:%i:%s") AS ending,
              IF(TIMEDIFF(e.datetime_start, "%now") < 0, 1, 0) AS is_running,
              e.cost_door,
              e.has_costs,
              v.name AS venue_name,
              v.district AS venue_district,
              GROUP_CONCAT(DISTINCT event_genres.name ORDER BY event_genres.name ASC SEPARATOR ", ") AS genres
            FROM
              events AS e
              INNER JOIN venues AS v ON v.id = e.venue_id
              INNER JOIN dj_event_relations AS djr ON djr.event_id = e.id
              INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = djr.dj_id
              INNER JOIN base_genres AS g ON g.id = dgr.genre_id

              INNER JOIN dj_genre_relations AS event_dj_genre ON event_dj_genre.dj_id = djr.dj_id
              INNER JOIN base_genres AS event_genres ON event_genres.id = event_dj_genre.genre_id
            WHERE
              e.city_id = 1
              AND e.status="complete"
              AND (%time)
              %conds
            GROUP BY e.id
            ORDER BY is_running DESC, ending ASC, e.datetime_start ASC
            ';

            // lets make it REALLY TIGHT SAVE ;)

            $sql = str_replace('%now', date('Y-m-d H:i:s'), $sql);
            $sql = str_replace('%time', $timespan, $sql);
            $sql = str_replace('%conds', count($conds) > 0 ? 'AND ' . join(' AND ', $conds) : NULL, $sql);

            // save number of found events

            $cost_steps = 5;

            $rows = $dbClient->getRowsAndDontValidateParamsMissing($sql);
            $response['hits'] = count($rows);

            foreach ($rows as $obj)
            {
                // events

                $obj = (object)$obj;

                $eur = $cents = NULL;
                if ($obj->cost_door) {
                    list($eur, $cents) = explode('.', $obj->cost_door);
                }

                $raw['events'][] = array(
                    'id'     => $obj->id,
                    'name'   => $obj->name,
                    'url'    => $obj->urlname,
                    'has_costs' => $obj->has_costs,
                    'cost'   => $cents > 0 ? $obj->cost_door : $eur,
                    'date'   => $obj->date,
                    'time'   => $obj->time,
                    'ending' => $obj->ending,
                    'is_running' => $obj->is_running,
                    'genres' => $obj->genres,
                    'venue'  => array(
                      'id' => $obj->venue_id,
                      'name' => $obj->venue_name,
                      'district' => $obj->venue_district
                    )
                );

                // districts lists

                $this->helper_filter_counter('district', $obj->venue_district, $raw['districts']);

                // costs list

                if ($obj->has_costs == 'no') {
                    $cost = 'free';
                }

                elseif ($obj->has_costs == 'maybe')
                {
                    $cost = 'maybe';
                }

                else
                {
                    if ($obj->cost_door <= 20) {
                        // 5 step filter
                        $cost = round($obj->cost_door % $cost_steps > 0 ? ($cost_steps - $obj->cost_door % $cost_steps + $obj->cost_door) : $obj->cost_door);
                    }
                    else
                    {
                        $cost = 'more';
                    }
                }

                $this->helper_filter_counter('cost', $cost, $raw['costs']);

                // genres list

                $genres = explode(',', $obj->genres);

                foreach ($genres as $genre)
                {
                    $this->helper_filter_counter('genre', $genre, $raw['genres']);
                }

                // venues list

                $this->helper_filter_counter('venue', $obj->venue_name, $raw['venues']);
            }

            ksort($raw['districts']);
            ksort($raw['costs']);
            ksort($raw['genres']);
            ksort($raw['venues']);

            // ### //
            // response

            // districts

            $response['results']['districts'][] = array('key' => 'all', 'name' => 'All Districts');
            foreach ($raw['districts'] as $district => $count)
            {
                $response['results']['districts'][] = array('key' => $district, 'name' => $district, 'count' => $count);
            }

            // costs

            $response['results']['costs'][] = array('name' => 'All Costs', 'price' => 'all');

            if (array_key_exists(5, $raw['costs'])) {
                if (array_key_exists('free', $raw['costs'])) {
                    $raw['costs'][5] += $raw['costs']['free'];
                }

                if (array_key_exists('maybe', $raw['costs'])) {
                    $raw['costs'][5] += $raw['costs']['maybe'];
                }

                if (array_key_exists(10, $raw['costs'])) {
                    $raw['costs'][10] += $raw['costs'][5] ? $raw['costs'][5] : 0;

                    if (array_key_exists(15, $raw['costs'])) {
                        $raw['costs'][15] += $raw['costs'][10] ? $raw['costs'][10] : 0;

                        if (array_key_exists(20, $raw['costs'])) {
                            $raw['costs'][20] += $raw['costs'][15] ? $raw['costs'][15] : 0;
                        }
                    }
                }
            }

            foreach ($raw['costs'] as $price => $count)
            {
                if ($price == 'free') {
                    $name = 'Free';
                }
                elseif ($price == 'maybe')
                {
                    $name = 'Maybe';
                }
                elseif ($price == 'more')
                {
                    $name = 'More';
                }
                else
                {
                    $name = 'Max. &euro; ' . $price;
                }

                if ($count) {
                    $response['results']['costs'][] = array(
                        'name' => $name,
                        'price' => $price,
                        'count' => $count
                    );
                }
            }

            // genres

            $response['results']['genres'][] = array('key' => 'all', 'name' => 'All Styles');
            foreach ($raw['genres'] as $genre => $count)
            {
                $response['results']['genres'][] = array('key' => $genre, 'name' => $genre, 'count' => $count);
            }

            // venues

            $response['results']['venues'][] = array('key' => 'all', 'name' => 'All Venues');
            foreach ($raw['venues'] as $venue => $count)
            {
                $response['results']['venues'][] = array('key' => $venue, 'name' => $venue, 'count' => $count);
            }

            // events
            $response['results']['events'] = $raw['events'];

            return $response;
        }

        /**
         * @param $type
         * @param $key
         * @param $array
         */
        private function  helper_filter_counter($type, $key, &$array)
        {
            $key = trim($key);

            if (!array_key_exists($key, $array)) {
                $array[$key] = 1;
            }

            else
            {
                $array[$key]++;
            }
        }

    }
