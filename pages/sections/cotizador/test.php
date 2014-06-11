<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
require_once './generarPdf.php';
require_once './generarCsv.php';
require_once './sendEmail.php';


$email = $_GET['email'];
$email_a = $_GET['email_a'];

// Generar Pdf 
$_pdf = new generarPdf(17);
$_pdf->generarCotizacionPdf();
$_pdf->getPdf();
$cotizacionPdf = $_pdf->getPdfbase64();
$nombreCotizacion = "/tmp/pdf/" . $_pdf->getNamePdf();



// Enviar por Correo Electronico
$enviarCorreo = new sendPdfEmail("Cotizacion TSO-mobile", $_pdf->getNamePdf(), $cotizacionPdf);
$enviarCorreo->setTo("Edwin Camilo Orozco", $email, $email_a);
$enviarCorreo->setFrom("Administrador", "corozco@tsomobile.com");

if ($enviarCorreo->enviarCorreo()) {
    echo "Se Envia Correo a {$email} y {$email_a}";
} else {

    echo "No se envia correo";
}