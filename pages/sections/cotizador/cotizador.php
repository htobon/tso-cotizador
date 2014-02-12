<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use db\AccesoriosGpsDB;
use db\PlanesDB;
use db\UnidadesGpsDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {

  $arregloGps = UnidadesGpsDB::getUnidadesGps();
  $accesorios = AccesoriosDB::getAccesorios();
  $restricciones = AccesoriosGpsDB::getAccesoriosGpsRestricciones();
  $planes = PlanesDB::getPlanesActivos();

  $gpsIncompatibles = array();
  $accesoriosIncompatibles = array();

  foreach ($restricciones as $restriccion) {
    $accesorioID = $restriccion["accesorio_id"];
    $gpsID = $restriccion["unidad_gps_id"];
    if (!isset($gpsIncompatibles[$accesorioID])) {
      $gpsIncompatibles[$accesorioID] = array();
    }
    if (!isset($accesoriosIncompatibles[$gpsID])) {
      $accesoriosIncompatibles[$gpsID] = array();
    }
    $gpsIncompatibles[$accesorioID][] = $gpsID;
    $accesoriosIncompatibles[$gpsID][] = $accesorioID;
  }

  // TEMPORAL
  $descuentos = array();
  
  $descuento1 = new stdClass();
  $descuento1->id = 1;
  $descuento1->cantidadMin = 5;
  $descuento1->cantidadMax = 10;
  $descuento1->descuento = 5;  
  array_push($descuentos, $descuento1);
  
  $descuento2 = new stdClass();
  $descuento2->id = 2;
  $descuento2->cantidadMin = 11;
  $descuento2->cantidadMax = 20;
  $descuento2->descuento = 8;  
  array_push($descuentos, $descuento2);
  
  $descuento3 = new stdClass();
  $descuento3->id = 3;
  $descuento3->cantidadMin = 21;
  $descuento3->cantidadMax = 30;
  $descuento3->descuento = 10;  
  array_push($descuentos, $descuento3);
  
  $descuento4 = new stdClass();
  $descuento4->id = 4;
  $descuento4->cantidadMin = 31;
  $descuento4->cantidadMax = 40;
  $descuento4->descuento = 12;  
  array_push($descuentos, $descuento4);

  $descuento5 = new stdClass();
  $descuento5->id = 5;
  $descuento5->cantidadMin = 41;
  $descuento5->cantidadMax = 50;
  $descuento5->descuento = 15;  
  array_push($descuentos, $descuento5);

  $descuento6 = new stdClass();
  $descuento6->id = 6;
  $descuento6->cantidadMin = 51;
  $descuento6->cantidadMax = 999999;
  $descuento6->descuento = 20;  
  array_push($descuentos, $descuento6);
  
  $smarty->assign("arregloGps", $arregloGps);
  $smarty->assign("accesorios", $accesorios);
  $smarty->assign("accesoriosIncompatibles", $accesoriosIncompatibles);
  $smarty->assign("gpsIncompatibles", $gpsIncompatibles);
  $smarty->assign("planes", $planes);
  $smarty->assign("descuentos", $descuentos);
  $smarty->display("sections/cotizador/cotizador.tpl");
} else {
  $smarty->assign("ocultarLogout", 1);
  $smarty->assign("error", "Usted debe iniciar sesión primero.");
  $smarty->display("index-iniciarSesion.tpl");
}

