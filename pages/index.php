<?php
require_once __DIR__."/../config/smarty.php";
require_once __DIR__."/../config/autoloader.php";

use db\UserDB;
use utils\Post;
use utils\Sesion;
// Descomentar la siguiente línea en caso de querer terminar la sesión.
// Esto es mientras se desarrolla el botón logout.
// Sesion::terminarSesion();
if (Sesion::sesionActiva()) {
    // Si ya existe una sesión de usuario entonces cargue el menu principal.
    irMenuPrincipal();
} else {
    // Si no existe sesión de usuario entonces:
    // El usuario acaba de oprimir el botón enviar?        
    if(Post::existe("enviar")) {
        // Se valida que el usuario exista en la base de datos y que la contraseña concuerde..
        $user = UserDB::getUsuario(Post::getVar("correo"));
        if($user != null) {
            print_r($user);
        } else {
            
        }
        // Si el usuario existe y tiene correcto el password entonces crear sesión y cargar el menu principal.
        //Sesion::iniciarSesion();
        // Asignando id de usuario 0 por lo pronto mientras hay conexión
        // a la base de datos.
        //Sesion::setVariable(Constantes::SESION_USER_ID, "0");
        irMenuPrincipal();
    } else {
        $smarty->assign("accion", $_SERVER['PHP_SELF']);
        $smarty->display("index-iniciarSesion.tpl");
    }
}

function irMenuPrincipal() {
    global $smarty;
    $smarty->assign("sesionId", session_id());
    $smarty->display("index-menu.tpl");
}

?>
