<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use db\UnidadesGpsDB;
use db\AccesoriosGpsDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {

    $arregloGps = UnidadesGpsDB::getUnidadesGps();
	$accesorios = AccesoriosDB::getAccesorios();
    $restricciones = AccesoriosGpsDB::getAccesoriosGpsRestricciones();

    $gpsIncompatibles = array();
    $accesoriosIncompatibles = array();

    foreach ($restricciones as $restriccion) {
        $accesorioID = $restriccion["accesorio_id"];
        $gpsID = $restriccion["unidad_gps_id"];

        if( !isset( $gpsIncompatibles[$accesorioID] ) ) $gpsIncompatibles[$accesorioID] = array();
        if( !isset( $accesoriosIncompatibles[$gpsID] ) ) $accesoriosIncompatibles[$gpsID] = array();

        $gpsIncompatibles[$accesorioID][] = $gpsID;
        $accesoriosIncompatibles[$gpsID][] = $accesorioID;
    }

    
    $smarty->assign("arregloGps", $arregloGps);
    $smarty->assign("accesorios", $accesorios);
    $smarty->assign("accesoriosIncompatibles", $accesoriosIncompatibles);
    $smarty->assign("gpsIncompatibles", $gpsIncompatibles);
    $smarty->display("sections/cotizador/cotizador.tpl");
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}

