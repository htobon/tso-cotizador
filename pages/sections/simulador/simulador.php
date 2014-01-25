<?php

require_once __DIR__."/../../../config/smarty.php";
require_once __DIR__."/../../../config/autoloader.php";

use db\AccesoriosDB;
use utils\Sesion;

echo "-------".Sesion::sesionActiva();
if(Sesion::sesionActiva()){
  echo "654654654654";
  //require_once __DIR__."/../../logout.php";
  //return;
}

$accesorios = AccesoriosDB::getAccesorios();

$smarty->assign("accesorios", $accesorios);
$smarty->display("sections/simulador/simulador.tpl");

