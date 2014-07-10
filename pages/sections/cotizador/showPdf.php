<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
require_once './generarPdf.php';

use utils\Constantes;
use utils\Sesion;

if (Sesion::sesionActiva()) {
    if (isset($_GET['cotizacion'])) {
        $cotizacion = base64_decode($_GET['cotizacion']);
        // Generar Pdf 
        $_pdf = new generarPdf($cotizacion);
        $_pdf->generarCotizacionPdf();
        $_pdf->getPdf();
    }
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}

