<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/4/11
 * Time: 9:18 PM
 * To change this template use File | Settings | File Templates.
 */

class App_Manager_App_VenuesManager extends App_Manager_AbstractManager
{
    public function listing()
		{
			$sql = '
			SELECT
				v.id,
				v.urlname,
				v.name,
				AVG(mr_event.rating) AS rating,
				mvr.member_id AS me_following
			FROM
				venues AS v
				LEFT JOIN events AS e ON e.venue_id = v.id AND e.status = ?
				LEFT JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
				LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = v.id AND mvr.member_id = ?
			WHERE
				v.city_id = ?
				AND v.status != ?
			GROUP BY v.id
			ORDER BY v.name ASC
			';

			$venues['all'] = $this->simple_fetch($sql, array('complete', Session::userdata('id'), Session::userdata('city_id'), 'review'));

			// top followed

/*
			$sql = '
			SELECT
				v.id,
				v.urlname,
				v.name,
				AVG(mr_event.rating) AS rating,
				COUNT(mvr_followers.member_id) AS count,
				mvr.member_id AS me_following
			FROM
				venues AS v
				INNER JOIN member_venue_relations AS mvr_followers ON mvr_followers.venue_id = v.id
				INNER JOIN events AS e ON e.venue_id = v.id
				LEFT JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
				LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = v.id AND mvr.member_id = ?
			WHERE
				v.city_id = ?
			GROUP BY v.id, mvr.member_id
			ORDER BY count DESC, v.name ASC
			LIMIT 10
			';

			$venues['top_followed'] = $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('city_id')));
*/

			return $venues;
		}


		// ##########################################################


		public function get_profile($venue_urlname = '')
		{
			$sql = '
			SELECT
				v.id,
				v.name,
				v.latitude,
				v.longitude,
				v.address,
				v.url_website,
				bc.name AS city,
				bc.currency AS currency,
				mvr.member_id AS me_following
			FROM
				venues AS v
				INNER JOIN base_cities AS bc ON bc.id = v.city_id
				LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = v.id AND mvr.member_id = ?
			WHERE v.urlname = ?
			';

			$profile = $this->simple_fetchobject($sql, array(Session::userdata('id'), $venue_urlname));

			if($profile->id)
			{
				$upcoming_recent_days = 10;

				// genres

				$sql = '
				SELECT
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR "^") AS genres
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = der.dj_id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
				WHERE
					e.venue_id = ?
				';

				$profile->genres = $this->simple_fetchvalue($sql, array($profile->id));
				$profile->genres = $profile->genres ? Format::string_explode($profile->genres, '^') : array();

				// followers

				$sql = 'SELECT count(mvr.venue_id) FROM member_venue_relations AS mvr WHERE mvr.venue_id = ?';
				$profile->followers = $this->simple_fetchvalue($sql, array($profile->id));

				// admission

				$sql = 'SELECT AVG(e.cost_door) AS avg_cost_door FROM events AS e WHERE e.venue_id = ?';
				$profile->avg_admission = $this->simple_fetchvalue($sql, array($profile->id));

				// ratings via events

				$sql = '
				SELECT
					COUNT(mr_event.event_id) AS count,
					AVG(mr_event.rating) AS rating
				FROM
					events AS e
					INNER JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
				WHERE
					e.venue_id = ?
				GROUP BY mr_event.event_id
				';

				$profile->avg_rating = $this->simple_fetchobject($sql, array($profile->id));

				// recent visitors

				$sql = '
				SELECT
					m.id,
					m.fullname,
					m.username,
					bc.name AS city,
					e.id AS event_id,
					e.urlname AS event_urlname,
					e.name AS event_name,
					e.datetime_start AS event_datetime_start,
					mmr_me.member_id AS me_following
				FROM
					events AS e
					INNER JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
					INNER JOIN members AS m ON m.id = mr_event.member_id
					INNER JOIN base_cities AS bc ON bc.id = m.city_id
					LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = m.id AND mmr_me.member_id = ?
				WHERE
					e.venue_id = ?
				ORDER BY mr_event.created DESC
				LIMIT 10
				';

				$profile->recent_visitors = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));

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

				$profile->recent_followers = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'venue'));

				// events running

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
					AND TIMEDIFF(e.datetime_start, ?) < "00:00:00"
					AND TIMEDIFF(e.datetime_end, ?) > "00:00:00"
				GROUP BY e.id
				ORDER BY e.datetime_start ASC
				';

				$profile->events['running'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE), Base::get_date(TRUE)));

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
					AND DATEDIFF(e.datetime_start, ?) <= ?
				GROUP BY e.id
				ORDER BY e.datetime_start ASC
				';

				$profile->events['upcoming'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE), Base::get_date(TRUE), $upcoming_recent_days));

				// events recent

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
					AVG(mr_event.rating) AS avg_rating,
					mer.member_id AS me_following
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN djs AS d ON d.id = der.dj_id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
					LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				WHERE
					e.venue_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_end, ?) < "00:00:00"
				GROUP BY e.id
				ORDER BY e.datetime_end DESC
				LIMIT 10
				';

				$recent_events_ids = array();

				foreach($this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE))) as $obj)
				{
					$profile->events['recent'][] = $obj;
					$recent_events_ids[] = $obj->id;
				}

				// djs running

				$sql = '
				SELECT
					d.id,
					d.name,
					d.urlname,
					d.url_soundcloud,
					d.url_facebook,
					d.url_myspace,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					e.id AS event_id,
					e.name AS event_name,
					e.datetime_start AS event_datetime_start,
					mdr.member_id AS me_following
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN djs AS d ON d.id = der.dj_id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
				WHERE
					e.venue_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_start, ?) < "00:00:00"
					AND TIMEDIFF(e.datetime_end, ?) > "00:00:00"
				GROUP BY d.id
				ORDER BY d.name ASC
				';

				$profile->djs['running'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE), Base::get_date(TRUE)));

				// djs upcoming

				$sql = '
				SELECT
					d.id,
					d.name,
					d.urlname,
					d.url_soundcloud,
					d.url_facebook,
					d.url_myspace,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					e.id AS event_id,
					e.name AS event_name,
					e.datetime_start AS event_datetime_start,
					mdr.member_id AS me_following
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN djs AS d ON d.id = der.dj_id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
				WHERE
					e.venue_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_start, ?) > "00:00:00"
					AND DATEDIFF(e.datetime_start, ?) <= ?
				GROUP BY d.id
				ORDER BY e.datetime_start ASC, d.name ASC
				';

				$profile->djs['upcoming'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE), Base::get_date(TRUE), $upcoming_recent_days));

				// djs recent

				if( ! empty($recent_events_ids))
				{
					$sql = '
					SELECT
						d.id,
						d.name,
						d.urlname,
						d.url_soundcloud,
						d.url_facebook,
						d.url_myspace,
						GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
						e.id AS event_id,
						e.name AS event_name,
						e.datetime_start AS event_datetime_start,
						AVG(mr_dj.rating) AS rating,
						mdr.member_id AS me_following
					FROM
						events AS e
						INNER JOIN dj_event_relations AS der ON der.event_id = e.id
						INNER JOIN djs AS d ON d.id = der.dj_id
						INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
						INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
						LEFT JOIN member_dj_ratings AS mr_dj ON mr_dj.dj_id = d.id AND mr_dj.event_id = e.id
						LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
					WHERE
						e.id IN ('.Format::array_join($recent_events_ids, ', ').')
						AND e.venue_id = ?
						AND e.status = ?
						AND TIMEDIFF(e.datetime_end, ?) < "00:00:00"
					GROUP BY d.id
					ORDER BY e.datetime_end DESC, d.name ASC
					';

					$profile->djs['recent'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE)));
				}

				// djs top booked

				$sql = '
				SELECT
					COUNT(d.id) AS gigs,
					d.id,
					d.name,
					d.urlname,
					d.url_soundcloud,
					d.url_facebook,
					d.url_myspace,
					(
						SELECT
							GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ")
						FROM
							dj_genre_relations AS dgr
							INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
						WHERE
							 dgr.dj_id = d.id
					) AS genres,
					mdr.member_id AS me_following
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN djs AS d ON d.id = der.dj_id
					LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
				WHERE
					e.venue_id = ?
					AND e.status = ?
				GROUP BY d.id
				ORDER BY gigs DESC, d.name ASC
				LIMIT 10
				';

				$profile->djs['top'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete'));
			}

			return $profile;
		}
}
