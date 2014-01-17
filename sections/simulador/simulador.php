<?php
require_once "../../config/smarty.php";
$accesorios = array();
//$audio->id = "1";
//$audio->nombre = "Audio Jit";
//array_push($accesorios, $audio);


$smarty->assign("accesorios", $accesorios);

$smarty->display("simulador.tpl");

