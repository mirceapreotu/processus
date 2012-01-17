<?php
return array(
    'application'    => array(
        'state'       => 'development',
        'host'        => '',
        'name'        => 'shakeonitapp',
        'expiredTime' => array(
            'default'    => 10,
            'mysqlCache' => 10,
        ),
    ),
    'processus'      => array(
        'beanstalkd'      => array(
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
            'couchbaseSalt'    => "Crowdpark4Ever!",
            'couchbaseTime'    => array(
                'default'   => array(),
                'fbusers'   => array(),
                'deeplink'  => array(),
                'userStake' => array(),
                'tmp'       => array(),
            ),
            'couchbasePorts'   => array(
                'default'        => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'fbusers'        => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'deeplink'       => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'userStake'      => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'tmp'            => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'comments'       => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
                'bets'           => array(
                    "host" => "127.0.0.1",
                    "port" => "11211"
                ),
            ),
            'couchbaseServers' => array(
                "0" => array(
                    "id"   => "default",
                    "host" => "127.0.0.1"
                ),
                "1" => array(
                    "id"   => "default",
                    "host" => "127.0.0.1"
                ),
                "2" => array(
                    "id"   => "default",
                    "host" => "127.0.0.1"
                ),
                "3" => array(
                    "id"   => "default",
                    "host" => "127.0.0.1"
                )
            )
        ),
        "mysql"           => array(
            "masters" => array(
                "0" => array(
                    'adapter' => 'PdoMysql',
                    'params'  => array(
                        'host'           => 'localhost',
                        'username'       => 'root',
                        'password'       => 'root',
                        'dbname'         => 'shakeonitapp',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                )
            ),
            "slaves"  => array(
                "0" => array(
                    'adapter' => 'PdoMysql',
                    'params'  => array(
                        'host'           => 'localhost',
                        'username'       => '',
                        'password'       => '',
                        'dbname'         => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                ),
                "1" => array(
                    'adapter' => 'PdoMysql',
                    'params'  => array(
                        'host'           => 'localhost',
                        'username'       => '',
                        'password'       => '',
                        'dbname'         => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                ),
                "3" => array(
                    'adapter' => 'PdoMysql',
                    'params'  => array(
                        'host'           => 'localhost',
                        'username'       => '',
                        'password'       => '',
                        'dbname'         => '',
                        'driver_options' => array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                        )
                    )
                )
            )
        )
    ),

    'locale'         => array(
        'default' => array(
            'lc_all'   => 'C',
            'timezone' => 'Europe/Berlin'
        )
    ),

    // ++++++++++++++ fb: osapi ++++++++++++++++++++++++++++++++++++++

    'Facebook'       => array(
        "appId"  => "", //"APIKEY",
        "secret" => "" //"APISECRET",
    ),

    // ++++++++++++++ fb: osapi ++++++++++++++++++++++++++++++++++++++

    'Profiler'       => array(
        "ips" => array(""),
    ),

    'Logging'        => array(

    ),

    // ++++++++++++++ Sendgrid Config ++++++++++++++++++++++++++++++++++++++

    'SendGrid'       => array(
        "credentials" => array(
            "user"     => "",
            "password" => ""
        ),
        "server"      => array(
            "host" => "smtp.sendgrid.net",
            "port" => ""
        ),
        "author"      => array(
            "fromEmail" => "noreply@anteup.com",
            "fromName"  => "AnteUp"
        ),
    ),
);