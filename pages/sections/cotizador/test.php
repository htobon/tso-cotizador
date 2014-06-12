<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
require_once './generarPdf.php';
require_once './generarCsv.php';
require_once './sendEmail.php';


$email = $_GET['email'];
$email_a = $_GET['email_a'];
/*
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
}*/

//set the recipient email address, where to send emails to
$to_email = $email;
//set the sender email address
$your_email = "admin@tsocotizador.info";
//use your email address as the sender
$header = "From: Camilo Orozco <" . $your_email . ">\r\n";
//put the site visitor's address in the Reply-To header
$header .= "Reply-To: Dpto Ventas <" . $your_email . ">\r\n";
//set the email Subject using the site visitor's name
$subject = " TEST  ";
//set the email body with all the site visitor's information
$emailMessage = "Name: testing \r\n";
$emailMessage .= "Email: " . $email . "\r\n";
$emailMessage .= "Message: 123 \r\n";
//send the email

if (mail($to_email, $subject, $emailMessage, $header)){
    echo "Se Envia Correo a {$email}";
}else{
    echo "No se envia correo";
}