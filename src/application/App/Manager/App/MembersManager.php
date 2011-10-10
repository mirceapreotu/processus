<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/4/11
 * Time: 9:17 PM
 * To change this template use File | Settings | File Templates.
 */

class App_Manager_App_MembersManager extends App_Manager_AbstractManager
{
    public function add($data)
		{
			$user_id = $this->simple_fetchvalue('SELECT id FROM members WHERE id = ?', array($data['id']));

			// new user

			if(empty($user_id))
			{
				$user_id = $data['id'];

				$this->simple_query(
					'INSERT INTO members VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())',
					array(
						$data['id'],
						NULL,
						$data['first_name'],
						$data['last_name'],
						$data['name'],
						Format::string_urlable($data['name'], '-'),
						$data['gender'] == 'male' ? 'm' : 'f',
						Format::array_shift(Format::string_explode($data['locale'], '_'))
					)
				);

				// auto follow by idaaa

				// $this->follow_member('100002419374183');
			}
		}


		// ##########################################################


		public function access_log($user_id)
		{
			$this->simple_query('INSERT INTO member_logins VALUES(?,NOW())', array($user_id));
			Session::metadata('accessed_'.date('YMD:H'), 1);
		}


		// ##########################################################


		public function get($member_id = '')
		{
			$sql = '
			SELECT
				m.id,
				m.city_id,
				m.firstname,
				m.fullname,
				m.username,
				m.language,
				bc.name AS city_name
			FROM
				members AS m
				LEFT JOIN base_cities AS bc ON bc.id = m.city_id
			WHERE m.id = ?
			';

			return $this->simple_fetchobject($sql, array($member_id));
		}


		// ##########################################################


		public function get_profile($member_username = '')
		{
			$sql = '
			SELECT
				m.id,
				m.city_id,
				m.fullname,
				m.username,
				bc.name AS city,
				mmr.member_id AS me_following
			FROM
				members AS m
				LEFT JOIN base_cities AS bc ON bc.id = m.city_id
				LEFT JOIN member_member_relations AS mmr ON mmr.follow_member_id = m.id AND mmr.member_id = ?
			WHERE
				m.username = ?
			';

			$profile = $this->simple_fetchobject($sql, array(Session::userdata('id'), $member_username));

			if($profile->id)
			{
				// genres

				$sql = '
				SELECT
					bg.id,
					bg.name
				FROM
					member_genre_relations AS mgr
					INNER JOIN base_genres AS bg ON bg.id = mgr.genre_id
				WHERE
					mgr.member_id = ?
				ORDER BY bg.name ASC
				';

				$profile->genres = $this->simple_fetch($sql, array($profile->id));

				// followers

				$sql = '
				SELECT
					m.id,
					m.fullname,
					m.username,
					bc.name AS city,
					mmr_me.member_id AS me_following
				FROM
					member_member_relations AS mmr
					LEFT JOIN members AS m ON m.id = mmr.member_id
					LEFT JOIN base_cities AS bc ON bc.id = m.city_id
					LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = mmr.member_id AND mmr_me.member_id = ?
				WHERE
					mmr.follow_member_id = ?
				ORDER BY m.fullname ASC
				';

				$profile->followers = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));

				// following

				$sql = '
				SELECT
					m.id,
					m.fullname,
					m.username,
					bc.name AS city,
					mmr_me.member_id AS me_following
				FROM
					member_member_relations AS mmr
					LEFT JOIN members AS m ON m.id = mmr.follow_member_id
					LEFT JOIN base_cities AS bc ON bc.id = m.city_id
					LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = mmr.follow_member_id AND mmr_me.member_id = ?
				WHERE
					mmr.member_id = ?
				ORDER BY m.fullname ASC
				';

				$profile->following = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));

				// events running

				$sql = '
				SELECT
					e.id,
					e.urlname,
					e.name,
					e.datetime_start,
					e.datetime_end,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					bc.name AS venue_city,
					mer_me.member_id AS me_following
				FROM
					member_event_relations AS mer
					INNER JOIN events AS e ON e.id = mer.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = e.city_id
					LEFT JOIN member_event_relations AS mer_me ON mer_me.event_id = mer.event_id AND mer_me.member_id = ?
				WHERE
					mer.member_id = ?
					AND TIMEDIFF(e.datetime_start, ?) < "00:00:00"
					AND TIMEDIFF(e.datetime_end, ?) > "00:00:00"
				ORDER BY e.datetime_start ASC
				';

				$profile->events['running'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, Base::get_date(TRUE), Base::get_date(TRUE)));

				// events upcoming

				$sql = '
				SELECT
					e.id,
					e.urlname,
					e.name,
					e.datetime_start,
					e.datetime_end,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					bc.name AS venue_city,
					mer_me.member_id AS me_following
				FROM
					member_event_relations AS mer
					INNER JOIN events AS e ON e.id = mer.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = e.city_id
					LEFT JOIN member_event_relations AS mer_me ON mer_me.event_id = mer.event_id AND mer_me.member_id = ?
				WHERE
					mer.member_id = ?
					AND TIMEDIFF(e.datetime_start, ?) > "00:00:00"
				ORDER BY e.datetime_start ASC
				';

				$profile->events['upcoming'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, Base::get_date(TRUE)));

				// events past/seen

				$sql = '
				SELECT
					e.id,
					e.urlname,
					e.name,
					e.datetime_start,
					e.datetime_end,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					bc.name AS venue_city,
					mr_event.rating,
					mer_me.member_id AS me_following
				FROM
					member_event_relations AS mer
					INNER JOIN events AS e ON e.id = mer.event_id
					INNER JOIN base_cities AS bc ON bc.id = e.city_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					LEFT JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id AND mr_event.member_id = mer.member_id
					LEFT JOIN member_event_relations AS mer_me ON mer_me.event_id = mer.event_id AND mer_me.member_id = ?
				WHERE
					mer.member_id = ?
					AND TIMEDIFF(e.datetime_end, ?) < "00:00:00"
				ORDER BY e.datetime_start DESC
				';

				foreach($this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, Base::get_date(TRUE))) as $obj)
				{
					if($obj->rating)
					{
						$profile->events['seen'][] = $obj;
					}
					else
					{
						$profile->events['past'][] = $obj;
					}
				}

				// following djs

				$sql = '
				SELECT
					d.id,
					d.name,
					d.urlname,
					d.url_soundcloud,
					d.url_facebook,
					d.url_myspace,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					mdr_me.member_id AS me_following
				FROM
					member_dj_relations AS mdr
					LEFT JOIN djs AS d ON d.id = mdr.dj_id
					LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					LEFT JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_dj_relations AS mdr_me ON mdr_me.dj_id = mdr.dj_id AND mdr_me.member_id = ?
				WHERE
					mdr.member_id = ?
				GROUP BY d.id
				ORDER BY d.name ASC
				';

				$profile->djs_all = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));

				// djs seen

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
					e.urlname AS event_urlname,
					e.name AS event_name,
					mr_dj.rating,
					mdr_me.member_id AS me_following
				FROM
					member_dj_ratings AS mr_dj
					INNER JOIN events AS e ON e.id = mr_dj.event_id
					INNER JOIN djs AS d ON d.id = mr_dj.dj_id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_dj_relations AS mdr_me ON mdr_me.dj_id = d.id AND mdr_me.member_id = ?
				WHERE
					mr_dj.member_id = ?
				GROUP BY mr_dj.event_id, d.id
				ORDER BY e.datetime_end DESC, d.name ASC
				';

				$profile->djs_seen = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));

				// djs alert

				$sql = '
				SELECT
					DATEDIFF(e.datetime_start, ?) AS days_left,
					d.id,
					d.name,
					d.urlname,
					d.url_soundcloud,
					d.url_facebook,
					d.url_myspace,
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
					e.id AS event_id,
					e.name AS event_name,
					v.id AS venue_id,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					mdr_me.member_id AS me_following
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id
					INNER JOIN djs AS d ON d.id = mdr.dj_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
					LEFT JOIN base_genres AS bg ON bg.id = dgr.genre_id
					LEFT JOIN member_dj_relations AS mdr_me ON mdr_me.dj_id = mdr.dj_id AND mdr_me.member_id = ?
				WHERE
					e.status = ?
					AND e.city_id = ?
					AND e.datetime_start >= ?
					AND mdr.member_id = ?
					AND DATEDIFF(e.datetime_start, ?) <= ?
				GROUP BY d.id
				ORDER BY e.datetime_start ASC, d.name ASC
				';

				$profile->djs_alert = $this->simple_fetch($sql, array(Base::get_date(), Session::userdata('id'), 'complete', $profile->city_id, Base::get_date(), $profile->id, Base::get_date(), 10));

				// following venues

				$sql = '
				SELECT
					v.id,
					v.urlname,
					v.name,
					bc.name AS city,
					mvr_me.member_id AS me_following
				FROM
					member_venue_relations AS mvr
					LEFT JOIN venues AS v ON v.id = mvr.venue_id
					LEFT JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_venue_relations AS mvr_me ON mvr_me.venue_id = mvr.venue_id AND mvr_me.member_id = ?
				WHERE
					mvr.member_id = ?
				ORDER BY v.name ASC
				';

				$profile->venues_all = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));

				// venues seen

				$sql = '
				SELECT
					v.id,
					v.urlname,
					v.name,
					e.id AS event_id,
					e.urlname AS event_urlname,
					e.name AS event_name,
					e.datetime_start AS event_datetime_start,
					bc.name AS city,
					mr_event.rating,
					mvr_me.member_id AS me_following
				FROM
					member_event_ratings AS mr_event
					INNER JOIN events AS e ON e.id = mr_event.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_venue_relations AS mvr_me ON mvr_me.venue_id = v.id AND mvr_me.member_id = ?
				WHERE
					mr_event.member_id = ?
				ORDER BY e.datetime_end DESC, v.name ASC
				';

				$profile->venues_seen = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id));
			}

			return $profile;
		}


		// ##########################################################


		public function get_rating_objects($event_id = '')
		{
			// make sure we're following

			$exists = $this->simple_fetchvalue('SELECT event_id FROM member_event_relations WHERE event_id = ? AND member_id = ?', array($event_id, Session::userdata('id')));

			if(empty($exists))
			{
				$this->simple_query('INSERT INTO member_event_relations VALUES(?,?)', array($event_id, Session::userdata('id')));
			}

			// get rating objects

			$sql = '
			SELECT
				e.id,
				e.urlname,
				e.name,
				mr_event.rating,
				v.id AS venue_id,
				v.urlname AS venue_urlname,
				v.name AS venue_name
			FROM
				member_event_relations AS mer
				INNER JOIN events AS e ON e.id = mer.event_id
				INNER JOIN venues AS v ON v.id = e.venue_id
				LEFT JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id AND mr_event.member_id = mer.member_id
			WHERE
				mer.member_id = ?
				AND mer.event_id = ?
				AND TIMEDIFF(e.datetime_end, ?) < "00:00:00"
			';

			$event = $this->simple_fetchobject($sql, array(Session::userdata('id'), $event_id, Base::get_date(TRUE)));

			// djs

			if($event->id)
			{
				$sql = '
				SELECT
					d.id,
					d.name,
					mr_dj.rating
				FROM
					dj_event_relations AS der
					INNER JOIN djs AS d ON d.id = der.dj_id
					LEFT JOIN member_dj_ratings AS mr_dj ON mr_dj.event_id = der.event_id AND mr_dj.dj_id = d.id AND mr_dj.member_id = ?
				WHERE
					der.event_id = ?
				ORDER BY d.name ASC
				';

				$event->djs = $this->simple_fetch($sql, array(Session::userdata('id'), $event->id));
			}
			else
			{
				$event = FALSE;
			}

			return $event;
		}


		// ##########################################################


		public function save_rating($event_obj, $data)
		{
			foreach($data['rating'] as $field_id => $field_val)
			{
				$field_val['old'] = Format::array_shift($field_val['old']);
				$field_val['set'] = Format::array_shift($field_val['set']);

/*
				Base::debug($field_val, FALSE);
				echo $field_val['old'] != $field_val['set'] ? '!' : 'false';
				echo '<hr>';
*/

				// save event rating

				if($field_id == 'rate_event')
				{
					if($field_val['old'] != '')
					{
						$this->simple_query('DELETE FROM member_event_ratings WHERE member_id = ? AND event_id = ?', array(Session::userdata('id'), $event_obj->id));
					}

					if($field_val['set'] != '')
					{
						$this->simple_query('INSERT INTO member_event_ratings VALUES(?,?,?,NOW())', array(Session::userdata('id'), $event_obj->id, $field_val['set']));

						if( ! $field_val['old'] && $field_val['set'])
						{
							$this->action_log('rated_event', $event_obj->id);
						}
					}
				}

				// save dj rating

				else
				{
					$dj_id = Format::string_replace($field_id, 'rate_dj_', '');

					if($field_val['old'] != '')
					{
						$this->simple_query('DELETE FROM member_dj_ratings WHERE member_id = ? AND event_id = ? AND dj_id = ?', array(Session::userdata('id'), $event_obj->id, $dj_id));
					}

					if($field_val['set'] != '')
					{
						$this->simple_query('INSERT INTO member_dj_ratings VALUES(?,?,?,?,NOW())', array(Session::userdata('id'), $event_obj->id, $dj_id, $field_val['set']));

						if( ! $field_val['old'] && $field_val['set'])
						{
							$this->action_log('rated_dj', $dj_id);
						}
					}
				}

			}
		}


		// ##########################################################


		public function update_settings($data)
		{
			$this->simple_query('UPDATE members SET username=?, city_id=? WHERE id=?', array($data['username']['set'], $data['city_id']['set'], Session::userdata('id')));
		}


		// ##########################################################


		public function get_blocked_genres($member_id = '')
		{
			$sql = '
			SELECT
				mb.section_id,
				bg.name
			FROM
				member_blocks AS mb
				INNER JOIN base_genres AS bg ON bg.id = mb.section_id
			WHERE
				mb.section = ?
				AND mb.member_id = ?
			ORDER BY bg.name ASC
			';

			return $this->simple_fetch($sql, array('genres', $member_id));
		}


		// ##########################################################


		public function update_city($city_id = '')
		{
			$this->simple_query('UPDATE members SET city_id = ? WHERE id = ?', array($city_id, Session::userdata('id')));
		}


		// ##########################################################


		public function follow_member($member_id = '')
		{
			if($member_id)
			{
				$exists = $this->simple_fetchvalue('SELECT follow_member_id FROM member_member_relations WHERE follow_member_id = ? AND member_id = ?', array($member_id, Session::userdata('id')));

				if(empty($exists))
				{
					$this->simple_query('INSERT INTO member_member_relations VALUES(?,?)', array($member_id, Session::userdata('id')));
					$this->action_log('member', $member_id);
					echo 'inserted';
				}
				else
				{
					$this->simple_query('DELETE FROM member_member_relations WHERE follow_member_id = ? AND member_id = ?', array($member_id, Session::userdata('id')));
					echo 'deleted';
				}
			}
		}


		// ##########################################################


		public function follow_dj($dj_id = '')
		{
			$exists = $this->simple_fetchvalue('SELECT dj_id FROM member_dj_relations WHERE dj_id = ? AND member_id = ?', array($dj_id, Session::userdata('id')));

			if(empty($exists))
			{
				$this->simple_query('INSERT INTO member_dj_relations VALUES(?,?)', array($dj_id, Session::userdata('id')));
				$this->action_log('dj', $dj_id);
				echo 'inserted';
			}
			else
			{
				$this->simple_query('DELETE FROM member_dj_relations WHERE dj_id = ? AND member_id = ?', array($dj_id, Session::userdata('id')));
				echo 'deleted';
			}

			// update member genre relations

			$this->simple_query('DELETE FROM member_genre_relations WHERE member_id = ?', array(Session::userdata('id')));

			$sql = '
			INSERT INTO member_genre_relations (genre_id, member_id)
			SELECT
				DISTINCT dgr.genre_id,
				m.id
			FROM
				members AS m
				INNER JOIN member_dj_relations AS mdr ON mdr.member_id = m.id
				INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = mdr.dj_id
				LEFT JOIN member_blocks AS mb ON mb.section_id = dgr.genre_id AND mb.section = ? AND mb.member_id = ?
			WHERE
				m.id = ?
				AND mb.section_id IS NULL
			';

			$this->simple_query($sql, array('genres', Session::userdata('id'), Session::userdata('id')));
		}


		// ##########################################################


		public function follow_event($event_id = '')
		{
			$exists = $this->simple_fetchvalue('SELECT event_id FROM member_event_relations WHERE event_id = ? AND member_id = ?', array($event_id, Session::userdata('id')));

			if(empty($exists))
			{
				$this->simple_query('INSERT INTO member_event_relations VALUES(?,?)', array($event_id, Session::userdata('id')));
				$this->action_log('event', $event_id);
				echo 'inserted';
			}
			else
			{
				$this->simple_query('DELETE FROM member_event_relations WHERE event_id = ? AND member_id = ?', array($event_id, Session::userdata('id')));
				echo 'deleted';
			}
		}


		// ##########################################################


		public function follow_venue($venue_id = '')
		{
			$exists = $this->simple_fetchvalue('SELECT venue_id FROM member_venue_relations WHERE venue_id = ? AND member_id = ?', array($venue_id, Session::userdata('id')));

			if(empty($exists))
			{
				$this->simple_query('INSERT INTO member_venue_relations VALUES(?,?)', array($venue_id, Session::userdata('id')));
				$this->action_log('venue', $venue_id);
				echo 'inserted';
			}
			else
			{
				$this->simple_query('DELETE FROM member_venue_relations WHERE venue_id = ? AND member_id = ?', array($venue_id, Session::userdata('id')));
				echo 'deleted';
			}
		}


		// ##########################################################


		public function block_genre($genre_id = '')
		{
			$exists = $this->simple_fetchvalue('SELECT section_id FROM member_blocks WHERE section_id = ? AND section = ? AND member_id = ?', array($genre_id, 'genres', Session::userdata('id')));

			if(empty($exists))
			{
				$this->simple_query('INSERT INTO member_blocks VALUES(?,?,?)', array(Session::userdata('id'), 'genres', $genre_id));
				$this->simple_query('DELETE FROM member_genre_relations WHERE genre_id = ? AND member_id = ?', array($genre_id, Session::userdata('id')));
				$this->action_log('genre_blocked', $genre_id);
				echo 'inserted';
			}
			else
			{
				$this->simple_query('DELETE FROM member_blocks WHERE section_id = ? AND section = ? AND member_id = ?', array($genre_id, 'genres', Session::userdata('id')));
				$this->simple_query('INSERT INTO member_genre_relations VALUES(?,?)', array($genre_id, Session::userdata('id')));
				$this->action_log('genre_unblocked', $genre_id);
				echo 'deleted';
			}
		}


		// ##########################################################


		public function add_comment($data)
		{
			$comment_id = $this->simple_query('INSERT INTO member_comments VALUES(NULL, ?, ?, ?, ?, NOW())', $data);

			// add log
			$this->action_log('comment_'.$data['section'], $data['section_id'], $comment_id);

			return $comment_id;
		}


		// ##########################################################


		public function get_comments($section = '', $section_id = '')
		{
			$sql = '
			SELECT
				mc.id AS id,
				mc.comment,
				mc.created,
				m.id AS member_id,
				m.fullname AS member_fullname,
				m.username AS member_username
			FROM
				member_comments AS mc
				INNER JOIN members AS m ON m.id = mc.member_id
			WHERE
				section = ?
				AND section_id = ?
			ORDER BY mc.created ASC
			';

			return $this->simple_fetch($sql, array($section, $section_id));
		}


		// ##########################################################


		public function action_log($type = '', $object_id = '', $item_id = NULL)
		{
			if($type && $object_id)
			{
				$this->simple_query('INSERT INTO member_logs VALUES(?,?,?,?,NOW())', array(Session::userdata('id'), $type, $object_id, $item_id));
			}
		}


		// ##########################################################


		public function latest_event_activities()
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
				ml.created
			FROM
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN events AS e ON e.id = ml.for_object_id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'event')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->event_id] = array(
					'obj'       => array(
						'type'    => 'follow',
						'id'      => $obj->event_id,
						'urlname' => $obj->event_urlname,
						'name'    => $obj->event_name,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname,
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
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN events AS e ON e.id = ml.for_object_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'comment_events')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->event_id] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'events',
						'id'         => $obj->event_id,
						'urlname'    => $obj->event_urlname,
						'name'       => $obj->event_name,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname,
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
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN events AS e ON e.id = ml.for_object_id
				INNER JOIN member_event_ratings AS mr_event ON mr_event.event_id = e.id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'rated_event')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->event_id] = array(
					'obj'       => array(
						'type'    => 'rating',
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

			// sort and return

			krsort($stream);

			$stream = Format::array_shrink($stream, 20);

			return $stream;
		}


		// ##########################################################


		public function latest_venue_activities()
		{
			$stream = array();

			// follow: venues

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				v.urlname AS venue_urlname,
				v.name AS venue_name,
				ml.created
			FROM
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN venues AS v ON v.id = ml.for_object_id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'venue')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->venue_urlname] = array(
					'obj'       => array(
						'type'     => 'follow',
						'urlname'  => $obj->venue_urlname,
						'name'     => $obj->venue_name,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname,
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
				v.urlname AS venue_urlname,
				v.name AS venue_name,
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN venues AS v ON v.id = ml.for_object_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'comment_venues')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->venue_urlname] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'venues',
						'urlname'    => $obj->venue_urlname,
						'name'       => $obj->venue_name,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment,
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


		public function latest_dj_activities()
		{
			$stream = array();

			// follow: djs

			$sql = '
			SELECT
				m.id AS member_id,
				m.username,
				m.fullname,
				d.urlname AS dj_urlname,
				d.name AS dj_name,
				ml.created
			FROM
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN djs AS d ON d.id = ml.for_object_id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'dj')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->dj_urlname] = array(
					'obj'       => array(
						'type'     => 'follow',
						'urlname'  => $obj->dj_urlname,
						'name'     => $obj->dj_name,
					),
					'member'    => array(
						'id'       => $obj->member_id,
						'username' => $obj->username,
						'fullname' => $obj->fullname,
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
				d.urlname AS dj_urlname,
				d.name AS dj_name,
				mc.id AS comment_id,
				mc.comment,
				ml.created
			FROM
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN djs AS d ON d.id = ml.for_object_id
				INNER JOIN member_comments AS mc ON mc.id = ml.item_id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			ORDER BY ml.created DESC
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'comment_djs')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->dj_urlname] = array(
					'obj'       => array(
						'type'       => 'comment',
						'section'    => 'djs',
						'urlname'    => $obj->dj_urlname,
						'name'       => $obj->dj_name,
						'comment_id' => $obj->comment_id,
						'comment'    => $obj->comment,
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
				members AS m
				INNER JOIN member_logs AS ml ON ml.member_id = m.id
				INNER JOIN djs AS d ON d.id = ml.for_object_id
				INNER JOIN member_dj_ratings AS mr_dj ON mr_dj.dj_id = d.id
			WHERE
				m.city_id = ?
				AND ml.action_type = ?
			LIMIT 20
			';

			foreach($this->simple_fetch($sql, array(Session::userdata('city_id'), 'rated_dj')) as $obj)
			{
				$stream[$obj->created.$obj->member_id.$obj->member_id.$obj->dj_urlname] = array(
					'obj'       => array(
						'type'    => 'rating',
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


		public function latest_registrations_by_city()
		{
			$sql = '
			SELECT
				m.id,
				m.firstname,
				m.fullname,
				m.username,
				m.created,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = m.id AND mmr_me.member_id = ?
			WHERE
				m.city_id = ?
			ORDER BY m.created DESC
			LIMIT 20
			';

			return $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('city_id')));
		}


		// ##########################################################


		public function top_followed()
		{
			$sql = '
			SELECT
				m.id,
				m.firstname,
				m.fullname,
				m.username,
				count(mmr.member_id) AS followers,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				INNER JOIN member_member_relations AS mmr ON mmr.follow_member_id = m.id
				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = m.id AND mmr_me.member_id = ?
			WHERE
				m.city_id = ?
			GROUP BY m.id
			ORDER BY followers DESC
			LIMIT 10
			';

			return $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('city_id')));
		}


		// ##########################################################


		public function matching_members()
		{
			$matchings = array();

			// member djs

			$sql = '
			SELECT
				count(DISTINCT mdr.dj_id) AS djs_count
			FROM
				members AS m
				INNER JOIN member_dj_relations AS mdr ON mdr.member_id = m.id
			WHERE m.id = ?
			';

			$matchings['me']['djs'] = $this->simple_fetchvalue($sql, array(Session::userdata('id')));

			// member genres

			$sql = '
			SELECT
				count(DISTINCT mgr.genre_id) AS genres_count
			FROM
				members AS m
				INNER JOIN member_genre_relations AS mgr ON mgr.member_id = m.id
			WHERE m.id = ?
			';

			$matchings['me']['genres'] = $this->simple_fetchvalue($sql, array(Session::userdata('id')));

			// member events

			$sql = '
			SELECT
				count(DISTINCT mer.event_id) AS events_count
			FROM
				members AS m
				INNER JOIN member_event_relations AS mer ON mer.member_id = m.id
			WHERE m.id = ?
			';

			$matchings['me']['events'] = $this->simple_fetchvalue($sql, array(Session::userdata('id')));

			// member venues

			$sql = '
			SELECT
				count(DISTINCT mvr.venue_id) AS venues_count
			FROM
				members AS m
				INNER JOIN member_venue_relations AS mvr ON mvr.member_id = m.id
			WHERE m.id = ?
			';

			$matchings['me']['venues'] = $this->simple_fetchvalue($sql, array(Session::userdata('id')));

			// djs matching

			$sql = '
			SELECT
				members_matching.id,
				members_matching.firstname,
				members_matching.fullname,
				members_matching.username,
				count(DISTINCT mdr_matching.dj_id) AS matching_djs_count,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				INNER JOIN member_dj_relations AS mdr ON mdr.member_id = m.id

				LEFT JOIN member_dj_relations AS mdr_matching ON mdr_matching.dj_id = mdr.dj_id
				INNER JOIN members AS members_matching ON members_matching.id = mdr_matching.member_id

				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = members_matching.id AND mmr_me.member_id = ?
			WHERE
				m.id = ?
				AND members_matching.city_id = ?
				AND mdr_matching.member_id != ?
			GROUP BY members_matching.id
			';

			$matchings['members']['djs'] = $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('id'), Session::userdata('city_id'), Session::userdata('id')));

			// genres matching

			$sql = '
			SELECT
				members_matching.id,
				members_matching.firstname,
				members_matching.fullname,
				members_matching.username,
				count(DISTINCT mgr.genre_id) AS matching_genres_count,
				GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS matching_genres,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				INNER JOIN member_dj_relations AS mdr ON mdr.member_id = m.id
				INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = mdr.dj_id

				LEFT JOIN member_genre_relations AS mgr ON mgr.genre_id = dgr.genre_id
				INNER JOIN members AS members_matching ON members_matching.id = mgr.member_id
				INNER JOIN base_genres AS bg ON bg.id = mgr.genre_id

				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = members_matching.id AND mmr_me.member_id = ?
			WHERE
				m.id = ?
				AND members_matching.city_id = ?
				AND mgr.member_id != ?
			GROUP BY members_matching.id
			';

			$matchings['members']['genres'] = $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('id'), Session::userdata('city_id'), Session::userdata('id')));

			// venues matching

			$sql = '
			SELECT
				members_matching.id,
				members_matching.firstname,
				members_matching.fullname,
				members_matching.username,
				count(DISTINCT mvr_matching.venue_id) AS matching_venues_count,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				INNER JOIN member_venue_relations AS mvr ON mvr.member_id = m.id

				LEFT JOIN member_venue_relations AS mvr_matching ON mvr_matching.venue_id = mvr.venue_id
				INNER JOIN members AS members_matching ON members_matching.id = mvr_matching.member_id

				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = members_matching.id AND mmr_me.member_id = ?
			WHERE
				m.id = ?
				AND members_matching.city_id = ?
				AND mvr_matching.member_id != ?
			GROUP BY members_matching.id
			';

			$matchings['members']['venues'] = $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('id'), Session::userdata('city_id'), Session::userdata('id')));

			// events matching

			$sql = '
			SELECT
				members_matching.id,
				members_matching.firstname,
				members_matching.fullname,
				members_matching.username,
				count(DISTINCT mer_matching.event_id) AS matching_events_count,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				INNER JOIN member_event_relations AS mer ON mer.member_id = m.id

				LEFT JOIN member_event_relations AS mer_matching ON mer_matching.event_id = mer.event_id
				INNER JOIN members AS members_matching ON members_matching.id = mer_matching.member_id

				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = members_matching.id AND mmr_me.member_id = ?
			WHERE
				m.id = ?
				AND members_matching.city_id = ?
				AND mer_matching.member_id != ?
			GROUP BY members_matching.id
			';

			$matchings['members']['events'] = $this->simple_fetch($sql, array(Session::userdata('id'), Session::userdata('id'), Session::userdata('city_id'), Session::userdata('id')));

			// draw matchings

			$distributions = array(
				'djs' => 0.5,
				'genres' => 0.3,
				'events' => 0.1,
				'venues' => 0.1,
			);

			foreach($matchings['members']['djs'] as $member)
			{
				if( ! $matchings['data'][$member->id])
				{
					$matchings['data'][$member->id] = $member;
				}

				$matchings['ratings'][$member->id] += ($member->matching_djs_count * 100 / $matchings['me']['djs']) * $distributions['djs'];
			}

			foreach($matchings['members']['genres'] as $member)
			{
				if( ! $matchings['data'][$member->id])
				{
					$matchings['data'][$member->id] = $member;
				}

				$matchings['ratings'][$member->id] += ($member->matching_genres_count * 100 / $matchings['me']['genres']) * $distributions['genres'];
			}

			foreach($matchings['members']['events'] as $member)
			{
				if( ! $matchings['data'][$member->id])
				{
					$matchings['data'][$member->id] = $member;
				}

				$matchings['ratings'][$member->id] += ($member->matching_events_count * 100 / $matchings['me']['events']) * $distributions['events'];
			}

			foreach($matchings['members']['venues'] as $member)
			{
				if( ! $matchings['data'][$member->id])
				{
					$matchings['data'][$member->id] = $member;
				}

				$matchings['ratings'][$member->id] += ($member->matching_venues_count * 100 / $matchings['me']['venues']) * $distributions['venues'];
			}

			$result = array();

			if($matchings['ratings'])
			{
				// highest is best

				arsort($matchings['ratings']);

				// build finished ratings

				foreach($matchings['ratings'] as $member_id => $rating)
				{
					$result[] = (object) array(
						'id'           => $member_id,
						'rating'       => round($rating),
						'username'     => $matchings['data'][$member_id]->username,
						'fullname'     => $matchings['data'][$member_id]->fullname,
						'me_following' => $matchings['data'][$member_id]->me_following,
					);
				}

				$result = Format::array_shrink($result, 10);
			}

			return $result;
		}


		// ##########################################################


		public function match($type, $object_id)
		{
			$matchings = array();

			// member djs

			$sql = '
			SELECT
				count(DISTINCT mdr.dj_id) AS djs_count
			FROM
				members AS m
				INNER JOIN member_dj_relations AS mdr ON mdr.member_id = m.id
			WHERE m.id = ?
			';

			$matchings['me']['djs'] = $this->simple_fetchvalue($sql, array(Session::userdata('id')));

			// member genres

			$sql = '
			SELECT
				count(DISTINCT mgr.genre_id) AS genres_count
			FROM
				members AS m
				INNER JOIN member_genre_relations AS mgr ON mgr.member_id = m.id
			WHERE m.id = ?
			';

			$matchings['me']['genres'] = $this->simple_fetchvalue($sql, array(Session::userdata('id')));

			// get venue match

			if($type == 'venue')
			{
				// genres matching

				$sql = '
				SELECT
					COUNT(DISTINCT mgr.genre_id) AS count
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = der.dj_id
					INNER JOIN member_genre_relations AS mgr ON mgr.genre_id = dgr.genre_id AND mgr.member_id = ?
				WHERE
					e.venue_id = ?
				';

				$matchings['object']['genres'] = $this->simple_fetchvalue($sql, array(Session::userdata('id'), $object_id));

				// djs matching

				$sql = '
				SELECT
					COUNT(DISTINCT mdr.dj_id) AS count
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
				WHERE
					e.venue_id = ?
				';

				$matchings['object']['djs'] = $this->simple_fetchvalue($sql, array(Session::userdata('id'), $object_id));
			}

			// get event match

			if($type == 'event')
			{
				// genres matching

				$sql = '
				SELECT
					COUNT(DISTINCT mgr.genre_id) AS count
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN dj_genre_relations AS dgr ON dgr.dj_id = der.dj_id
					INNER JOIN member_genre_relations AS mgr ON mgr.genre_id = dgr.genre_id AND mgr.member_id = ?
				WHERE
					e.id = ?
				';

				$matchings['object']['genres'] = $this->simple_fetchvalue($sql, array(Session::userdata('id'), $object_id));

				// djs matching

				$sql = '
				SELECT
					COUNT(DISTINCT mdr.dj_id) AS count
				FROM
					events AS e
					INNER JOIN dj_event_relations AS der ON der.event_id = e.id
					INNER JOIN member_dj_relations AS mdr ON mdr.dj_id = der.dj_id AND mdr.member_id = ?
				WHERE
					e.id = ?
				';

				$matchings['object']['djs'] = $this->simple_fetchvalue($sql, array(Session::userdata('id'), $object_id));
			}

			// draw matchings

			$distributions = array(
				'genres' => 0.8,
				'djs' => 0.2,
			);

			$matchings['rating'] += ($matchings['object']['genres'] * 100 / $matchings['me']['genres']) * $distributions['genres'];
			$matchings['rating'] += ($matchings['object']['djs'] * 100 / $matchings['me']['djs']) * $distributions['djs'];

			return $matchings['rating'];
		}


		// ##########################################################


		public function callback_unique_username()
		{
			$sql = 'SELECT id FROM members WHERE username = ? AND id != ?';
			$result = $this->simple_fetchvalue($sql, array($_GET['value'], $_GET['id']));

			if(IS_AJAX_REQUEST)
			{
				echo empty($result) ? NULL : Lang::get('error_callback_unique_username', array($_GET['value']));
			}

			return empty($result) ? TRUE : FALSE;
		}
}
