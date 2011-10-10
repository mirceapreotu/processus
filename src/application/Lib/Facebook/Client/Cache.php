<?php
/**
 * Lib_Facebook_Client_Cache
 *
 * @package		Lib_Facebook_Client
 * @category	basilicom
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_Facebook_Client_Cache
 *
 * @package Lib_Facebook_Client
 *
 * @category	basilicom
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Client_Cache extends Lib_Redis_Redisek_Server
{
    /**
    * override in subclass or inject using Zend_Config
    * @var array
    */
    protected $_configDefault = array(

        "connection" => array(
                   "host" => "localhost",
                   "port" => "6379",
                   "isPersistent" => false,
            ),
        "model" => array(
            "keyPrefix" => array(
                "app" => null,//"com.example.HelloWorld",
                "bucket" => "Api.Fb", // default bucket
                "version" => 1,
                "class" => null, //autodetect
                "dsn"=> "{app}:{class}:v{version}:{bucket}:{key}",
            ),
        ),
         "serializer" => array(
             "enabled" => true,
             "bucket" => array(
                 // enable auto-serialize value for buckets
                 "flags" => null,
                 "whitelist" => array(
                    "Api.Fb",
                    //"Fb.Api.Request",
                    // "Api.Fb.*",
                 ),
                 "blacklist" => array(

                 ),
             )
        ),


    );







    /**
     * @var Lib_Facebook_Client_Client
     */
    protected $_client;

    /**
     * @param Lib_Facebook_Client_Client $client
     * @return void
     */
    public function setClient(Lib_Facebook_Client_Client $client) {
        $this->_client = $client;
    }


    /**
     * @return Lib_Facebook_Client_Client
     */
    public function getClient()
    {
        return $this->_client;
    }


    /**
     * @param Lib_Facebook_Client_Client $client
     * @return void
     */
    public function init(Lib_Facebook_Client_Client $client) {
        $this->setClient($client);

/*
        $namespaceName = get_class($this)."_".get_class($client);
        $namespaceName .= "_v".$this->getModelVersion();

        $this->setNamespaceName($namespaceName);
*/
    }




    /**
     * @return Lib_Facebook_Client_CachePolicy
     */
    public function newCachePolicy()
    {
        return new Lib_Facebook_Client_CachePolicy();
    }


    /**
     * @return Lib_Facebook_Client_CacheInvalidator
     */
    public function newCacheInvalidator()
    {
        return new Lib_Facebook_Client_CacheInvalidator();
    }



    /**
     * @return string
     */
    public function getClientIdSignature()
    {
        $client = $this->getClient();
        $clientId = $client->getClientId();

        $signature = get_class($this).get_class($client).json_encode($clientId);
        $signature = md5($signature).sha1($signature);
        return $signature;
    }



    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++

    protected $_key = array(
        "dsn" => "FBUSERID_{fbUserId}_FBGRAPHID_{fbGraphId}_FBGRAPHADDGRAPH_{fbAdditionalGraph}_FBGRAPHPARAMS_{fbGraphParams}",
        "data" => array(
            "fbUserId" => "100000000045678",
            "fbGraphId" => "100000000000023",
            "fbAdditionalGraph" => "/events/attending",
            "fbGraphParams" => array(
                /*
                "metadata" => true,
                "fields" => array(
                    "field1", "field2"
                )
                */
            ),
        ),
        "salt" => "foobar",
    );


    public function getKeyDefinition()
    {
        return $this->_key;
    }

    public function getKeyDefinitionDsn()
    {
        $keyDefinition = $this->getKeyDefinition();
        return Lib_Utils_Array::getProperty($keyDefinition, "dsn");
    }

    /*
    public function getKeyDefinitionData()
    {
        $keyDefinition = $this->getKeyDefinition();
        return Lib_Utils_Array::getProperty($keyDefinition, "data");
    }
    */
    public function getKeyDefinitionSalt()
    {
        $keyDefinition = $this->getKeyDefinition();
        return Lib_Utils_Array::getProperty($keyDefinition, "salt");
    }



    public function parseKeyDefinition($keyDefinition, $forFilter)
    {

        //$keyDefintion = $this->_key;
        $parser = new Lib_Template_StringParser();
        $parser->setTemplate($keyDefinition["dsn"]);

        $salt = $keyDefinition["salt"];

        $keyData = (array)$keyDefinition["data"];
        foreach($keyData as $keyName => $keyValue) {
//var_dump($keyValue);
            if ($forFilter === true) {
                if ($keyValue==="*") {
                    continue;
                }
            }
            switch($keyName) {

                case "fbAdditionalGraph": {
                    if (Lib_Utils_String::isEmpty($keyValue)) {
                        $keyValue = (string)trim($keyValue);
                    }
                    if (Lib_Utils_String::startsWith($keyValue, "/")) {
                        $keyValue = Lib_Utils_String::removePrefix(
                            $keyValue, "/"
                        );
                    }
                    if (Lib_Utils_String::endsWith($keyValue, "/")) {
                        $keyValue = Lib_Utils_String::removePostfix(
                            $keyValue, "/"
                        );
                    }

                    $keyValue = (string)trim($keyValue);
                    if (strpos($keyValue, "//")!==false) {
                        throw new Exception(
                            "Invalid double dash at ".__METHOD__
                        );
                    }

                    if (Lib_Utils_String::isEmpty($keyValue)) {
                        $keyValue = "null";
                    }

                    $value = (string)md5($keyValue).sha1($keyValue);
                    $keyData[$keyName] = $value;


                    break;
                }
                case "fbGraphParams": {
                    $value = md5($salt.json_encode($keyValue));
                    $keyData[$keyName] = $value;
                    break;
                }

                default: {
                    break;
                }
            }
        }

        $parsed = $parser->parse($keyData);
        return $parsed;

    }


    /*
    public function getKeyParsed()
    {
        $keyDefinition = $this->_key;
        $parsed = $this->parseKeyDefinition($keyDefinition, false);
        return $parsed;
    }

    */



    /**
     * @param Lib_Facebook_Client_Response $clientResponse
     * @param Lib_Facebook_Client_CachePolicy $cachePolicy
     * @param  $graphId
     * @param  $additionalGraph
     * @param  $apiEndpoint
     * @param  $apiEndpointParams
     * @return string
     */
    public function newKey(
                Lib_Facebook_Client_Response $clientResponse,
                Lib_Facebook_Client_CachePolicy $cachePolicy,

                $graphId,
                $additionalGraph,

                $apiEndpoint,
                $apiEndpointParams,

                $forFilter = false

            ) {
        $userId = 0;
        try {
            $userId = $this->getClient()->getFacebook()->getUserId();
        } catch (Exception $e) {

        }

        $keyDefinition = array(
            "salt" =>$this->getKeyDefinitionSalt(),
            "dsn" => $this->getKeyDefinitionDsn(),
            "data" => array(
                "fbUserId" => $userId,//"100000000045678",
                "fbGraphId" => $graphId,//"100000000000023",
                "fbAdditionalGraph" => $additionalGraph, //"/events/attending",
                "fbGraphParams" => array(
                    // just for checksum hashing
                    $additionalGraph,
                    $apiEndpoint,
                    $apiEndpointParams,
                    $this->getClient()->getAccessTokenApplication(),
                    $this->getClient()->getAccessTokenUser(),
                    $this->getClientIdSignature(),
                ),
            ),
        );

        $key = $this->parseKeyDefinition($keyDefinition, $forFilter);

        return $key;
    }







    public function saveClientResponse(

                Lib_Facebook_Client_Response $clientResponse,
                Lib_Facebook_Client_CachePolicy $cachePolicy,

                $graphId,
                $additionalGraph,

                $apiEndpoint,
                $apiEndpointParams

    )
    {

        $ttl = $cachePolicy->getTtl(); //sec
        if (!is_int($ttl)) {
            $ttl = $cachePolicy->getTtlDefault();
        }
        if (((is_int($ttl)) && ($ttl>=0)) !== true) {
            throw new Exception("Invalid cachePolicy.ttl at ".__METHOD__);
        }



        $result = false;


        $redisekServer = $this->getServer();

        $key = $this->newKey(
            $clientResponse,
            $cachePolicy,

            $graphId,
            $additionalGraph,

            $apiEndpoint,
            $apiEndpointParams
        );


        $isValid = (
                        $clientResponse->hasResult()
                        && ($clientResponse->hasError()!==true)
        );


        if (!$isValid) {
            if ($cachePolicy->getInvalidateOnError()) {
                $redisekServer->del(array($key));
            }
            return $result;
        }


 //var_dump("SAVE");

        try {
            $redisekServer->setIsLogEnabled(true);
            $currentTimestamp = time();
            $value = array(
                "created" => $currentTimestamp,
                "modified" => $currentTimestamp,
                "result"=> $clientResponse->getResult(),
            );
            $redisekServer->set($key, $value);
            $redisekServer->expire($key, $ttl);


        }catch (Exception $e) {
            //var_dump($e->getMessage());
//            var_dump($redisekServer->getLogLastItem());
        }


    }



    public function loadClientResponse(
        Lib_Facebook_Client_Response $clientResponse,
        Lib_Facebook_Client_CachePolicy $cachePolicy,

                $graphId,
                $additionalGraph,

                $apiEndpoint,
                $apiEndpointParams

    )
    {
        $ttl = $cachePolicy->getTtl(); //sec
        if (!is_int($ttl)) {
            $ttl = $cachePolicy->getTtlDefault();
        }
        if (((is_int($ttl)) && ($ttl>=0)) !== true) {
            throw new Exception("Invalid cachePolicy.ttl at ".__METHOD__);
        }


        $redisekServer = $this->getServer();

        $key = $this->newKey(
            $clientResponse,
            $cachePolicy,

            $graphId,
            $additionalGraph,

            $apiEndpoint,
            $apiEndpointParams
        );

        $value = $redisekServer->get($key);
        $responseResult = Lib_Utils_Array::getProperty($value, "result");
        $isValid = is_array($responseResult);
        if (is_array(Lib_Utils_Array::getProperty($value, "error"))) {
            $isValid = false;
        }


        $currentTtl = (int)$redisekServer->ttl($key);
        $newTtl = $ttl;
        if (($currentTtl>$newTtl) || ($currentTtl<1)) {
            $redisekServer->expire($key, (int)$newTtl);
        }


        if (!$isValid) {

            if ($cachePolicy->getInvalidateOnError()) {
                $redisekServer->del(array($key));
            }

            return $clientResponse;
        }

        $value["ttl"] = $redisekServer->ttl($key);

        $clientResponse->setCacheData($value);
        $clientResponse->setDataProvider($responseResult);
        return $clientResponse;
    }


    /**
     * @param Lib_Facebook_Client_Response $clientResponse
     * @param Lib_Facebook_Client_CachePolicy $cachePolicy
     * @param  $graphId
     * @param  $additionalGraph
     * @param  $apiEndpoint
     * @param  $apiEndpointParams
     * @return void
     */
    public function invalidateClientResponse(

                Lib_Facebook_Client_Response $clientResponse,

                Lib_Facebook_Client_CacheInvalidator $cacheInvalidator
    )
    {

        $redisekServer = $this->getServer();



        $userId = 0;
        try {
            if ($cacheInvalidator->getUserId()==="*") {
                $userId = $cacheInvalidator->getUserId();
            } else {
                $userId = $this->getClient()->getFacebook()->getUserId();
            }
        } catch (Exception $e) {

        }

        $graphId = $cacheInvalidator->getGraphId();
        if ($graphId==="me") {
            try {
                $graphId = $this->getClient()->getFacebook()->getUserId();
            } catch (Exception $e) {

            }
        }

        $additionalGraph = $cacheInvalidator->getAdditionalGraph();



        $keyDefinition = array(
            "salt" =>$this->getKeyDefinitionSalt(),
            "dsn" => $this->getKeyDefinitionDsn(),
            "data" => array(
                "fbUserId" => $userId,//"100000000045678",
                "fbGraphId" => $graphId,//"100000000000023",
                "fbAdditionalGraph" => $additionalGraph, //"/events/attending",
                "fbGraphParams" => "*",
            ),
        );
        $keyPattern = $this->parseKeyDefinition($keyDefinition, true);

        $keysList = (array)$redisekServer->keys($keyPattern);
        if (count($keysList)>0) {
            $redisekServer->del($keysList);
        }

        $keysList = (array)$redisekServer->keys($keyPattern);

        
    }


}
