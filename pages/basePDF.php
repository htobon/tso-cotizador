<?php

require_once __DIR__ . "/../libs/tcpdf/tcpdf-6_0_065.php";

// Se hereda la clase TCPDF para personalizar la cabecera y el pie de página
class MYPDF extends TCPDF {

  //Cabecera
  public function Header() {
    // Logo
    $image_file = __DIR__.'/../images/pdfCotizacion/logo_TSO.png';
    $this->Image($image_file, 10, 10, 100, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', 'B', 25);
    //Set color
    $this->setColor('text', 32, 100, 172);
    // Title
    $this->Cell(0, 40, 'Cotización', 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    // Separator line
    $this->SetLineStyle(array('width' => 0.25 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(32, 100, 172)));
    $this->line(10, 35, 195, 35);
  }

  // Page footer
  public function Footer() {

    // Ubica la posicion a 15mm del borde inferior
    $this->SetY(-15);

    // Separator line
    $this->SetLineStyle(array('width' => 0.25 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(32, 100, 172)));
    $this->line(10, 280, 195, 280);

    
    // Set font
    $this->SetFont('helvetica', 'B', 10);
    //Set color
    $this->setColor('text', 32, 100, 172);
    // Primera linea
    $this->Cell(0, 10, 'Cali: ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
  }
}

?>