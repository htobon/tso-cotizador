<?php

/*
 Configuring Smarty, Session and Autoloader.
 */

require_once dirname(__FILE__).'/'.'config.php';

require_once LIB_DIR . '/Smarty-3.1.16/Smarty.class.php';

$smarty = new Smarty();
$smarty->setTemplateDir(WORKSPACE_DIR . '/templates');
$smarty->setCompileDir(WORKSPACE_DIR . '/templates_c');
$smarty->setCacheDir(WORKSPACE_DIR . '/cache');
$smarty->setConfigDir(WORKSPACE_DIR . '/config');

// Session
ini_set("session.cookie_lifetime",$options["session.cookie_lifetime"]);
ini_set("session.gc_maxlifetime", $options["session.gc_maxlifetime"]);
//error_reporting(E_ALL ^ E_DEPRECATED);

// Autoloader 
spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    if (file_exists(WORKSPACE_DIR.'/classes/'.$class.'.php')) {
        require_once(WORKSPACE_DIR.'/classes/'.$class.'.php');
    }    
});

?>
