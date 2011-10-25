<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 25.09.11
 * Time: 11:01
 * To change this template use File | Settings | File Templates.
 */


require_once "lib/Redisek/Redisek.php";

echo "<pre>";

$config = array(
    "connection" => array(
               "host" => "localhost",
               "port" => "6379",
               "isPersistent" => false,
        ),
    "model" => array(
        "keyPrefix" => array(
            "app" => "com.example.HelloWorld",
            "bucket" => "Fb.User", // default bucket
            "version" => 1,
            "class" => null,
            "dsn"=> "{app}:{class}:v{version}:{bucket}:{key}",
        ),
    ),
     "serializer" => array(
         "enabled" => true,
         "bucket" => array(
             // enable auto-serialize value for buckets
             "flags" => null,
             "whitelist" => array(
                //"Fb.User",
                //"Fb.Api.Request",
                 "Fb.*",
             ),
             "blacklist" => array(

             ),
         )

    ),

);

$server = new Redisek_Server();
$server->setConfig($config);
$server->setIsLogEnabled(true);



var_dump("-------------- current bucket and keyprefix -------------------");

$server->setBucket("Fb.User");

var_dump(array(
        "bucket" => $server->getBucket(),
        "prefix" => $server->getKeyPrefix(),
        "serializeEnabled" => $server->getIsSerializeEnabled(),
        "prefixModel" => $server->getModelKeyPrefix()->getConfig(),
         )
);

$server->getModelKeyPrefix()->requireValidDsn();

//exit;

$userId = '10002334567';

var_dump("-------------- set ($userId, {...} ): -------------------");


$result = $server->set($userId, array(
                    "id" => $userId,
                    "firstname"=>"John",
                    "lastname"=>"Doe",
                  ));
var_dump($result);

var_dump("-------------- get ($userId): -------------------");
$result = $server->get($userId);
var_dump($result);



var_dump("-------------- lpush (log) -------------------");
$server->setBucket("Fb.Api.Request");

var_dump(array(
        "bucket" => $server->getBucket(),
        "prefix" => $server->getKeyPrefix(),
        "serializeEnabled" => $server->getIsSerializeEnabled(),
        "prefixModel" => $server->getModelKeyPrefix()->getConfig(),
         )
);


$result =$server->lpush("log",
     array(
          array(
              "date"=>date("Y-m-d H:i:s"),
              "timeStamp" => time(),
              "ip" => $_SERVER["REMOTE_ADDR"],
              "params" => (array)$_GET,
          ),
     )
);

var_dump($result);



var_dump("-------------- lrange (log) -------------------");
$result=$server->lRange("log", 0, -1);
var_dump($result);






var_dump("-------------- REDIS SERVER LOG -------------------");
var_dump($server->getLog());
