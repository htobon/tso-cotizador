<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use utils\Sesion;


if (Sesion::sesionActiva()) {
    $accesorios = AccesoriosDB::getAccesorios();
    $smarty->assign("accesorios", $accesorios);
    $smarty->display("sections/simulador/simulador.tpl");
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}