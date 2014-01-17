<?php
require_once dirname(__FILE__).'/'.'config.php';


use Doctrine\Common\ClassLoader;
//require '/libs/DoctrineDBAL-2.3/Doctrine/lib/Doctrine/Common/ClassLoader.php';
require '/libs/DoctrineDBAL-2.3.4/Doctrine/Common/ClassLoader.php';
$classLoader = new ClassLoader('Doctrine', '/libs/DoctrineDBAL-2.3.4');
$classLoader->register();

$config = new \Doctrine\DBAL\Configuration();

$connectionParams = array(
    'dbname' => $infoDB['db'],
    'user' => $infoDB['username'],
    'password' => $infoDB['password'],
    'host' => $infoDB['host'],
    'driver' => $infoDB['driver'],
    'charset' => 'utf8',
                 'driverOptions' => array(
                    1002=>'SET NAMES utf8'
                 )
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
//$conn = DriverManager::getConnection($connectionParams, $config);

?>
