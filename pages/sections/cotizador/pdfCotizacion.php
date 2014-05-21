<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
require_once __DIR__ . "/../../../pages/basePDF.php";

use db\CotizacionDB;
use db\ClienteDB;
use db\AccesoriosDB;
use db\UnidadesGpsDB;
use db\PlanesDB;
use db\DescuentosDB;
use db\TiposContratoDB;
use db\DuracionesContratoDB;
use db\UsuarioDB;
use utils\Constantes;
use utils\Sesion;


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$cotizacion_id = $_GET['cotizacion_id'];
$cotizacion = CotizacionDB::getCotizacion($cotizacion_id);
$cotizacionDetalle = CotizacionDB::getAccesoriosCotizados($cotizacion_id);
$cliente = ClienteDB::getClientePorId($cotizacion->cliente_id);
$unidadGps = UnidadesGpsDB::getUnidadesGpsPorId($cotizacion->unidad_gps_id);

$usuario = UsuarioDB::getUsuarioPorID(Sesion::getVariable(Constantes::SESION_USER_ID));

$totalAccesorios =0;
$totalInstalaciones =0;

/* echo "<pre>";
  print_r($cotizacion);
  print_r($cotizacionDetalle);
  print_r($cliente);
  exit(); */

createDocument();
imprimirDatosCliente();
imprimirCabeceras();
imprimirDatosGPS();
imprimirDatosAccesorios();
imprimirDatosInstalaciones();
imprimirDatosPlanes();
imprimirResumenCotizacion();
imprimirDescuentos();
imprimirTotales();

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

include './enviarEmail.php';

function createDocument() {
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

function imprimirDatosCliente() {

    global $pdf;
    global $cotizacion;
    global $cliente;



    $pdf->setTextBlue();
    $pdf->setBoldFont();

    $pdf->Cell(0, 5, "Cotización No {$cotizacion->serial}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');



    $pdf->Cell(getAnchoColumna1(), 5, 'Señor(a):', 0, 1, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->setNormalFont();
    $pdf->Cell(getAnchoColumna1(), 8, $cotizacion->nombre_contacto, 0, 1, 'L', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(getAnchoColumna1(), 10, $cliente->nombre, 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->Ln();
}

function imprimirCabeceras() {
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

function imprimirDatosGPS() {
    global $pdf;
    global $cotizacion;
    global $unidadGps;


    $total = $cotizacion->cantidad_vehiculos * $unidadGps->precioUnidad;

    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Unidad GPS", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Nombre GPS
    $pdf->Cell(getAnchoColumna1(), 14, $unidadGps->nombre, 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$" . $unidadGps->precioUnidad, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "$" . $total, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Ln();
}

function imprimirDatosAccesorios() {
    global $pdf;
    global $cotizacionDetalle;

    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Accesorios", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();


    foreach ($cotizacionDetalle as $detalle) {

        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $precioTotal = $detalle->cantidad_accesorio * $accesorio->precioAccesorio;

        // Detalle accesorios
        $pdf->Cell(getAnchoColumna1(), 14, $accesorio->nombre, 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $pdf->Cell(getAnchoColumna3(), 14, '$' . $accesorio->precioAccesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->Cell(getAnchoColumna4(), 14, '$' . $precioTotal, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln();
}

function imprimirDatosInstalaciones() {
    global $pdf;
    global $cotizacion;
    global $cotizacionDetalle;
    global $unidadGps;

    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Instalaciones", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle instalacion
    $pdf->Cell(getAnchoColumna1(), 14, "Instalacion {$unidadGps->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$" . $unidadGps->precioInstalacion, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    $precioInstalacionGps = $unidadGps->precioInstalacion * $cotizacion->cantidad_vehiculos;
    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "$" . $precioInstalacionGps, 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    foreach ($cotizacionDetalle as $detalle) {

        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $precioTotal = $detalle->cantidad_accesorio * $accesorio->precioInstalacion;

        // Detalle instalacion
        $pdf->Cell(getAnchoColumna1(), 14, "Instalacion {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $pdf->Cell(getAnchoColumna3(), 14, "$" . $accesorio->precioInstalacion, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->Cell(getAnchoColumna4(), 14, "$" . $precioTotal, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln();
}

function imprimirDatosPlanes() {
    global $pdf;
    global $cotizacion;
    global $cotizacionDetalle;

    $plan = PlanesDB::getPlanePorId($cotizacion->plan_servicio_id);
    $precioPlan = $plan->precio * $cotizacion->cantidad_vehiculos;

    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Planes de servicio", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "{$plan->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$plan->precio}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "$ {$precioPlan}", 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    foreach ($cotizacionDetalle as $detalle) {
        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $precioTotal = $detalle->cantidad_accesorio * $accesorio->precioMensualidad;

        // Detalle plan
        $pdf->Cell(getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $pdf->Cell(getAnchoColumna3(), 14, "$ {$accesorio->precioMensualidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->Cell(getAnchoColumna4(), 14, "$ {$precioTotal}", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln();
}

function imprimirResumenCotizacion() {

    global $pdf;
    global $cotizacion;

    $descuento = DescuentosDB::getDescuentosPorId($cotizacion->descuento_id);
    $tipoContrato = TiposContratoDB::getTiposContratoPorId($cotizacion->tipo_contrato_id);
    $duracionContrato = DuracionesContratoDB::getDuracionContratoPorId($cotizacion->duracion_contrato_id);



    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);


    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 5, "Numero Vehiculos", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 5, $cotizacion->cantidad_vehiculos, 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Porcentaje de descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "{$descuento->descuento}%", 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Tipo de Contrato", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $tipoContrato->nombre, 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    if ($tipoContrato->id == 2) {
        // Detalle plan
        $pdf->Cell(getAnchoColumna1(), 14, "Duración del contrato", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        // Cantidad
        $pdf->Cell(getAnchoColumna2(), 14, "{$duracionContrato->cantidadMeses} Meses", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
    }




    $pdf->Ln();
}

function imprimirDescuentos() {
    
    global $pdf;

    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Descuentos", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "1", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "2", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "3", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "4", 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    $pdf->Ln();
}

function imprimirTotales() {
    global $pdf;
    global $totalAccesorios;
    global $totalInstalaciones;

    $pdf->setTextBlue();
    $pdf->setBoldFont();

    // set font
    $pdf->SetFont('times', 'B', 14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Totales", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->SetFont('times', 'N', 12);
    $pdf->setTextBlack();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Totales Accesorios", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "2", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "3", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "4", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    
    
    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Totales Instalaciones", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "2", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "3", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(getAnchoColumna4(), 14, "4", 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    $pdf->Ln();
}

function getAnchoColumna1() {
    return 80;
}

function getAnchoColumna2() {
    return 30;
}

function getAnchoColumna3() {
    return 35;
}

function getAnchoColumna4() {
    return 35;
}

//============================================================+
// END OF FILE
//============================================================+