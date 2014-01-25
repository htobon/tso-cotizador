<?php

require_once __DIR__ . "/../config/smarty.php";
require_once __DIR__ . "/../config/autoloader.php";

use utils\Sesion;

Sesion::terminarSesion();
global $smarty;
$smarty->assign("ocultarLogout", 1);
$smarty->display("index-iniciarSesion.tpl");
?>
