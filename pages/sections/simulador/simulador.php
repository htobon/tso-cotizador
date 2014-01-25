<?php

require_once __DIR__."/../../../config/smarty.php";
require_once __DIR__."/../../../config/autoloader.php";

use db\AccesoriosDB;
use utils\Sesion;

$accesorios = AccesoriosDB::getAccesorios();

$smarty->assign("accesorios", $accesorios);
$smarty->display("sections/simulador/simulador.tpl");

