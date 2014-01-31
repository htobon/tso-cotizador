<?php
// Session must be called first, otherwise the session id would change between pages.
require_once __DIR__.'/'.'session.php';

// Configuring Smarty.
require_once __DIR__.'/'.'config.php';


require_once LIB_DIR . '/Smarty-3.1.16/Smarty.class.php';

$smarty = new Smarty();
$smarty->setTemplateDir(WORKSPACE_DIR . '/templates');
$smarty->setCompileDir(WORKSPACE_DIR . '/templates_c');
$smarty->setCacheDir(WORKSPACE_DIR . '/cache');
$smarty->setConfigDir(WORKSPACE_DIR . '/config');


//error_reporting(E_ALL ^ E_DEPRECATED);

?>
