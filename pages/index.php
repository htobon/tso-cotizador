<?php

require_once __DIR__ . "/../config/smarty.php";
require_once __DIR__ . "/../config/autoloader.php";

use db\UsuarioDB;
use utils\Constantes;
use utils\Post;
use utils\Sesion;

// Descomentar la siguiente línea en caso de querer terminar la sesión.
// Esto es mientras se desarrolla el botón logout.
// Sesion::terminarSesion();
if (Sesion::sesionActiva()) {
    // Si ya existe una sesión de usuario entonces cargue el menu principal.
    $usuario = UsuarioDB::getUsuarioPorID(Sesion::getVariable(Constantes::SESION_USER_ID));
    irMenuPrincipal($usuario);
} else {
    // Si no existe sesión de usuario entonces:
    // El usuario acaba de oprimir el botón enviar?        
    if (Post::existe("enviar")) {
        // Se valida que el usuario exista en la base de datos y que la contraseña concuerde..
        $usuarioExiste = UsuarioDB::validarUsuario(Post::getVar("correo"), Post::getVar("password"));
        if ($usuarioExiste) {
            // Si el usuario existe y tiene correcto el password entonces crear sesión y cargar el menu principal.
            $usuario = UsuarioDB::getUsuarioPorCorreo(Post::getVar("correo"));
            Sesion::iniciarSesion();
            Sesion::setVariable(Constantes::SESION_USER_ID, $usuario->id);
            irMenuPrincipal($usuario);
        } else {
            // Si el usuario NO existe entonces se debe mostrar un error.
            $smarty->assign("error", "Error al iniciar sesión!");
            irFormularioIniciarSesion();
        }
    } else {
        irFormularioIniciarSesion();
    }
}

function irFormularioIniciarSesion() {
    global $smarty;
    $smarty->display("index-iniciarSesion.tpl");
}

function irMenuPrincipal($usuario) {
    global $smarty;
    $smarty->assign("usuario", $usuario);
    $smarty->display("index-menu.tpl");
}

?>
