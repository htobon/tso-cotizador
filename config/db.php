<?php
require_once dirname(__FILE__).'/'.'config.php';


use Doctrine\Common\ClassLoader;
require '/libs/DoctrineDBAL-2.3/Doctrine/lib/Doctrine/Common/ClassLoader.php';
$classLoader = new ClassLoader('Doctrine', '/libs/DoctrineDBAL-2.3/Doctrine/lib/Doctrine/Common/ClassLoader.php');
$classLoader->register();

$config = new \Doctrine\DBAL\Configuration();
//..
$connectionParams = array(
    'dbname' => $infoDB['db'],
    'user' => $infoDB['username'],
    'password' => $infoDB['password'],
    'host' => $infoDB['host'],
    'driver' => $infoDB['driver'],
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

/*
require_once( LIB_DIR. '/adodb/adodb.inc.php' );

global $db;

$db = &ADONewConnection($infoDB['driver']);
if (!$db) {
    //$log->error('Could not connect to the database.');
    exit;
}
$db->connect($infoDB['host'], $infoDB['username'], $infoDB['password'], $infoDB['db']);
if (!$db->IsConnected()) {
    $db->EXECUTE("set names 'utf8'");
    //$log->error('Could not connect to the database.');
    exit;
}


//$log->info('DB initialized successfully.');
*/


?>
