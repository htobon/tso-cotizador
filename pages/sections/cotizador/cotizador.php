<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use db\AccesoriosGpsDB;
use db\DescuentosDB;
use db\DuracionesContratoDB;
use db\PlanesDB;
use db\UnidadesGpsDB;
use db\AccesoriosPlanesDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {

  $arregloGps = UnidadesGpsDB::getUnidadesGpsActivas();
  $accesorios = AccesoriosDB::getAccesoriosActivos();

  $restriccionesAccesoriosGps = AccesoriosGpsDB::getAccesoriosGpsRestricciones();
  $restriccionesAccesoriosPlanes = AccesoriosPlanesDB::getAccesoriosPlanesRestricciones();

  $planes = PlanesDB::getPlanesActivos();
  $descuentos = DescuentosDB::getDescuentosActivos();
  $duraciones = DuracionesContratoDB::getDuracionesContratoActivas();

  $gpsIncompatiblesAccesorio = array();
  $planesIncompatiblesAccesorio = array();
  $accesoriosIncompatiblesGPS = array();
  $accesoriosIncompatiblesPlanes = array();

  // Crear arreglos con restricciones entre Accesorios y GPS
  foreach ($restriccionesAccesoriosGps as $restriccion) {
    $accesorioID = $restriccion["accesorio_id"];
    $gpsID = $restriccion["unidad_gps_id"];
    if (!isset($gpsIncompatiblesAccesorio[$accesorioID])) {
      $gpsIncompatiblesAccesorio[$accesorioID] = array();
    }
    if (!isset($accesoriosIncompatiblesGPS[$gpsID])) {
      $accesoriosIncompatiblesGPS[$gpsID] = array();
    }

    $gpsIncompatiblesAccesorio[$accesorioID][] = $gpsID;
    $accesoriosIncompatiblesGPS[$gpsID][] = $accesorioID;
  }

  // Crear arreglos con restricciones entre Accesorios y planes
  foreach ($restriccionesAccesoriosPlanes as $restriccion) {
    $accesorioID = $restriccion["accesorio_id"];
    $planID = $restriccion["planes_id"];

    if(!isset($accesoriosIncompatiblesPlanes[$planID])){
      $accesoriosIncompatiblesPlanes[$planID] = array();
    }
    if(!isset($planesIncompatiblesAccesorio[$accesorioID])){
      $planesIncompatiblesAccesorio[$accesorioID] = array();
    }

    $accesoriosIncompatiblesPlanes[$planID][] = $accesorioID;
    $planesIncompatiblesAccesorio[$accesorioID][] = $planID;
  }

  // Asignación de variables Smarty.
  $smarty->assign("arregloGps", $arregloGps);
  $smarty->assign("accesorios", $accesorios);
  $smarty->assign("accesoriosIncompatiblesGPS", $accesoriosIncompatiblesGPS);
  $smarty->assign("gpsIncompatiblesAccesorio", $gpsIncompatiblesAccesorio);
  $smarty->assign("planesIncompatiblesAccesorio", $planesIncompatiblesAccesorio);
  $smarty->assign("accesoriosIncompatiblesPlanes", $accesoriosIncompatiblesPlanes);
  
  $smarty->assign("planes", $planes);
  $smarty->assign("descuentos", $descuentos);
  $smarty->assign("duraciones", $duraciones);
  $smarty->display("sections/cotizador/cotizador.tpl");
} else {
  $smarty->assign("ocultarLogout", 1);
  $smarty->assign("error", "Usted debe iniciar sesión primero.");
  $smarty->display("index-iniciarSesion.tpl");
}

