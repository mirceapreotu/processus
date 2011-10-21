<?php
/**
 * App_JsonRpc_V1_Fb_Service_Canvas
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Service_Canvas
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */
class App_JsonRpc_V1_Fb_Service_Canvas
    extends App_JsonRpc_V1_Fb_Service
{


    public function foo()
    {
        $application = $this->getContext()->getApplication();

        $dbClient = $application->getDbClient();

        $sql ="
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
LEFT JOIN member_dj_relations AS mdr ON mdr.dj_id = d.id AND mdr.member_id = 708150929
WHERE
m.city_id = 1
GROUP BY d.id, mdr.member_id
ORDER BY count DESC, d.name ASC
LIMIT 20
        ";

        $params = array();
        $rows =$dbClient->getRows($sql, $params, false);
        return $rows;


    }



/*
    public function foo($id, $additionalGraph=null, $params=null)
    {
        $application = $this->getContext()->getApplication();
        $facebook = $application->getFacebook();
        $srv = $application->getFacebook()->getServiceGraph();



        $apiClient = $facebook->getApiClient();
        $cachePolicy = $facebook->getApiClientCachePolicy();
        $cachePolicy->setLoad(true);
        $cachePolicy->setSave(true);

        $r=$srv->getById(
            $apiClient,
            $cachePolicy,
            
            $id, $additionalGraph, $params
        );
        $r->delegateError(__METHOD__);

        return array(
            "result"=>$r->getResult(),
            "cache" => $r->getCacheData()
            );
    }
*/

   /**
     * @return object
     */
    public function getInitialData()
    {



        


        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $viewerBO = $application->getViewerBO();


        $result= array(

            "viewer" => null,

        );



        // register fb user?
        $forceFbAuth = false;
        $updatePersonRecordFromFbMe = true;
        $application->tryAutoRegisterFbUser(
            $forceFbAuth, $updatePersonRecordFromFbMe
        );



        $result["viewer"] = $application->getViewerBO()->newPersonDtoIfExists();


        // check profile

        $viewerBO = $application->getViewerBO();
        $result["viewer"] = $viewerBO->newPersonDtoIfExists();


       

        // friends
        if ($viewerBO->exists()) {
        }

    
        $profiler->stop();
        return (object)$result;
    }
}