<?php
/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Pages_Events
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook_Pages
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Pages_Events
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Pages
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */
class App_JsonRpc_V1_Fb_Service_Facebook_Pages_Events
    extends App_JsonRpc_V1_Fb_Service
{





    /**
     * @param  $pageId
     * @param array|null $additionalGraph
     * @param array|null $params
     * @return object
     */
    public function getList($pageId, $additionalGraph=null, $params = null)
    {
        //"create_event",
        //"manage_pages",

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $permissionManager = $application->getManagerFacebookPermissions();

        /*
        $permissionManager->requireFacebookMePermissionsByList(
            array("user_events"),
            __METHOD__
        );
        */

        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();

        $apiService = $facebook->getServicePagesEvents();
        $apiClientResponse = $apiService->getList(
            $apiClient,
            $apiCachePolicy,

            $pageId,

            $additionalGraph,
            $params
        );




        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);

        $result = $apiClientResponse->getResult();
        $result["_cache"] = $apiClientResponse->getCacheData();

        return (object)$result;


    }


    public function create($pageId, $eventData)
    {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $permissionManager = $application->getManagerFacebookPermissions();


        $permissionManager->requireFacebookMePermissionsByList(
            array(
             "create_event",
            "manage_pages",
            ),
            __METHOD__
        );


        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();


        $apiService = $facebook->getServicePagesEvents();

// debug
if ($eventData===null) {
    $eventData = $apiService->newPageEventExampleByPageId($pageId);
}


        $apiClientResponse = $apiService->createEvent(
            $apiClient,

            $pageId,
            $eventData
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);


        // invalidate page

        // @TODO: for current user - invalidate now,
        // for other users invalidate async (beanstalk)

        $invalidator = $apiClient->newCacheInvalidator();
        $invalidator->setUserId("*"); // for all users
        $invalidator->setGraphId($pageId); // the current page
        $invalidator->setAdditionalGraph("*"); // all connections
        $invalidator->setGraphParams("*"); // all params

        $apiClient->apiGetInvalidate($apiClientResponse, $invalidator);



        return (object)$apiClientResponse->getResult();
    }








   


}