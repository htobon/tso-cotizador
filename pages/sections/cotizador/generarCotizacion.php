<?php
require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

echo "<h1>Cargando</h1>";
print"<pre>"; print_r($_POST);print"</pre>";
global $smarty;
$smarty->display("sections/cotizador/generarCotizacion.tpl");

?>