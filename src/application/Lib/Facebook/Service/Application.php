<?php
/**
 * Lib_Facebook_Service_Application
 *
 * @package Lib_Facebook_Service
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Service_Graph
 *
 *
 * @package Lib_Facebook_Application
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Service_Application
    extends Lib_Facebook_Service_Abstract
{



    /**
     * Example: params={"metadata":true, "fields":["name","last_name"]}
     * @param Lib_Facebook_Client_Client $client
     * @param  string $id
     * @param  string|null $additionalGraph
     * @param  array|null $params
     * @param  bool $allowMe
     * @param  Lib_Facebook_Client_CachePolicy $cachePolicy
     * @return Lib_Facebook_Client_Response
     */
    public function getById(
        Lib_Facebook_Client_Client $client,
        Lib_Facebook_Client_CachePolicy $cachePolicy,
        $id,
        $additionalGraph,
        $params


    ) {

        $allowMe = false;

        $graph = new Lib_Facebook_Service_Graph();
        $clientResponse = $graph->getById(
            $client,
            $cachePolicy,
            $id,
            $additionalGraph,
            $params
        );
        
        return $clientResponse;
    }



    /**
     * Example: params={"metadata":true, "fields":["name","last_name"]}
     * @param Lib_Facebook_Client_Client $client
     * @param  string $id
     * @param  string|null $additionalGraph
     * @param  array|null $params
     * @param array|null $fields
     * @param  bool $allowMe
     * @param  Lib_Facebook_Client_CachePolicy $cachePolicy
     * @return Lib_Facebook_Client_Response
     */
    public function describeById(
        Lib_Facebook_Client_Client $client,
        Lib_Facebook_Client_CachePolicy $cachePolicy,

        $id,
        $additionalGraph,
        $params,
        $fields



    ) {

        $allowMe = false;

        $graph = new Lib_Facebook_Service_Graph();
        $clientResponse = $graph->describeById(
            $client,
            $cachePolicy,
            $id,
            $additionalGraph,
            $params,
            $fields
        );

        return $clientResponse;
    }



}
