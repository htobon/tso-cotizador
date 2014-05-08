<?php

require_once __DIR__ . "/../../../pages/basePDF.php";

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

createDocument();
imprimirCabeceras();
imprimirDatosGPS();
imprimirDatosAccesorios();
imprimirDatosInstalaciones();
imprimirDatosPlanes();

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

function createDocument(){
	global $pdf;
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('TSO Mobile');
	$pdf->SetTitle('Cotizacion');
	$pdf->SetSubject('Cotizacion');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set font
	$pdf->SetFont('times', 'BI', 12);

	// add a page
	$pdf->AddPage();
	
}




function imprimirDatosCliente(){

}

function imprimirDatosGPS(){
	global $pdf;

	$pdf->setTextBlue();
	$pdf->setBoldFont();

	// set font
	$pdf->SetFont('times', 'B', 14);

	  // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Unidad GPS", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

 	$pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Nombre GPS
	$pdf->Cell(getAnchoColumna1(), 14, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

	// Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Ln();
}

function imprimirDatosAccesorios(){
	global $pdf;

	$pdf->setTextBlue();
	$pdf->setBoldFont();

	// set font
	$pdf->SetFont('times', 'B', 14);

	  // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Accesorios", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle accesorios
	$pdf->Cell(getAnchoColumna1(), 14, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

	// Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
	
    $pdf->Ln();
}

function imprimirDatosInstalaciones(){
	global $pdf;

	$pdf->setTextBlue();
	$pdf->setBoldFont();

	// set font
	$pdf->SetFont('times', 'B', 14);

	  // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Instalaciones", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle instalacion
	$pdf->Cell(getAnchoColumna1(), 14,"", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

	// Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
	
    $pdf->Ln();
}

function imprimirDatosPlanes(){
global $pdf;

	$pdf->setTextBlue();
	$pdf->setBoldFont();

	// set font
	$pdf->SetFont('times', 'B', 14);

	  // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Planes de servicio", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle plan
	$pdf->Cell(getAnchoColumna1(), 14, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

	// Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
	
    $pdf->Ln();	
}

function imprimirCabeceras(){
	global $pdf;

	$pdf->setTextBlue();
	$pdf->setBoldFont();

	// set font
	$pdf->SetFont('times', 'B', 14);

	  // Title
    $pdf->Cell(getAnchoColumna1(), 10, "", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(getAnchoColumna2(), 10, "Cantidad", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(getAnchoColumna3(), 10, "Precio unitario", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(getAnchoColumna4(), 10, "Precio total", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Ln();
}

function getAnchoColumna1(){
	return 80;
}

function getAnchoColumna2(){
	return 30;
}

function getAnchoColumna3(){
	return 35;
}

function getAnchoColumna4(){
	return 35;
}

//============================================================+
// END OF FILE
//============================================================+