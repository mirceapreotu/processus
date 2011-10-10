<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/4/11
 * Time: 9:15 PM
 * To change this template use File | Settings | File Templates.
 */

class App_Manager_App_EventsManager extends App_Manager_AbstractManager
{
    public function get_event($event_urlname)
		{
			$sql = '
			SELECT
				e.id,
				e.name,
				e.urlname,
				e.datetime_start,
				e.datetime_end,
				e.has_costs,
				e.cost_door,
				e.cost_presale,
				e.cost_students,
				bc.name AS city,
				bc.currency,
				v.id AS venue_id,
				v.urlname AS venue_urlname,
				v.name AS venue_name,
				v.city_id AS venue_city_id,
				v.address AS venue_address,
				v.url_website AS venue_web,
				v.latitude AS venue_lat,
				v.longitude AS venue_long,
				GROUP_CONCAT(DISTINCT g.name ORDER BY g.name ASC SEPARATOR "^") AS genres,
				mr_event.rating AS member_event_rating,
				mer.member_id AS following_event,
				mvr.member_id AS following_venue
			FROM
				events AS e
				INNER JOIN dj_event_relations AS der ON der.event_id = e.id
				INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = der.dj_id
				INNER JOIN base_genres AS g ON g.id = dgr.genre_id
				INNER JOIN venues AS v ON v.id = e.venue_id
				INNER JOIN base_cities AS bc ON bc.id = e.city_id
				LEFT JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id AND mr_event.member_id = ?
				LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = e.venue_id AND mvr.member_id = ?
			WHERE
				e.urlname = ?
				AND e.status = ?
			GROUP BY e.id
			';

			$result = $this->simple_fetchobject($sql, array(Session::userdata('id'), Session::userdata('id'), Session::userdata('id'), $event_urlname, 'complete'));

			if($result->id)
			{
				// prepare time

				$now = new fTimestamp();
				$result->datetime_start = new fTimestamp($result->datetime_start);
				$result->datetime_end   = new fTimestamp($result->datetime_end);

				// prepare genres

				$result->genres = Format::string_explode($result->genres, '^');

				// get djs

				$sql = '
				SELECT
					d.id,
					d.name,
					d.urlname,
					d.url_soundcloud,
					d.url_facebook,
					d.url_myspace,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					mdr.member_id AS me_following
				FROM
					dj_event_relations AS der
					LEFT JOIN djs AS d ON d.id = der.dj_id
					LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					LEFT JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
				WHERE
					der.event_id = ?
				GROUP BY d.id
				ORDER BY d.name ASC
				';

				$result->djs = $this->simple_fetch($sql, array(Session::userdata('id'), $result->id));

				// followers

				$sql = 'SELECT count(mer.event_id) FROM member_event_relations AS mer WHERE mer.event_id = ?';
				$result->followers = $this->simple_fetchvalue($sql, array($result->id));

				// recent followers

				$sql = '
				SELECT
					m.id,
					m.fullname,
					m.username,
					mmr_me.member_id AS me_following
				FROM
					member_logs AS ml
					INNER JOIN members AS m ON m.id = ml.member_id
					LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = m.id AND mmr_me.member_id = ?
				WHERE
					ml.for_object_id = ?
					AND ml.action_type = ?
				GROUP BY m.id
				ORDER BY ml.created DESC
				LIMIT 30
				';

				$result->recent_followers = $this->simple_fetch($sql, array(Session::userdata('id'), $result->id, 'event'));

				// events upcoming

				$sql = '
				SELECT
					e.id,
					e.urlname,
					e.name,
					e.datetime_start,
					e.datetime_end,
					e.has_costs,
					e.cost_door,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					mer.member_id AS me_following
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN djs AS d ON d.id = der.dj_id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				WHERE
					e.venue_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_start, ?) > "00:00:00"
					AND e.id != ?
				GROUP BY e.id
				ORDER BY e.datetime_start ASC
				';

				$result->upcoming = $this->simple_fetch($sql, array(Session::userdata('id'), $result->venue_id, 'complete', Base::get_date(TRUE), $result->id));

				// events close by
/*
	- find all venues around this venue
	- which have an event
	- where starting date = starting date of current event
	- or current datetime between starting date & ending date
*/

				$radius = 1; // in km

				$sql = '
				SELECT
					v.id AS venue_id,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					v.latitude AS venue_latitude,
					v.longitude AS venue_longitude,
					e.id,
					e.urlname,
					e.name,
					e.datetime_start,
					e.datetime_end,
					TIMEDIFF(e.datetime_start, ?) AS event_started,
					TIMEDIFF(e.datetime_end, ?) AS event_end,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					mer.member_id AS me_following
				FROM
					venues AS v
					INNER JOIN events AS e ON e.venue_id = v.id
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN djs AS d ON d.id = der.dj_id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				WHERE
					v.id != ?
					AND v.city_id = ?
					AND e.status = ?
					AND (POW((69.1*(v.longitude-'.$result->venue_long.')*cos('.$result->venue_lat.'/57.3)),2) + POW((69.1*(v.latitude-'.$result->venue_lat.')),2)) < (? * ?)
					AND (DATE_FORMAT(e.datetime_start, "%Y-%m-%d") = ? OR ? between e.datetime_start AND e.datetime_end)
				GROUP BY e.id
				';

				$result->closeby = $this->simple_fetch(
					$sql,
					array(
						$now,
						$now,
						Session::userdata('id'),
						$result->venue_id,
						$result->venue_city_id,
						'complete',
						$radius,
						$radius,
						$result->datetime_start->format('Y-m-d'),
						$now,
					)
				);

// Base::debug($result->closeby);

				$objects = array();

				foreach($result->closeby as $obj)
				{
					$google  = 'http://maps.googleapis.com/maps/api/distancematrix/json?origins='.$result->venue_lat.','.$result->venue_long.'&destinations='.$obj->venue_latitude.','.$obj->venue_longitude.'&mode=walking&sensor=false';
					$geocode = file_get_contents($google);
					$output  = json_decode($geocode);

					$obj->distance_meter = $output->rows[0]->elements[0]->distance->value;
					$obj->duration_seconds = $output->rows[0]->elements[0]->duration->value;

					$objects[$obj->duration_seconds][] = $obj;
				}

				ksort($objects);
				$result->closeby = $objects;
			}

			return $result;
		}


		// ##########################################################


		public function callback_events()
		{
			/*
				prepare lookup dates
			*/

			$now      = new fTimestamp();
			$today    = new fTimestamp();
			$tomorrow = new fTimestamp('+1 day');

			if($_POST['date'] == 'today')
			{
				$timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") = "'.$today->format('Y-m-d').'" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") >= "'.$tomorrow->format('Y-m-d').'" OR "'.$now.'" between e.datetime_start AND e.datetime_end';
			}

			elseif($_POST['date'] == 'tomorrow')
			{
				$timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") = "'.$tomorrow->format('Y-m-d').'" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") >= "'.$tomorrow->adjust('+1 day')->format('Y-m-d').'"';
			}

			elseif($_POST['date'] == 'thisweek')
			{
				$periods = Format::get_date_period($today->format('Y-m-d'), 'week');
				$periods['end'] = new fTimestamp($periods['end']);

				$timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") >= "'.$today->format('Y-m-d').'" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") <= "'.$periods['end']->adjust('+1 day')->format('Y-m-d').'"';
			}

			elseif($_POST['date'] == 'nextweek')
			{
				$periods = Format::get_date_period($today->format('Y-m-d'), 'week', 1);
				$periods['end'] = new fTimestamp($periods['end']);

				$timespan = 'DATE_FORMAT(e.datetime_start,"%Y-%m-%d") >= "'.$periods['start'].'" AND DATE_FORMAT(e.datetime_end,"%Y-%m-%d") <= "'.$periods['end']->adjust('+1 day')->format('Y-m-d').'"';
			}


			/*
				prepare entrance filter
			*/

			$entrance = NULL;

			if($_POST['costs'] == 'free')
			{
				$entrance = 'AND e.has_costs = "no"';
			}

			elseif(in_array($_POST['costs'], array(5,10,20)))
			{
				$entrance = 'AND e.has_costs = "yes" AND (e.cost_door IS NOT NULL AND e.cost_door <= '.$_POST['costs'].')';
			}


			/*
				prepare sql query
			*/

			if($_POST['filter'] == 'genres')
			{
				$sql = '
				SELECT
					DISTINCT g.id,
					g.name
				FROM
					events AS e
					LEFT JOIN dj_event_relations AS der ON der.event_id = e.id
					LEFT JOIN djs AS d ON d.id = der.dj_id
					LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					LEFT JOIN base_genres AS g ON g.id = dgr.genre_id
				WHERE
					e.city_id = ?
					AND e.status = ?
					AND g.name IS NOT NULL
					AND ('.$timespan.')
					'.$entrance.'
				ORDER BY g.name ASC
				';

				// if we received a filter by object

				$filter_by_object = NULL;

				if( ! empty($_POST['fid']) && Format::is_number($_POST['fid']))
				{
					$filter_by_object = 'AND dgr.genre_id = '.$_POST['fid'];

					$sql = '
					SELECT
						DISTINCT e.id,
						e.name,
						e.urlname,
						e.datetime_start,
						v.name AS venue
					FROM
						events AS e
						LEFT JOIN dj_event_relations AS der ON der.event_id = e.id
						LEFT JOIN djs AS d ON d.id = der.dj_id
						LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
						LEFT JOIN venues AS v ON v.id = e.venue_id
					WHERE
						e.city_id = ?
						AND e.status = ?
						AND ('.$timespan.')
						'.$filter_by_object.'
						'.$entrance.'
					ORDER BY e.datetime_start ASC
					';
				}
			}

			elseif($_POST['filter'] == 'djs')
			{
				$sql = '
				SELECT
					DISTINCT d.id,
					d.name
				FROM
					events AS e
					LEFT JOIN dj_event_relations AS der ON der.event_id = e.id
					LEFT JOIN djs AS d ON d.id = der.dj_id
				WHERE
					e.city_id = ?
					AND e.status = ?
					AND ('.$timespan.')
					'.$entrance.'
				ORDER BY d.name ASC
				';

				// if we received a filter by object

				$filter_by_object = NULL;

				if( ! empty($_POST['fid']) && Format::is_number($_POST['fid']))
				{
					$filter_by_object = 'AND der.dj_id = '.$_POST['fid'];

					$sql = '
					SELECT
						DISTINCT e.id,
						e.name,
						e.urlname,
						e.datetime_start,
						v.name AS venue
					FROM
						events AS e
						LEFT JOIN dj_event_relations AS der ON der.event_id = e.id
						LEFT JOIN venues AS v ON v.id = e.venue_id
					WHERE
						e.city_id = ?
						AND e.status = ?
						AND ('.$timespan.')
						'.$filter_by_object.'
						'.$entrance.'
					ORDER BY e.datetime_start ASC
					';
				}
			}

			elseif($_POST['filter'] == 'venues')
			{
				$sql = '
				SELECT
					DISTINCT v.id,
					v.name
				FROM
					events AS e
					LEFT JOIN venues AS v ON v.id = e.venue_id
				WHERE
					e.city_id = ?
					AND e.status = ?
					AND ('.$timespan.')
					'.$entrance.'
				ORDER BY v.name ASC
				';

				// if we received a filter by object

				$filter_by_object = NULL;

				if( ! empty($_POST['fid']) && Format::is_number($_POST['fid']))
				{
					$filter_by_object = 'AND e.venue_id = '.$_POST['fid'];

					$sql = '
					SELECT
						DISTINCT e.id,
						e.name,
						e.urlname,
						e.datetime_start,
						v.name AS venue
					FROM
						events AS e
						LEFT JOIN venues AS v ON v.id = e.venue_id
					WHERE
						e.city_id = ?
						AND e.status = ?
						AND ('.$timespan.')
						'.$filter_by_object.'
						'.$entrance.'
					ORDER BY e.datetime_start ASC
					';
				}
			}


			/*
				query
			*/

			$objects = array();

			foreach($this->simple_fetch($sql, array($_POST['city'], 'complete')) as $obj)
			{
				$date = new fTimestamp($obj->datetime_start);
				$obj->datetime_start = $date->format('D, d.m.Y');

				$objects[] = $obj;
			}

			echo Format::json($objects);
		}
}
