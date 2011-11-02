<?php
return array(
    //prefix must be alphanum and underscore
    'applicationPrefix' => 'com.meetidaaa.dev',

    'gaintSConfig' => array(
        'beanstalkd' => array(
            'servers' => array(
                "1" => array(
                    "host" => "127.0.0.1",
                    "port" => ""
                ),
                "2" => array(
                    "host" => "127.0.0.1",
                    "port" => ""
                ),
                "3" => array(
                    "host" => "127.0.0.1",
                    "port" => ""
                )
            ),
            'workers' => array(),
        ),
        'membase_config' => array(
            'membase_salt' => "monitoring_tool",
            'membase_expireTime' => array(
                "flash" => 5,
                "low" => 10,
                "medium" => 30
            ),
            'membase_ports' => array(
                'user' => array(

                ),
                'friends' => array(

                ),
                'default' => array(

                ),
                'tmp' => array(

                ),
            ),
            'servers' => array(
                "local" => array(
                    "id" => "default",
                    "host" => "127.0.0.1",
                    "port" => "11280"
                ),
                "local" => array(
                    "id" => "default",
                    "host" => "127.0.0.1",
                    "port" => "11280"
                ),
                "local" => array(
                    "id" => "default",
                    "host" => "127.0.0.1",
                    "port" => "11280"
                ),
                "local" => array(
                    "id" => "default",
                    "host" => "127.0.0.1",
                    "port" => "11280"
                ),
            ),
        ),

        'couchDB' => array(
            "logging" => array(
                "host" => "localhost",
                "dbName" => "logging"
            ),
        ),
    ),

    'serverStage' => array(
        // ZEND_Uri does not allow underscore in domain!
        "host" => "dev.meetidaaa.com.local.meetidaaa.com",
        // local: dev.meetidaaa.com.local.meetidaaa.com
        // live: dev.meetidaaa.com

        "hostHttps" => "ssl-dev.meetidaaa.com.local.meetidaaa.com",
        // local: ssl-dev.meetidaaa.com.local.meetidaaa.com
        // live: ssl-dev.meetidaaa.com

        "stage" => 'development'
    ),

    'httpd' => array(
        'protocol' => 'http',
        // ZEND_Uri does not allow underscore in domain!
        "host" => "dev.meetidaaa.com.local.meetidaaa.com",
        // local: dev.meetidaaa.com.local.meetidaaa.com
        // live: dev.meetidaaa.com

        'path' => 'htdocs'
    ),

    'locale' => array(
        'default' => array(
            'lc_all' => 'C',
            'timezone' => 'Europe/Berlin',
        )
    ),

    // +++++++++++++++++ db: default (Zend) +++++++++++++++++++++++++

    'database' => array(
        'adapter' => 'PdoMysql',
        'params' => array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'root',
            'dbname' => 'crowdpark',
            'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            )
        )
    ),

    // ++++++++++++++++ Debugging  +++++++++++++
    "App_Debug" => array(
        "enabled" => true,
        "dumpVar" => array(
            "enabled" => true,
        ),
        "firebug" => array(
            "enabled" => true,
        ),
        "developers" => array(
            "enabled" => true, // (false ... noone is a developer)
            // if "clients" === null (anyone is a developer)
            "clients" => array(


                // define a client
                array(
                    "enabled" => true,
                    // stage is mandatory
                    // (ip: null - dont check ip,
                    //  any ip will be treated as developer)
                    "ip" => array(
                        "whitelist" => array(
                            //"192.168.222.163",
                            "*",
                        ),
                        "blacklist" => array(
                            //"192.168.222.163",
                        ),
                    ),

                    "userAgent" => array(
                        "whitelist" => array(
                            //"192.168.222.163",
                            "*",
                        ),
                        "blacklist" => array(
                        ),
                    ),
                ),

                // define another client
            ),

        ),

    ),


    // ++++++++++ session +++++++++++++++
    'Lib_Session_AbstractSession' => array(
        "saveHandler" => array(
            "type" => "MEMCACHED",
            "backendOptions" => array(
                'servers' => array(
                    array(
                        'host' => '127.0.0.1',
                        'port' => 11211,
                    ),
                ),
                'compression' => false,
            ),
            "frontendOptions" => array(
                'caching' => true,
                'lifetime' => 1800,
                'automatic_serialization' => true,

                //   pre-pended to they id (index)
                //   you choose for each cached item.
                //'cache_id_prefix' => 'myApp',
                //'logging' => true,
                //'logger'  => $oCacheLog,

                //   this performs a consistency check
                // whenever data is written to cache.
                'write_control' => true,

                // If this is set, ignore_user_abort will be set to true
                // while cache is being written. This helps prevent data corruption.
                'ignore_user_abort' => true,
            ),
        ),
    ),


    // ++++++++++++++++++++++++ app: Fb ++++++++++++++++++++++++++++++


    // ++++++++++++++ fb: osapi ++++++++++++++++++++++++++++++++++++++
    'App_Facebook_Config' => array(
        "app" => array(
            "id" => "214748345258289", //"APPID",
            "name" => "meetidaa", //"APP_NAME",
            "url" => "http://apps.facebook.com/meetidaaa", //"http://apps.facebook.com/APPNAME",
            "profilePageUrl" => null,
            "siteName" => null,
            "siteUrl" => null,
        ),

        "api" => array(
            "apiKey" => "214748345258289", //"APIKEY",
            "apiSecret" => "d65b884a39f4d5f574a54f8676c2184d", //"APISECRET",
            "baseDomain" => null,
            "cookieSupportEnabled" => true,
            "fileUploadSupportEnabled" => null,
        ),

        "og" => array(
            // "siteAuthor" => "OG_SITE_AUTHOR",
            // "siteDescription" => "OG_SITE_DESCRIPTION",
            // "siteFavicon" => "OG_SITE_FAVICON",
            // "siteImage" => "OG_SITE_IMAGE",
            // "siteKeywords" => "OG_SITE_KEYWORDS",
            // "sitePublisher" => "OG_SITE_PUBLISHER_URL",
            // "siteTitle" => "OG_SITE_TITLE",
            // "siteType" => "OG_SITE_TYPE",
            // "siteUrl" => "OG_SITE_URL",
        ),

        "fanpage" => array(
            "id" => "246472082061938",
            "url" => "http://www.facebook.com/pages/MyTestPage2/246472082061938",
            "tabUrl" => null,
            "likeRequired" => true,
        ),

        "login" => array(
            "scope" => "", //user_photos,publish_stream",//null,
        ),

        "mvc" => array(
            "canvas" => array(
                "type" => "canvas",
                "contentUrl" => null, //$this->getSiteUrl().'canvas/index.php',
                // the nice url for external access
                "url" => null, //$this->getAppUrl(),
                "enabled" => true, //true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page.
                //  you must implement that in your view or controller)
                "isAuthRequired" => true, // true
            ),
            "tab" => array(
                "type" => "tab", //Lib_Facebook_Config::ENVIRONMENT_TYPE_TAB,
                "contentUrl" => null, //$this->getSiteUrl().'canvas/tab.php',
                // the nice url for external access
                "url" => null, //$this->getFanPageTabUrl(),
                "enabled" => null, //true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page)
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page.
                //  you must implement that in your view or controller)
                "isAuthRequired" => true, // true
            ),
            "connect" => array(
                "type" => "connect", //Lib_Facebook_Config::ENVIRONMENT_TYPE_CONNECT,
                "contentUrl" => null, //$this->getSiteUrl().'canvas/connect.php',
                // the nice url for external access
                "url" => null, //$this->getSiteUrl().'canvas/connect.php',
                "enabled" => null, //true,
                "teaserTarget" => null, // e.g. "tab"
                //  (may redirect to teaserTarget-page.
                //  you must implement that in your view or controller)
                "isAuthRequired" => true, // true
            ),

        ),

    ),
    // ++++++++++++++ fb: mock ++++++++++++++++++++++++++++++++++++++
    'App_Facebook_Mock' => array(
        "mock" => array(
            "enabled" => false,
            "session" => array(
                "signed_request" => "YOUR_FB_SIGNED_REQUEST",
            )
        ),
    ),

    // ++++++++++++++++++++ fb: db ++++++++++++++++++++++++++

    "App_Facebook_Db_Xdb_Client" => array(

        'master' => array(
            'adapter' => 'pdo_mysql',
            'params' => array(

                'host' => 'localhost', //mysqlmaster on live
                // username 16chars
                'username' => 'root',
                'password' => 'root',
                'dbname' => 'crowdpark',


                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''
                )
            )
        ),
        'slaves' => array(
            // a db slave
            /*
            array(
                'adapter' => 'pdo_mysql',
                'params'  => array(
                    'host'     => 'localhost-slave1',
                    // username 16chars
                    'username' => 'meetidaaa-slave1',
                    'password' => 'meetidaaa-slave1',
                    'dbname'   => 'meetidaaa-slave1',
                    'driver_options'  => array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''
                    )
                )
            ),
            */
            // another slave
        ),


    ),


);