<?php
require_once "../../config/smarty.php";
require_once '../../config/db.php';

$sql = "SELECT * FROM accesorios";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll();

$smarty->assign("accesorios", $rows);

$smarty->display("simulador.tpl");

