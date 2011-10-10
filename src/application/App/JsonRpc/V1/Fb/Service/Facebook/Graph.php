<?php
/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Graph
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Graph
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */
class App_JsonRpc_V1_Fb_Service_Facebook_Graph
    extends App_JsonRpc_V1_Fb_Service
{



    public function get($additionalGraph=null, $params = null)
    {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();

        $id = $facebook->getConfig()->getAppId();

        $apiService = $facebook->getServiceGraph();
        $apiClientResponse = $apiService->getById(
            $apiClient,
            $apiCachePolicy,

            $id,

            $additionalGraph,
            $params
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);

        $result = $apiClientResponse->getResult();
        $result["_cache"] = $apiClientResponse->getCacheData();


        return (object)$result;

    }

    public function describe(
        $additionalGraph=null, $params = null, $fields = null
    )
    {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();
        $apiCachePolicy->setLoad(true);
        $apiCachePolicy->setSave(true);


        $id = $facebook->getConfig()->getAppId();

        $apiService = $facebook->getServiceGraph();
        $apiClientResponse = $apiService->describeById(
            $apiClient,
            $apiCachePolicy,

            $id,

            $additionalGraph,
            $params,
            $fields
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);

        $result = $apiClientResponse->getResult();
        $result["_cache"] = $apiClientResponse->getCacheData();


        return (object)$result;

    }


    public function getAndDescribe(
        $additionalGraph=null, $params = null, $fields = null
    )
    {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();
        $apiCachePolicy->setLoad(true);
        $apiCachePolicy->setSave(true);


        $id = $facebook->getConfig()->getAppId();

        $apiService = $facebook->getServiceApplication();
        $apiClientResponse = $apiService->getById(
            $apiClient,
            $apiCachePolicy,

            $id,

            $additionalGraph,
            $params
        );
        $apiClientResponse->delegateError(__METHOD__);

        $resultGetById = $apiClientResponse->getResult();

        $apiClientResponse = $apiService->describeById(
            $apiClient,
            $apiCachePolicy,

            $id,

            $additionalGraph,
            $params,

            $fields
        );
        $apiClientResponse->delegateError(__METHOD__);

        $resultDescribeById = $apiClientResponse->getResult();



        $result = array();
        if (is_array($resultGetById)) {
            $result += $resultGetById;
        }
        if (is_array($resultDescribeById)) {
            $result += $resultDescribeById;
        }



        $profiler->stop();

        return (object)$result;

    }










    /**
     * @param  int|string $id
     * @param  string|null $additionalGraph
     * @param  array|null $params
     * @return object
     */
    /*
    public function getById(
        $id,
        $additionalGraph=null,
        $params=null
    )
    {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $permissionManager = $application->getManagerFacebookPermissions();



        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();
        $apiCachePolicy->setLoad(true);
        $apiCachePolicy->setSave(true);


        $apiService = $facebook->getServiceGraph();
        $apiClientResponse = $apiService->getById(
            $apiClient,
            $apiCachePolicy,
            $id,
            $additionalGraph,
            $params
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);


        return (object)$apiClientResponse->getResult();


    }

    */
    /**
     * @param  int|string $id
     * @param  string|null $additionalGraph
     * @param  array|null $params
     * @param  array|null $fields
     * @return object
     */
    /*
    public function describeById(
        $id,
        $additionalGraph=null,
        $params=null,
        $fields=null
    )
    {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $permissionManager = $application->getManagerFacebookPermissions();


        $facebook = $application->getFacebook();

        $apiClient = $facebook->getApiClient();
        $apiCachePolicy = $apiClient->newCachePolicy();
        $apiCachePolicy->setLoad(true);
        $apiCachePolicy->setSave(true);


        $apiService = $facebook->getServiceGraph();
        $apiClientResponse = $apiService->describeById(
            $apiClient,
            $apiCachePolicy,
            $id,
            $additionalGraph,
            $params,
            $fields
        );


        $profiler->stop();
        $apiClientResponse->delegateError(__METHOD__);


        return (object)$apiClientResponse->getResult();


    }

   */


}