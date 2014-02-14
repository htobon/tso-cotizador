<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\AccesoriosDB;
use db\AccesoriosGpsDB;
use db\DescuentosDB;
use db\DuracionesContratoDB;
use db\PlanesDB;
use db\UnidadesGpsDB;
use utils\Sesion;

if (Sesion::sesionActiva()) {

  $arregloGps = UnidadesGpsDB::getUnidadesGps(); // FAVOR REVISAR. Solo se deben devolver las unidades activas.
  
  $accesorios = AccesoriosDB::getAccesoriosActivos();
  $restricciones = AccesoriosGpsDB::getAccesoriosGpsRestricciones(); // FAVOR REVISAR. Solo se deben devolver las restricciones activas.
  $planes = PlanesDB::getPlanesActivos();
  $descuentos = DescuentosDB::getDescuentosActivos();
  $duraciones = DuracionesContratoDB::getDuracionesContratoActivas();

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
  
  // Asignación de variables Smarty.
  $smarty->assign("arregloGps", $arregloGps);
  $smarty->assign("accesorios", $accesorios);
  $smarty->assign("accesoriosIncompatibles", $accesoriosIncompatibles);
  $smarty->assign("gpsIncompatibles", $gpsIncompatibles);
  $smarty->assign("planes", $planes);
  $smarty->assign("descuentos", $descuentos);
  $smarty->assign("duraciones", $duraciones);
  $smarty->display("sections/cotizador/cotizador.tpl");
} else {
  $smarty->assign("ocultarLogout", 1);
  $smarty->assign("error", "Usted debe iniciar sesión primero.");
  $smarty->display("index-iniciarSesion.tpl");
}

