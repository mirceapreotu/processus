<?php

print_r("=== CLEAN ENVIROMENT ===" . PHP_EOL);

$processusCorePath = '../library/Processus/core/';
$applicationPath   = '../application/php/Application/';

require_once($processusCorePath . 'Interfaces/InterfaceBootstrap.php');
require_once($processusCorePath . 'Interfaces/InterfaceApplicationContext.php');
require_once ($processusCorePath . 'ProcessusBootstrap.php');
require_once($applicationPath . 'ApplicationBootstrap.php');

$bootstrap = \Application\ApplicationBootstrap::getInstance();
$bootstrap->init();

$tableNames = array(
    'bet_comments',
    'bet_requests',
    'bet_supporters',
    'bets',
    'fbuser_stakes',
    'fbusers',
    'feed',
    'leaderboard',
    'notifications',
);

$couchbaseList = $bootstrap->getApplication()->getRegistry()->getProcessusConfig()->getCouchbaseConfig()->getCouchbasePortList();

foreach ($couchbaseList as $couchbaseItem)
{
    $memcached = new \Processus\Lib\Db\Memcached($couchbaseItem->host, $couchbaseItem->port);
    $memcached->flush();

    print_r('Memcached :: Flush => ' . $couchbaseItem->host . ":" . $couchbaseItem->port . PHP_EOL);
}

$mysql = \Processus\Lib\Db\MySQL::getInstance();

foreach ($tableNames as $name => $item)
{
    $dbh = $mysql->dbh;

    $sqlStmt = 'TRUNCATE ' . $item;

    print_r('Mysql :: ' . $sqlStmt . PHP_EOL);

    $dbh->query($sqlStmt);

}

print_r('everything is clear, like a virgin!!!' . PHP_EOL);

?>