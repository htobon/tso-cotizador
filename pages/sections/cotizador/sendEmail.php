<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/config.php";
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

    public function enviarEmail() {

        $mail = new PHPMailer;
        
        global $infoSmtp;

        $mail->isSMTP();                                      
        $mail->Host = $infoSmtp['host'];//'smtp.gmail.com';                   
        $mail->SMTPAuth = $infoSmtp['SMTPAuth']; //true;                              
        $mail->Username = $infoSmtp['Username']; //'tsomobile.cotizador@gmail.com';          
        $mail->Password = $infoSmtp['Password']; //'tsocotizador';                     
        $mail->SMTPSecure = $infoSmtp['SMTPSecure']; //'tls';
        $mail->Port = $infoSmtp['Port']; //587;                                
        // ssl port 465
        // tsl port 587

        $mail->From = "admin@tsocotizador.info"; //$this->fromEmail; 
        $mail->FromName = $this->fromName;//"Administrador";//$this->fromName; 
        $mail->addAddress($this->toEmail, $this->toName);    

        if (!empty($this->toEmail2)) {            
            $mail->addAddress($this->toEmail2, $this->toName);          
        }
        $mail->addReplyTo('info@tsocotizador.info', 'Information');
        $mail->addCC($this->fromEmail, $this->fromName);
        //$mail->addBCC('bcc@example.com');

        $fileatt = __DIR__ . "/../../../tmp/pdf/{$this->fileName}";

        $mail->WordWrap = 50;                                 
        $mail->addAttachment($fileatt);                               
        $mail->isHTML(true);                                  

        $mail->Subject = $this->subject;

        //$firma_html = "<img src='{$_SERVER['SERVER_NAME']}/firmas/{$this->firma}' alt='firma'  height='150' width='800'/>";
        
        //$mail->AddEmbeddedImage( __DIR__ . "/../../../firmas/{$this->firma}", 'logo_2u');
        //$mail->AddEmbeddedImage("http://www.tsocotizador.info/firmas/{$this->firma}", 'logo_2u');

        $message = "<html>
                        <body>
                            <p>Cordial Saludo.</p>
                            <p>Muchas gracias por su inter&eacute;s en nuestras soluciones.</p>
                            <p>Adjunto enviamos nuestra propuesta econ&oacute;mica. Estamos seguros de que nuestra compa&ntilde;&iacute;a podr&aacute; brindarle los mejores y m&aacute;s completos servicios de Monitoreo y Rastreo Satelital.
                            Quedamos atentos para ayudarles en la toma de la mejor decisi&oacute;n y resolver todas sus inquietudes.</p>
                            <p>Atentamente,</p>
                            <img src='http://www.tsocotizador.info/firmas/{$this->firma}' alt='firma'  height='150' width='800'/>
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
