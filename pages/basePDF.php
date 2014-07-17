<?php

require_once __DIR__ . "/../libs/tcpdf/tcpdf-6_0_065.php";

// Se hereda la clase TCPDF para personalizar la cabecera y el pie de página
class MYPDF extends TCPDF {

    //Cabecera
    public function Header() {

        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);

        // set background image
        $img_file = __DIR__ . '/../images/bg.jpg';
        $this->Image($img_file, 0, 0, 250, 310, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();

        // Logo
        $image_file = __DIR__ . '/../images/pdfCotizacion/logo_TSO.png';
        $this->Image($image_file, 10, 10, 100, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);


        $this->setBoldFont(25);
        $this->setTextBlue();
        // Title
        //$this->Cell(0, 40, 'Cotización', 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        // Separator line
        $this->SetLineStyle(array('width' => 0.25 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(32, 100, 172)));
        $this->line(16, 33, 195, 33);

        $this->SetMargins(PDF_MARGIN_LEFT, 39, PDF_MARGIN_RIGHT);
    }

    public function getScaleFactor() {
        return $this->k;
    }

    // Page footer
    public function Footer() {

        // Ubica la posicion a 15mm del borde inferior
        $this->SetY(-25);

        $this->setTextBlue();
        $this->setBoldFont(15);
        $this->Cell(15, 6, "Líder", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->setNormalFont(15);
        $this->Cell(85, 6, "Latinoamericano  en Gestión Satelital", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->setBoldFont(15);
        $this->Cell(0, 6, "www.tsomobile.com", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        $this->Ln(2);
        // Separator line
        $this->SetLineStyle(array('width' => 0.25 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(32, 100, 172)));
        $this->line(14, 276, 195, 276);

        /**/
        $this->setTextBlue();
        $this->setNormalFont(7.5);

        $this->Cell(33, 6, "Calle 30 Norte #2Bis - 61", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(27, 6, "PBX: +57 (2)6410990", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(25, 6, "Carrera 17ª #24-73", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(30, 6, "CEL: +57 314-5300277", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(35, 6, "Carrera 43ª No 71 sur - 103", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(0, 6, "CEL: +57 316-7549187", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        $this->Cell(33, 8, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(27, 8, "FAX: +57 (2)3957060", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(25, 8, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(30, 8, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(35, 8, "Oficina 406 Edificio Alondras", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(0, 8, "", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        $this->setBoldFont(9);
        $this->Cell(33, 10, "Cali", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(27, 10, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(25, 10, "Cartagena", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(30, 10, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(35, 10, "Medellín", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->Cell(0, 10, "", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        /**/
        /* $this->setTextBlue();

          // Primera linea
          $this->setBoldFont();

          $label = "Cali:";
          $cellWidth = $this->GetStringWidth($label) + 3;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setNormalFont();
          $label = "Calle 30 Norte 2 Bis - 61";
          $cellWidth = $this->GetStringWidth($label) + 5;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setBoldFont();
          $label = "PBX:";
          $cellWidth = $this->GetStringWidth($label) + 3;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setNormalFont();
          $label = "+ (57) 2 641-0990";
          $cellWidth = $this->GetStringWidth($label) + 5;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');
          $this->setBoldFont();

          $this->setBoldFont();
          $label = "FAX:";
          $cellWidth = $this->GetStringWidth($label) + 3;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setNormalFont();
          $label = "+ (57) 2 395-7060";
          $cellWidth = $this->GetStringWidth($label) + 5;
          $this->Cell($cellWidth, 6, $label, 0, true, 'L', 0, '', 0, false, 'T', 'M');


          // Segunda linea
          $this->setBoldFont();
          $label = "PBX Bogotá:";
          $cellWidth = $this->GetStringWidth($label) + 3;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setNormalFont();
          $label = "+ (57) 2 395-7060";
          $cellWidth = $this->GetStringWidth($label) + 5;
          $this->Cell($cellWidth, 6, $label, 0, true, 'L', 0, '', 0, false, 'T', 'M');


          // Tercera línea
          $this->setBoldFont();

          $label = "Contacto:";
          $cellWidth = $this->GetStringWidth($label) + 3;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setNormalFont();
          $label = "Pedro Paramo";
          $cellWidth = $this->GetStringWidth($label) + 5;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setBoldFont();
          $label = "Email:";
          $cellWidth = $this->GetStringWidth($label) + 3;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');

          $this->setNormalFont();
          $label = "paramo@tsomobile.com";
          $cellWidth = $this->GetStringWidth($label) + 5;
          $this->Cell($cellWidth, 6, $label, 0, false, 'L', 0, '', 0, false, 'T', 'M');
          $this->setBoldFont(); */
    }

    public function setBoldFont($size = 10) {
        $this->SetFont('helvetica', 'B', $size);
    }

    public function setNormalFont($size = 10) {
        $this->SetFont('helvetica', '', $size);
    }

    public function setTextBlue() {
        $this->setColor('text', 32, 100, 172);
    }

    public function setTextBlack() {
        $this->setColor('text', 25, 25, 25);
    }

}

?>