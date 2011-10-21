<?php
/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Events
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook_Viewer
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Events
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook_Viewer
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */
class App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Events
    extends App_JsonRpc_V1_Fb_Service
{


    public function getList($additionalGraph=null, $params=null)
    {
        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $permissionManager = $application->getManagerFacebookPermissions();


        $permissionManager->requireFacebookMePermissionsByList(
            array("user_events"),
            __METHOD__
        );


        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();
        $apiCachePolicy->setLoad(true);
        $apiCachePolicy->setSave(true);
        

        $apiService = $facebook->getServiceUserEvents();
        $apiClientResponse = $apiService->getMeList(
            $apiClient,
            $apiCachePolicy,
            $additionalGraph,
            $params
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);


        $result = (array)$apiClientResponse;
        $result["_cache"] = $apiClientResponse->getCacheData();


        return (object)$result;
    }



    /**
     * @return object
     */
    public function create($eventData) {


        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

/*
        if (is_array($eventData)!==true) {
            throw new Exception(
                "Invalid parameter 'eventData' at ".__METHOD___
            );
        }
*/


        $permissionManager = $application->getManagerFacebookPermissions();

        $permissionManager->requireFacebookMePermissionsByList(
            array("create_event"),
            __METHOD__
        );

        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();


        $apiService = $facebook->getServiceUserEvents();
if ($eventData === null) {
    $eventData = $apiService->newMeEventExample();

}
        $apiClientResponse = $apiService->createMeEvent(
            $apiClient,
            $eventData
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);


        // invalidate page

        // @TODO: for current user - invalidate now,
        // for other users invalidate async (beanstalk)

        $invalidator = $apiClient->newCacheInvalidator();
        $invalidator->setUserId("me"); // for all users
        $invalidator->setGraphId("me"); // the current page
        $invalidator->setAdditionalGraph("events"); // all connections
        $invalidator->setGraphParams("*"); // all params

        $apiClient->apiGetInvalidate($apiClientResponse, $invalidator);


        return (object)$apiClientResponse->getResult();


   }















}