<?php
/**
 * Lib_Facebook_Service_User_Permissions
 *
 * @package Lib_Facebook_Service_User
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Service_User_Permissions
 *
 *
 * @package Lib_Facebook_Service_User
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Service_User_Permissions
    extends Lib_Facebook_Service_Abstract
{







    /**
     * Example: params={"metadata":true, "fields":["name","last_name"]}
     * @param Lib_Facebook_Client_Client $client
     * @param  Lib_Facebook_Client_CachePolicy $cachePolicy
     * @return Lib_Facebook_Client_Response
     */
    public function getMePermissions(
        Lib_Facebook_Client_Client $client,
        Lib_Facebook_Client_CachePolicy $cachePolicy

    ) {



        $id = "me";
        $additionalGraph = "/permissions";
        $params = array();
        $allowMe = true;

        
        $graph = new Lib_Facebook_Service_Graph();
        $clientResponse = $graph->getById(
            $client,
            $cachePolicy,
            $id,
            $additionalGraph,
            $params
        );

        return $clientResponse;

        /* returns

                {
           "data": [
              {
                 "installed": 1,
                 "read_stream": 1,
                 "user_birthday": 1,
                 "user_religion_politics": 1,
                 "user_relationships": 1,
                 "user_relationship_details": 1,
                 "user_hometown": 1,
                 "user_location": 1,
                 "user_likes": 1,
                 "user_activities": 1,
                 "user_interests": 1,
                 "user_education_history": 1,
                 "user_work_history": 1,
                 "user_online_presence": 1,
                 "user_website": 1,
                 "user_groups": 1,
                 "user_events": 1,
                 "user_photos": 1,
                 "user_videos": 1,
                 "user_photo_video_tags": 1,
                 "user_notes": 1,
                 "user_about_me": 1,
                 "user_status": 1,
                 "friends_birthday": 1,
                 "friends_religion_politics": 1,
                 "friends_relationships": 1,
                 "friends_relationship_details": 1,
                 "friends_hometown": 1,
                 "friends_location": 1,
                 "friends_likes": 1,
                 "friends_activities": 1,
                 "friends_interests": 1,
                 "friends_education_history": 1,
                 "friends_work_history": 1,
                 "friends_online_presence": 1,
                 "friends_website": 1,
                 "friends_groups": 1,
                 "friends_events": 1,
                 "friends_photos": 1,
                 "friends_videos": 1,
                 "friends_photo_video_tags": 1,
                 "friends_notes": 1,
                 "friends_about_me": 1,
                 "friends_status": 1
              }
           ]
        }

        */

        return $clientResponse;
    }




}
