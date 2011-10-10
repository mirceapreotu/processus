<?php
/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Pages_Admins
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
 * App_JsonRpc_V1_Fb_Service_Facebook_Pages_Admins
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
class App_JsonRpc_V1_Fb_Service_Facebook_Pages_Admins
    extends App_JsonRpc_V1_Fb_Service
{


    public function getList($pageId)
    {
        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $permissionManager = $application->getManagerFacebookPermissions();

        $permissionManager->requireFacebookMePermissionsByList(
            array("manage_pages"),
            __METHOD__
        );
        

        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();

        $apiService = $facebook->getServicePages();
        $apiClientResponse = $apiService->getPageAccessTokenByPageId(
            $apiClient,
            $apiCachePolicy,
            $pageId
        );
        $apiClientResponse->delegateError(__METHOD__);


        $graphType = $apiClientResponse->getResultProperty("type");
        if ($graphType !== "page") {
            throw new Exception("That is not a page! ");
        }
        $pageAccessToken = $apiClientResponse->getResultProperty(
            "access_token"
        );

        $apiClientResponse = $apiService->getPageAdminsByPageIdAndAccessToken(
            $apiClient,
            $apiCachePolicy,
            $pageId,
            $pageAccessToken
        );
        $apiClientResponse->delegateError(__METHOD__);


        $result = (array)$apiClientResponse->getResult();
        $result["_cache"] = $apiClientResponse->getCacheData();

        $profiler->stop();

        return (object)$result;
    }












}