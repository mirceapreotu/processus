<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 9/4/11
 * Time: 9:10 PM
 * To change this template use File | Settings | File Templates.
 */

class App_Manager_App_BaseManager extends App_Manager_AbstractManager
{
    /**
     * @return array|null
     */
    public function get_cities()
    {
        $sql = 'SELECT id, name FROM base_cities WHERE live =:isLive ORDER BY name ASC';
        $config = new App_GaintS_Vo_Core_GetDataConfigVo();
        $config->setSQLStmt($sql)
                ->setFromCache(true)
                ->setMemKey($this->getMemKey(__METHOD__))
                ->setSQLParamData(array("isLive" => 1));
        return $this->getData($config);
    }


    // ##########################################################


    public function search($query = NULL)
    {
        $matchings = array();
        $query = Format::string_fulltext_query($query);

        // members

        $sql = '
			SELECT
				m.id,
				m.fullname,
				m.username,
				bc.name AS city,
				mmr_me.member_id AS me_following
			FROM
				members AS m
				LEFT JOIN base_cities AS bc ON bc.id = m.city_id
				LEFT JOIN member_member_relations AS mmr_me ON mmr_me.follow_member_id = m.id AND mmr_me.member_id = ?
			WHERE
				MATCH (m.fullname, m.username) AGAINST (? IN BOOLEAN MODE)
			ORDER BY m.fullname ASC
			';

        $matchings['members'] = $this->simple_fetch($sql, array(Session::userdata('id'), $query));

        // djs

        $sql = '
			SELECT
				d.id,
				d.name,
				d.urlname,
				GROUP_CONCAT(DISTINCT bg.name ORDER BY bg.name ASC SEPARATOR ", ") AS genres,
				mdr_me.member_id AS me_following
			FROM
				djs AS d
				LEFT JOIN dj_genre_relations AS dgr ON dgr.dj_id = d.id
				LEFT JOIN base_genres AS bg ON bg.id = dgr.genre_id
				LEFT JOIN member_dj_relations AS mdr_me ON mdr_me.dj_id = d.id AND mdr_me.member_id = ?
			WHERE
				MATCH (d.name) AGAINST (? IN BOOLEAN MODE)
			GROUP BY d.id
			ORDER BY d.name ASC
			';

        $matchings['djs'] = $this->simple_fetch($sql, array(Session::userdata('id'), $query));

        // venues

        $sql = '
			SELECT
				v.id,
				v.name,
				v.urlname,
				bc.name AS city,
				mvr_me.member_id AS me_following
			FROM
				venues AS v
				LEFT JOIN base_cities AS bc ON bc.id = v.city_id
				LEFT JOIN member_venue_relations AS mvr_me ON mvr_me.venue_id = v.id AND mvr_me.member_id = ?
			WHERE
				MATCH (v.name) AGAINST (? IN BOOLEAN MODE)
			ORDER BY v.name ASC
			';

        $matchings['venues'] = $this->simple_fetch($sql, array(Session::userdata('id'), $query));

        // events

        $sql = '
			SELECT
				e.id,
				e.name,
				e.urlname,
				e.datetime_start,
				v.urlname AS venue_urlname,
				v.name AS venue_name,
				bc.name AS venue_city,
				mer_me.member_id AS me_following
			FROM
				events AS e
				INNER JOIN venues AS v ON v.id = e.venue_id
				INNER JOIN base_cities AS bc ON bc.id = e.city_id
				LEFT JOIN member_event_relations AS mer_me ON mer_me.event_id = e.id AND mer_me.member_id = ?
			WHERE
				e.status = ?
				AND MATCH (e.name) AGAINST (? IN BOOLEAN MODE)
			ORDER BY e.datetime_start DESC, e.name ASC
			';

        $matchings['events'] = $this->simple_fetch($sql, array(Session::userdata('id'), 'complete', $query));

        return $matchings;
    }
}
