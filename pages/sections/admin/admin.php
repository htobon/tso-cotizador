<?php
require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use db\UsuarioDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {
    $usuarios = UsuarioDB::getUsuarios();    
    $smarty->assign("usuarios", $usuarios);
    
    $accesorios = AccesoriosDB::getAccesorios();
    $smarty->assign("accesorios", $accesorios);
    
    //$smarty->display("sections/admin/admin.tpl");
    $smarty->display("sections/admin/cotizacionesGeneradas.tpl");
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}

?>
