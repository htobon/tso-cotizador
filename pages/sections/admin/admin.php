<?php
require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\UsuarioDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {
    $usuarios = UsuarioDB::getUsuarios();
    
    $smarty->assign("usuarios", $usuarios);
    $smarty->display("sections/admin/admin.tpl");    
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}




?>
