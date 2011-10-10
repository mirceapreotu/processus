<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/4/11
 * Time: 9:13 PM
 * To change this template use File | Settings | File Templates.
 */

class App_Manager_App_DashBoardManager extends App_Manager_AbstractManager
{
    public function get_stream($member_id = '')
		{
			$stream = array();

			// follow: events

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				e.id AS event_id,
				e.urlname AS event_urlname,
				e.name AS event_name,
				bc.name AS event_city,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN events AS e ON e.id = ml.for_object_id
				INNER JOIN base_cities AS bc ON bc.id = e.city_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'event')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->event_id] = array(
					'obj'       => array(
						'type'    => 'events',
						'id'      => $obj->event_id,
						'urlname' => $obj->event_urlname,
						'name'    => $obj->event_name,
						'city'    => $obj->event_city
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// follow: people

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				fm.id AS follow_member_id,
				fm.username AS follow_member_username,
				fm.fullname AS follow_member_fullname,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN members AS fm ON fm.id = ml.for_object_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'member')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->follow_member_id] = array(
					'obj'       => array(
						'type'     => 'people',
						'id'       => $obj->follow_member_id,
						'urlname'  => $obj->follow_member_username,
						'name'     => $obj->follow_member_fullname,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// follow: djs

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				d.id AS dj_id,
				d.name AS dj_name,
				d.urlname AS dj_urlname,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN djs AS d ON d.id = ml.for_object_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'dj')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->dj_urlname] = array(
					'obj'       => array(
						'type'     => 'djs',
						'urlname'  => $obj->dj_urlname,
						'name'     => $obj->dj_name,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// follow: venues

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				v.id AS venue_id,
				v.name AS venue_name,
				v.urlname AS venue_urlname,
				bc.name AS venue_city,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN venues AS v ON v.id = ml.for_object_id
				INNER JOIN base_cities AS bc ON bc.id = v.city_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'venue')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->venue_urlname] = array(
					'obj'       => array(
						'type'     => 'venues',
						'urlname'  => $obj->venue_urlname,
						'name'     => $obj->venue_name,
						'city'     => $obj->venue_city
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// comments: people

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				fm.id AS follow_member_id,
				fm.username AS follow_member_username,
				fm.fullname AS follow_member_fullname,
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN members AS fm ON fm.id = ml.for_object_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'comment_people')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->follow_member_id] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'people',
						'id'         => $obj->follow_member_id,
						'urlname'    => $obj->follow_member_username,
						'name'       => $obj->follow_member_fullname,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// comments: events

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				e.id AS event_id,
				e.urlname AS event_urlname,
				e.name AS event_name,
				bc.name AS event_city,
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN events AS e ON e.id = ml.for_object_id
				INNER JOIN base_cities AS bc ON bc.id = e.city_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'comment_events')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->event_id] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'events',
						'id'         => $obj->event_id,
						'urlname'    => $obj->event_urlname,
						'name'       => $obj->event_name,
						'city'       => $obj->event_city,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// comments: djs

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				d.id AS dj_id,
				d.urlname AS dj_urlname,
				d.name AS dj_name,
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN djs AS d ON d.id = ml.for_object_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'comment_djs')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->dj_urlname] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'djs',
						'id'         => $obj->dj_id,
						'urlname'    => $obj->dj_urlname,
						'name'       => $obj->dj_name,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// comments: venues

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				v.id AS venue_id,
				v.urlname AS venue_urlname,
				v.name AS venue_name,
				bc.name AS venue_city,
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN venues AS v ON v.id = ml.for_object_id
				INNER JOIN base_cities AS bc ON bc.id = v.city_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'comment_venues')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->venue_urlname] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'venues',
						'id'         => $obj->venue_id,
						'urlname'    => $obj->venue_urlname,
						'name'       => $obj->venue_name,
						'city'       => $obj->venue_city,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname
					),
					'created'   => $obj->created
				);
			}

			// rated: events

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				e.id AS event_id,
				e.urlname AS event_urlname,
				e.name AS event_name,
				mr_event.rating AS event_rating,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN events AS e ON e.id = ml.for_object_id
				INNER JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'rated_event')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->event_id] = array(
					'obj'       => array(
						'type'    => 'rating',
						'section' => 'events',
						'id'      => $obj->event_id,
						'urlname' => $obj->event_urlname,
						'name'    => $obj->event_name,
						'rating'  => $obj->event_rating,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname,
					),
					'created'   => $obj->created
				);
			}

			// rated: djs

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				d.urlname AS dj_urlname,
				d.name AS dj_name,
				mr_dj.rating AS dj_rating,
				ml.created
			FROM
				member_member_relations AS mmr
				INNER JOIN member_logs AS ml ON ml.member_id = mmr.follow_member_id
				INNER JOIN members AS m ON m.id = ml.member_id
				INNER JOIN djs AS d ON d.id = ml.for_object_id
				INNER JOIN member_dj_ratings AS mr_dj ON mr_dj.dj_id = d.id
			WHERE
				mmr.member_id = ?
				AND ml.action_type = ?
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array($member_id, 'rated_dj')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->dj_urlname] = array(
					'obj'       => array(
						'type'    => 'rating',
						'section' => 'djs',
						'urlname' => $obj->dj_urlname,
						'name'    => $obj->dj_name,
						'rating'  => $obj->dj_rating,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname,
					),
					'created'   => $obj->created
				);
			}

			// sort and return

			krsort($stream);

			$stream = Format::array_shrink($stream, 20);

			return $stream;
		}


		// ##########################################################


		public function get_djs_alert()
		{
			$sql = '
			SELECT
				DATEDIFF(e.datetime_start, ?) AS days_left,
				d.id,
				d.name,
				d.urlname,
				d.url_soundcloud,
				d.url_facebook,
				d.url_myspace,
				e.urlname AS event_urlname,
				e.name AS event_name
			FROM
				events AS e
				INNER JOIN dj_event_relations AS der ON der.event_id = e.id
				INNER JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id
				INNER JOIN djs AS d ON d.id = mdr.dj_id
			WHERE
				e.status = ?
				AND e.city_id = ?
				AND e.datetime_start >= ?
				AND mdr.member_id = ?
				AND DATEDIFF(e.datetime_start, ?) <= ?
			GROUP BY e.id, d.id
			ORDER BY e.datetime_start ASC
			';

			return $this->simple_fetch($sql, array(Base::get_date(), 'complete', Session::userdata('city_id'), Base::get_date(), Session::userdata('id'), Base::get_date(), 10));
		}


		// ##########################################################


		public function get_running_events()
		{
			$objects  = array();
			$now      = new fTimestamp();
			$timespan = '? between e.datetime_start AND e.datetime_end';

			$sql = '
			SELECT
				e.id,
				e.urlname,
				e.name,
				v.urlname AS venue_urlname,
				v.name AS venue_name,
				e.datetime_start,
				e.datetime_end,
				GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres
			FROM
				events AS e
				INNER JOIN venues AS v ON v.id = e.venue_id
				INNER JOIN dj_event_relations AS der ON der.event_id = e.id
				INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = der.dj_id
				INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
			WHERE
				e.city_id = ?
				AND e.status = ?
				AND '.$timespan.'
			GROUP BY e.id
			ORDER BY e.datetime_end ASC
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'complete', $now)) as $obj)
			{
				$objects[] = $obj;
			}

			return $objects;
		}
}
