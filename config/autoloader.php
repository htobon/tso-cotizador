<?php
// Autoloader 
spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    if (file_exists(WORKSPACE_DIR.'/classes/'.$class.'.php')) {
        require_once(WORKSPACE_DIR.'/classes/'.$class.'.php');
    }    
});
?>
