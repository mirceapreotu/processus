<?php
return array(

    'appName' => 'exampleApp',

    'processus' => array(
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
            'workers' => array()
        ),
        'couchbaseConfig' => array(
            'couchbaseSalt' => "DefaultSalt",
            'couchbasePorts' => array(
                'default' => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'fbusers' => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'tmp' => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                )
            ),
            'couchbaseServers' => array(
                "0" => array(
                    "id" => "default",
                    "host" => "127.0.0.1"
                ),
                "1" => array(
                    "id" => "default",
                    "host" => "127.0.0.1"
                ),
                "2" => array(
                    "id" => "default",
                    "host" => "127.0.0.1"
                ),
                "3" => array(
                    "id" => "default",
                    "host" => "127.0.0.1"
                )
            )
        ),
        "mysql" => array(
            "masters" => array(
                "0" => array(
                    'adapter' => 'PdoMysql',
                    'params' => array(
                        'host' => 'localhost',
                        'username' => '',
                        'password' => '',
                        'dbname' => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                )
            ),
            "slaves" => array(
                "0" => array(
                    'adapter' => 'PdoMysql',
                    'params' => array(
                        'host' => 'localhost',
                        'username' => '',
                        'password' => '',
                        'dbname' => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                ),
                "1" => array(
                    'adapter' => 'PdoMysql',
                    'params' => array(
                        'host' => 'localhost',
                        'username' => '',
                        'password' => '',
                        'dbname' => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                ),
                "3" => array(
                    'adapter' => 'PdoMysql',
                    'params' => array(
                        'host' => 'localhost',
                        'username' => '',
                        'password' => '',
                        'dbname' => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                )
            )
        )
    ),

    'locale' => array(
        'default' => array(
            'lc_all' => 'C',
            'timezone' => 'Europe/Berlin'
        )
    ),

    'Facebook' => array(
        "appId" => "", //"APIKEY",
        "secret" => "" //"APISECRET",
    ),

    'Profiler' => array(
        "ips" => array()
    )
);