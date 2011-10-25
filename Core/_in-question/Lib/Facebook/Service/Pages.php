<?php
/**
 * Lib_Facebook_Service_Pages
 *
 * @package Lib_Facebook_Service
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Service_Events
 *
 *
 * @package Lib_Facebook_Service
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Service_Pages
    extends Lib_Facebook_Service_Abstract
{



    const PERMISSION_MANAGE_PAGES = "manage_pages";

    // notice: ein page admin muss die page liken,
    //um zu confirmen dass er admin sein will


/*
$fanpageId = $application->getFacebook()->getConfig()->getFanPageId();

//$r=$application->getFacebook()->api("/".$fanpageId."?metadata=1&fields=access_token");
$r=$application->getFacebook()->api("/".$fanpageId."/admins/".$application->getFacebookMeId(),"GET", array(
                                                                                                 "access_token" => "AAABvN6rndNkBAF4A8QqsaJ5NB8JhZAkWbYtJUZAVef9it8Kq3yK6zS0KfA7NIWxAs6ONzwuLx0tSCCBWaJrlsGS8EOvSN2usKtE7FjGeY2d7FZBzbErX75NAZCtF9aAZD",
                                                                                                     ));
return $r;
  */

    /**
     * requires permission manage_pages
     * @param Lib_Facebook_Client_Client $client
     * @param  Lib_Facebook_Client_CachePolicy $cachePolicy
     * @param  string|int $pageId
     * @return Lib_Facebook_Client_Response
     */
    public function getPageAccessTokenByPageId(
        Lib_Facebook_Client_Client $client,
        Lib_Facebook_Client_CachePolicy $cachePolicy,

        $pageId
    ) {


        $id = $pageId;
        //".$pageId."?metadata=1&fields=access_token";
        $additionalGraph = null;
        $params = array(
            "metadata" => true,
            "fields" => array(
                "access_token"
            ),
        );
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


    public function getPageAdminsByPageIdAndAccessToken(
                Lib_Facebook_Client_Client $client,
                Lib_Facebook_Client_CachePolicy $cachePolicy,

                $pageId,
                $pageAccessToken

    )
    {
        $id = $pageId;
        $additionalGraph = "/admins";
        $params = array(
            "access_token" => $pageAccessToken
        );
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






}
