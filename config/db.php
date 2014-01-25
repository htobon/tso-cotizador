<?php

require_once __DIR__ . '/' . 'config.php';
require_once WORKSPACE_DIR . '/libs/DoctrineDBAL-2.3.4/Doctrine/Common/ClassLoader.php';

function getConn() {
    $classLoader = new Doctrine\Common\ClassLoader('Doctrine', WORKSPACE_DIR . '/libs/DoctrineDBAL-2.3.4');
    $classLoader->register();

    $config = new Doctrine\DBAL\Configuration();

    global $infoDB;
    $connectionParams = array(
        'dbname' => $infoDB['db'],
        'user' => $infoDB['username'],
        'password' => $infoDB['password'],
        'host' => $infoDB['host'],
        'driver' => $infoDB['driver'],
        'charset' => 'utf8',
        'driverOptions' => array(
            1002 => 'SET NAMES utf8'
        )
    );
    return $conn = Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
}

?>
