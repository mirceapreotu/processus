<?php
/**
 * Lib_Facebook_Service_Graph
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
 * @package Lib_Facebook_Service
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Service_Graph
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

        $allowMe = true;

        if ($fields!==null) {
            if (is_array($fields)!==true) {
                throw new Exception(
                    "Invalid parameter 'fields' at ".__METHOD__
                );
            }
        }

        if (is_array($params)!==true) {
            $params = array();
        }
        $params["metadata"] = true;

        if (is_array($fields)) {
            $_fields = Lib_Utils_Array::getProperty($params, "fields");
            if (is_array($_fields)!==true) {
                $_fields = array();
            }
            foreach($_fields as $field) {
                if (in_array($field, $fields, true)) {
                    continue;
                }
                $fields[] = $field;
            }
            $params["fields"] = $fields;
        }

//var_dump($params);

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
