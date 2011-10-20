<?php
/**
 * Lib_Facebook_Service_Pages_Events
 *
 * @package Lib_Facebook_Service_Pages
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Service_Pages_Events
 *
 *
 * @package Lib_Facebook_Service_Pages
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Service_Pages_Events
    extends Lib_Facebook_Service_Abstract
{

     /**
     * Example: params={"metadata":true, "fields":["name","last_name"]}
     * @param Lib_Facebook_Client_Client $client
     * @param  Lib_Facebook_Client_CachePolicy $cachePolicy
     * @return Lib_Facebook_Client_Response
     */
    public function getList(
        Lib_Facebook_Client_Client $client,
        Lib_Facebook_Client_CachePolicy $cachePolicy,

        $pageId,
        $additionalGraph,
        $params
    ) {



        if (Lib_Utils_String::isEmpty($additionalGraph)) {
            $additionalGraph ="";
        }


        $id = $pageId;
        $additionalGraph = "/events".$additionalGraph;
        //$params = array();
        $allowMe = false;

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
     * @param  $pageId
     * @param  $eventData
     * @return Lib_Facebook_Client_Response
     */
    public function createEvent(
        Lib_Facebook_Client_Client $client,

        $pageId,
        $eventData
    )
    {
        if (is_array($eventData)!==true) {
            throw new Exception("Invalid parameter 'eventData' at ".__METHOD__);
        }

        $eventData["pageId"] = $pageId;

        $allowMe = false;

        $id = $pageId;
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
    public function newPageEventExampleByPageId($pageId)
    {
        $data =

                array(
                   //'access_token' => $access_token,
                    "pageId" => $pageId,
                   'name' => 'Event Example',
                   'description' => 'Event Example Description',
                   'location' => 'Event Example Location',
                   'street' => 'Event Example Street',
                   'city' => 'Berlin',
                   'privacy_type' => 'OPEN', # OPEN, CLOSED, SECRET
                  'start_time' => date('Y-m-d H:i:s', time()), # timezone info is stripped
                  'end_time' => date('Y-m-d H:i:s', time() * 60),
                   );


        $data["name"] .= " ".md5(time());

        return $data;
    }

}
