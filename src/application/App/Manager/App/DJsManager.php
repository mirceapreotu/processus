<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/4/11
 * Time: 9:14 PM
 * To change this template use File | Settings | File Templates.
 */

    class App_Manager_App_DJsManager extends App_Manager_AbstractManager
    {
        public function listing()
        {
            // top followed

            $sql = '
			SELECT
				d.id,
				d.urlname,
				d.name,
				d.country,
				COUNT(mdr_followers.member_id) AS count,
				mdr.member_id AS me_following
			FROM
				members AS m
				INNER JOIN member_dj_relations AS mdr_followers ON mdr_followers.member_id = m.id
				INNER JOIN djs AS d ON d.id = mdr_followers.dj_id
				LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = d.id AND mdr.member_id=:userId
			WHERE
				m.city_id=:cityId
			GROUP BY d.id, mdr.member_id
			ORDER BY count DESC, d.name ASC
			LIMIT 20
			';

        // only for testing
        $sqlParams = array(
            "userId" => 708150929,
            "cityId" => 1
        );

        $config = new App_GaintS_Vo_Core_GetDataConfigVo();
        $config->setSQLStmt($sql)
                ->setFromCache(true)
                ->setMemKey($this->getMemKey(__METHOD__))
                ->setSQLParamData($sqlParams);

        $djs['top_followed'] = $this->getData($config);

        return $djs;
    }


        // ##########################################################


        public function get_profile($dj_urlname = '')
        {
            $sql = '
			SELECT
				d.id,
				d.name,
				d.urlname,
				d.url_soundcloud,
				d.url_facebook,
				d.url_myspace,
				d.country,
				mdr.member_id AS me_following
			FROM
				djs AS d
				LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = d.id AND mdr.member_id = ?
			WHERE d.urlname = ?
			';

            $config = new App_GaintS_Vo_GetDataConfigVo();
            $config->setSQLStmt($sql)
                    ->setFromCache(true)
                    ->setMemKey($this->getMemKey(__METHOD__))
                    ->setSQLParamData(array(Session::userdata('id'), $dj_urlname));

            $profile = $this->simple_fetchobject($sql, array(Session::userdata('id'), $dj_urlname));

            if ($profile->id) {
                $upcoming_recent_days = 10;

                // genres

                $sql = '
				SELECT
					GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR "^") AS genres
				FROM
					dj_genre_relations AS dgr
					INNER JOIN base_genres AS bg ON bg.id = dgr.genre_id
				WHERE
					 dgr.dj_id = ?
				';

                $profile->genres = $this->simple_fetchvalue($sql, array($profile->id));
                $profile->genres = Format::string_explode($profile->genres, '^');

                // followers

                $sql = 'SELECT count(mdr.dj_id) FROM member_dj_relations AS mdr WHERE mdr.dj_id = ?';
                $profile->followers = $this->simple_fetchvalue($sql, array($profile->id));

                // dj ratings

                $sql = '
				SELECT
					COUNT(mr_dj.dj_id) AS count,
					AVG(mr_dj.rating) AS rating
				FROM
					member_dj_ratings AS mr_dj
				WHERE
					mr_dj.dj_id = ?
				GROUP BY mr_dj.dj_id
				';

                $profile->avg_dj_rating = $this->simple_fetchobject($sql, array($profile->id));

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
					mr_dj.rating,
					mmr_me.member_id AS me_following
				FROM
					member_dj_ratings AS mr_dj
					INNER JOIN events AS e ON e.id = mr_dj.event_id
					INNER JOIN members AS m ON m.id = mr_dj.member_id
					INNER JOIN base_cities AS bc ON bc.id = m.city_id
					LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = m.id AND mmr_me.member_id = ?
				WHERE
					mr_dj.dj_id = ?
				ORDER BY mr_dj.created DESC
				LIMIT 20
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

                $profile->recent_followers = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'dj'));

                // events running

                $sql = '
				SELECT
					e.id,
					e.urlname,
					e.name,
					e.datetime_end,
					e.has_costs,
					e.cost_door,
					v.id AS venue_id,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					bc.name AS venue_city,
					mer.member_id AS me_following
				FROM
					dj_event_relations AS der
					INNER JOIN events AS e ON e.id = der.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				WHERE
					der.dj_id = ?
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
					e.has_costs,
					e.cost_door,
					v.id AS venue_id,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					bc.name AS venue_city,
					mer.member_id AS me_following
				FROM
					dj_event_relations AS der
					INNER JOIN events AS e ON e.id = der.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				WHERE
					der.dj_id = ?
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
					v.id AS venue_id,
					v.urlname AS venue_urlname,
					v.name AS venue_name,
					bc.name AS venue_city,
					AVG(mr_dj.rating) AS avg_rating,
					mer.member_id AS me_following
				FROM
					dj_event_relations AS der
					INNER JOIN events AS e ON e.id = der.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_dj_ratings AS mr_dj ON mr_dj.event_id = e.id AND mr_dj.dj_id = der.dj_id
					LEFT JOIN member_event_relations AS mer ON mer.event_id = e.id AND mer.member_id = ?
				WHERE
					der.dj_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_end, ?) < "00:00:00"
				GROUP BY e.id
				ORDER BY e.datetime_end DESC
				LIMIT 10
				';

                $profile->events['recent'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE)));

                // venues upcoming

                $sql = '
				SELECT
					v.id,
					v.urlname,
					v.name,
					bc.name AS city,
					e.id AS event_id,
					e.name AS event_name,
					e.datetime_start,
					mvr.member_id AS me_following
				FROM
					dj_event_relations AS der
					INNER JOIN events AS e ON e.id = der.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = e.venue_id AND mvr.member_id = ?
				WHERE
					der.dj_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_start, ?) > "00:00:00"
					AND DATEDIFF(e.datetime_start, ?) <= ?
				ORDER BY e.datetime_start ASC
				';

                $profile->venues['upcoming'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE), Base::get_date(TRUE), $upcoming_recent_days));

                // venues recent

                $sql = '
				SELECT
					v.id,
					v.urlname,
					v.name,
					bc.name AS city,
					e.id AS event_id,
					e.name AS event_name,
					e.datetime_start,
					mr_dj.rating,
					mvr.member_id AS me_following
				FROM
					dj_event_relations AS der
					INNER JOIN events AS e ON e.id = der.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_dj_ratings AS mr_dj ON mr_dj.event_id = e.id AND mr_dj.dj_id = der.dj_id
					LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = e.venue_id AND mvr.member_id = ?
				WHERE
					der.dj_id = ?
					AND e.status = ?
					AND TIMEDIFF(e.datetime_end, ?) < "00:00:00"
				GROUP BY v.id
				ORDER BY e.datetime_start DESC
				LIMIT 10
				';

                $profile->venues['recent'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete', Base::get_date(TRUE)));

                // venues top booked

                $sql = '
				SELECT
					COUNT(v.id) AS gigs,
					v.id,
					v.urlname,
					v.name,
					bc.name AS city,
					mvr.member_id AS me_following
				FROM
					dj_event_relations AS der
					INNER JOIN events AS e ON e.id = der.event_id
					INNER JOIN venues AS v ON v.id = e.venue_id
					INNER JOIN base_cities AS bc ON bc.id = v.city_id
					LEFT JOIN member_venue_relations AS mvr ON mvr.venue_id = e.venue_id AND mvr.member_id = ?
				WHERE
					der.dj_id = ?
					AND e.status = ?
				GROUP BY v.id
				ORDER BY gigs DESC, v.name ASC
				LIMIT 10
				';

                $profile->venues['top'] = $this->simple_fetch($sql, array(Session::userdata('id'), $profile->id, 'complete'));
            }

            return $profile;
        }
    }
