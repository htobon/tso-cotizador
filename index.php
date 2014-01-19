<?php

require_once "./config/smarty.php";
require_once "./config/db.php";
require_once "./model/Sesion.php";

//Sesion::terminarSesion();
if (Sesion::existeSesion()) {
    // Si ya existe una sesión entonces cargue el menu principal.
    irMenuPrincipal();
} else {
    // Si no existe sesión entonces:
    // El usuario acaba de oprimir el botón enviar?
    if (isset($_POST["enviar"])) {
        echo "Oprimió el botón enviar!!!";
        // Se valida que el usuario exista en la base de datos.
        // Si el usuario existe entonces crear sesión y cargar el menu principal.
        Sesion::iniciarSesion();
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
