<?php
require_once "./config/smarty.php";
require_once './config/db.php';

$sql = "SELECT * FROM test";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll();

$smarty->assign("infos", $rows);

$smarty->display("index.tpl");

?>
