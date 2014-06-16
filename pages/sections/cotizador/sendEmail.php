<?php

require_once __DIR__ . "/../../../config/smarty.php";

class sendPdfEmail {

    private $to;
    private $from;
    private $subject;
    private $fileName;
    private $pdf;
    private $firma;

    function __construct($subject, $fileName, $file) {
        $this->subject = $subject;
        $this->fileName = $fileName;
        $this->pdf = $file;
    }

    public function setTo($nombre, $correo, $correo_alterno) {

        $this->to = "{$nombre} <{$correo}>";

        if (!empty($correo_alterno)) {
            $this->to .= ", {$nombre} <{$correo_alterno}>";
        }
    }

    public function setFrom($nombre, $correo, $firma) {
        $this->from = "{$nombre} <{$correo}>";
        $this->firma = $firma;
    }

    public function enviarCorreo() {

        $to = $this->to;
        $from = $this->from;
        $subject = $this->subject;

        // Convertir imagen en base64
        $path = __DIR__ . "/../../../firmas/{$this->firma}";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $firma_html = "<img src='{$base64}' alt='firma'  height='150' width='800'/>";
        if (empty($this->firma))
            $firma_html = "";

        $message = "<html>
                        <body>
                            <p>Cordial Saludo.</p>
                            <p>Muchas gracias por su inter&eacute;s en nuestras soluciones.</p>
                            <p>Adjunto enviamos nuestra propuesta econ&oacute;mica. Estamos seguros de que nuestra compa&ntilde;&iacute;a podr&aacute; brindarle los mejores y m&aacute;s completos servicios de Monitoreo y Rastreo Satelital.
                            Quedamos atentos para ayudarles en la toma de la mejor decisi&oacute;n y resolver todas sus inquietudes.</p>
                            <p>Atentamente,</p>
                            {$firma_html}
                        </body>
                    </html>";

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

        /* // attachment name
          $filename = $this->fileName;
          // encode data (puts attachment in proper format)
          $pdfdoc = $this->pdf;
          $attachment = chunk_split($pdfdoc); */

        $filename = $this->fileName;
        $fileatt = __DIR__ . "/../../../tmp/pdf/{$filename}";
        $file = fopen($fileatt, 'rb');
        $data = fread($file, filesize($fileatt));
        fclose($file);
        $attachment = chunk_split(base64_encode($data));


        // main header (multipart mandatory)
        $headers = "From: " . $from . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol . $eol;

        // message
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $headers .= $message . $eol . $eol;

        // attachment
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: base64" . $eol;
        $headers .= "Content-Disposition: attachment" . $eol . $eol;
        $headers .= $attachment . $eol . $eol;
        $headers .= "--" . $separator . "--";


        if (mail($to, $subject, "", $headers)) {
            return true;
        } else {

            return false;
        }
    }

    public function enviar() {

        $to = $this->to;
        //$from = "Hector Fabio@tsomobile.com";
        $from = $this->from;
        $subject = $this->subject;
        $fileatttype = "application/pdf";
        $fileattname = $this->fileName;
        $headers = "From: $from";

        $fileatt = $this->pdf;
        $attachment = chunk_split($fileatt);

        /* $file = fopen($fileatt, 'rb');
          $data = fread($file, filesize($fileatt));
          fclose($file);
          $attachment = chunk_split(base64_encode($data)); */

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

        /* echo "<pre>";       
          print_r($to);
          print_r($subject);
          print_r($message);
          print_r($headers);
          echo "</pre>";
          exit(); */

        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

}
