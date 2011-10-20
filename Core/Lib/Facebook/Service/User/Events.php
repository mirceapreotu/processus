<?php
/**
 * Lib_Facebook_Service_User_Events
 *
 * @package Lib_Facebook_Service_User
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Service_User_Events
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
class Lib_Facebook_Service_User_Events
    extends Lib_Facebook_Service_Abstract
{

     /**
     * Example: params={"metadata":true, "fields":["name","last_name"]}
     * @param Lib_Facebook_Client_Client $client
     * @param  Lib_Facebook_Client_CachePolicy $cachePolicy
     * @return Lib_Facebook_Client_Response
     */
    public function getMeList(
        Lib_Facebook_Client_Client $client,
        Lib_Facebook_Client_CachePolicy $cachePolicy,


        $additionalGraph,
        $params
    ) {



        if (Lib_Utils_String::isEmpty($additionalGraph)) {
            $additionalGraph ="";
        }


        $id = "me";
        $additionalGraph = "/events".$additionalGraph;
        //$params = array();
        $allowMe = true;

        $clientResponse = $client->apiGetById(
            $cachePolicy,

            $id,
            $additionalGraph,
            $params,

            $allowMe

        );


        return $clientResponse;
    }



    /**
     * @throws Exception
     * @param Lib_Facebook_Client_Client $client
     * @param  $eventData
     * @return Lib_Facebook_Client_Response
     */
    public function createMeEvent(
        Lib_Facebook_Client_Client $client,

        $eventData
    )
    {
        if (is_array($eventData)!==true) {
            throw new Exception("Invalid parameter 'eventData' at ".__METHOD__);
        }


        $allowMe = true;

        $id = "me";
        $additionalGraph = "/events";

        $params = $eventData;


       $clientResponse =$client->apiPostById(
            $id,
           $additionalGraph,
           $params,
           $allowMe
       );


        return $clientResponse;
    }





       /**
     * @return array
     */
    public function newMeEventExample()
    {
        $data =

                array(
                   //'access_token' => $access_token,
                   'name' => 'Event Example',
                   'description' => 'Event Example Description',
                   'location' => 'Event Example Location',
                   'street' => 'Event Example Street',
                   'city' => 'Berlin',
                   'privacy_type' => 'OPEN', # OPEN, CLOSED, SECRET
                  'start_time' => date('Y-m-d H:i:s', time()), # timezone info is stripped
                  'end_time' => date('Y-m-d H:i:s', time() * 60),
                   );


        return $data;
    }

}
