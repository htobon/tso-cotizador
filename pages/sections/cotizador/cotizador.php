<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {

	$arregloGps = array();
	
	$gps1 = new stdClass();
    $gps1->id = "1";
    $gps1->nombre = "TSO-3500";
    $gps1->precioUnidad = "36000";
    array_push($arregloGps, $gps1);

    $gps1 = new stdClass();
    $gps1->id = "2";
    $gps1->nombre = "TSO-4500";
    $gps1->precioUnidad = "36000";
    array_push($arregloGps, $gps1);

    $gps1 = new stdClass();
    $gps1->id = "3";
    $gps1->nombre = "TSO-6000";
    $gps1->precioUnidad = "28000";
    array_push($arregloGps, $gps1);

    $gps1 = new stdClass();
    $gps1->id = "4";
    $gps1->nombre = "TSO-9000";
    $gps1->precioUnidad = "28000";
    array_push($arregloGps, $gps1);


   
    $accesorios = AccesoriosDB::getAccesorios();
    $smarty->assign("arregloGps", $arregloGps);
    $smarty->assign("accesorios", $accesorios);
    $smarty->display("sections/cotizador/cotizador.tpl");
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}

