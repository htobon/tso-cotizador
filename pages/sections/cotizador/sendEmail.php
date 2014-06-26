<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../libs/PHPMailer/PHPMailerAutoload.php";

class sendPdfEmail {

    private $to;
    private $toName;
    private $toEmail;
    private $toEmail2;
    private $from;
    private $fromName;
    private $fromEmail;
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
        $this->toName = $nombre;
        $this->toEmail = $correo;

        $this->toEmail2 = "";

        if (!empty($correo_alterno)) {
            $this->to .= ", {$nombre} <{$correo_alterno}>";
            $this->toEmail2 = $correo_alterno;
        }
    }

    public function setFrom($nombre, $correo, $firma) {
        $this->from = "{$nombre} <{$correo}>";
        $this->fromName = $nombre;
        $this->fromEmail = $correo;
        //$this->from = "Informacion <info@tsocotizador.info>";
        $this->firma = $firma;
    }

    public function enviarCorreo() {

        $to = $this->to;
        $from = $this->from;
        $subject = $this->subject;

        /* // Convertir imagen en base64
          $path = __DIR__ . "/../../../firmas/{$this->firma}";
          $type = pathinfo($path, PATHINFO_EXTENSION);
          $data = file_get_contents($path);
          $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
          $firma_html = "<img src='{$base64}' alt='firma'  height='150' width='800'/>";
          if (empty($this->firma))
          $firma_html = ""; */

        //firma html
        $firma_html = "<img src='{$_SERVER['SERVER_NAME']}/firmas/{$this->firma}' alt='firma'  height='150' width='800'/>";

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

    public function enviarEmail() {

        $mail = new PHPMailer;

        $mail->isSMTP();                                      
        $mail->Host = 'smtp.gmail.com';                   
        $mail->SMTPAuth = true;                              
        $mail->Username = '';          
        $mail->Password = '';                     
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;                                
        // ssl port 465
        // tsl port 587

        $mail->From = "admin@tsocotizador.info"; //$this->fromEmail; 
        $mail->FromName = "Administrador";//$this->fromName; 
        $mail->addAddress($this->toEmail, $this->toName);    

        if (!empty($this->toEmail2)) {
            $mail->addAddress($this->toEmail2, $this->toName);          
        }
        $mail->addReplyTo('info@tsocotizador.info', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        $fileatt = __DIR__ . "/../../../tmp/pdf/{$this->fileName}";

        $mail->WordWrap = 50;                                 
        $mail->addAttachment($fileatt);                               
        $mail->isHTML(true);                                  

        $mail->Subject = $this->subject;

        $firma_html = "<img src='{$_SERVER['SERVER_NAME']}/firmas/{$this->firma}' alt='firma'  height='150' width='800'/>";

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

        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
            //echo 'Message has been sent';
            return true;
        }
    }

}
