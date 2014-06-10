<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
require_once './generarPdf.php';
require_once './generarCsv.php';
require_once './sendEmail.php';


// Generar Pdf 
$_pdf = new generarPdf(17);
$_pdf->generarCotizacionPdf();
$_pdf->getPdf();
$cotizacionPdf = $_pdf->getPdfbase64();
$nombreCotizacion = "/tmp/pdf/" . $_pdf->getNamePdf();



// Enviar por Correo Electronico
$enviarCorreo = new sendPdfEmail("Cotizacion TSO-mobile", "cotizacion-123.pdf", $cotizacionPdf);
$enviarCorreo->setTo("Edwin Camilo Orozco", "camilo.o19@gmail.com", "corozco@ecodev.com.co");
$enviarCorreo->setFrom("Administrado", "corozco@tsomobile.com");

if ($enviarCorreo->enviarCorreo()) {



    echo "Se Envia Correo ";
} else {

    echo "No se envia correo";
}