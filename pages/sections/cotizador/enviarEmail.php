<?php

// Settings
$name = $cotizacion->nombre_contacto;//"Nombre Contacto";
$email = $cotizacion->correo_contacto;//"camilo.o19@gmail.com";
//$email_alterno = $cotizacion->correo_alterno_contacto;//"camilo.o19@gmail.com";
$to = "$name <$email>";
$from = "{$usuario->nombres} {$usuario->apellidos}";//"Nombre Vendedor";
$subject = "Cotizacion TSO-mobile";
$fileatttype = "application/pdf";
$fileattname = "cotizacion-{$cotizacion->serial}.pdf";
$headers = "From: $from";

$fileatt = $pdf->Output($fileattname, 'E');
$attachment = chunk_split($fileatt);

/*$file = fopen($fileatt, 'rb');
$data = fread($file, filesize($fileatt));
fclose($file);
$attachment = chunk_split(base64_encode($data));*/

// This attaches the file
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
$headers .= "\nMIME-Version: 1.0\n" .
        "Content-Type: multipart/mixed;\n" .
        " boundary=\"{$mime_boundary}\"";
$message = "";
$message = "This is a multi-part message in MIME format.\n\n" .
        "-{$mime_boundary}\n" .
        "Content-Type: text/plain; charset=\"iso-8859-1\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $message .= "\n\n";

$message .= "--{$mime_boundary}\n" .
        "Content-Type: {$fileatttype};\n" .
        " name=\"{$fileattname}\"\n" .
        "Content-Disposition: attachment;\n" .
        " filename=\"{$fileattname}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $attachment . "\n\n" .
        "-{$mime_boundary}-\n";

if (mail($to, $subject, $message, $headers)) {

    echo "The email was sent.";
} else {

    echo "There was an error sending the mail.";
}