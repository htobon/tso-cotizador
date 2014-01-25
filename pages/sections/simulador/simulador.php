<?php
require_once "../../../config/smarty.php";
require_once '../../../config/db.php';

$sql = "SELECT * FROM accesorios";
$stmt = $conn->query($sql);
$accesorios = $stmt->fetchAll();
$smarty->assign("accesorios", $accesorios);

$smarty->display("sections/simulador/simulador.tpl");

