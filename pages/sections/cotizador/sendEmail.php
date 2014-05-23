<?php

class sendPdfEmail {

    private $to;
    private $from;
    private $subject;
    private $fileName;
    private $pdf;

    function __construct($subject, $fileName, $file) {
        $this->subject = $subject;
        $this->fileName = $fileName . ".pdf";
        $this->pdf = $file;
    }

    public function setTo($nombre, $correo, $correo_alterno) {

        $this->to = "{$nombre} <{$correo}>";

        if (!empty($correo_alterno)) {
            $this->to .= ", {$nombre} <{$correo_alterno}>";
        }
    }

    public function setFrom($nombre, $correo) {
        $this->from = "{$nombre} <{$correo}>";
    }

    public function enviarCorreo() {

        $to = $this->to;
        $from = $this->from;
        $subject = $this->subject;
        $message = "<p>Se envia cotizacion adjunta.</p>";

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

        // attachment name
        $filename = $this->fileName;
        // encode data (puts attachment in proper format)
        $pdfdoc = $this->pdf;
        $attachment = chunk_split($pdfdoc);

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

        /*echo "<pre>";
        print_r($to);
        print_r($subject);
        print_r($headers);
        echo "</pre>";
        exit();*/
        
        if (mail($to, $subject, "", $headers)) {
            return true;
        } else {

            return false;
        }
    }

}
