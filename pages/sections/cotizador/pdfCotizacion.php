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

if (!Sesion::sesionActiva()) {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
    exit();
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$cotizacion_id = $_GET['cotizacion_id'];
$cotizacion = CotizacionDB::getCotizacion($cotizacion_id);
$cotizacionDetalle = CotizacionDB::getAccesoriosCotizados($cotizacion_id);
$cliente = ClienteDB::getClientePorId($cotizacion->cliente_id);
$unidadGps = UnidadesGpsDB::getUnidadesGpsPorId($cotizacion->unidad_gps_id);
$descuento = DescuentosDB::getDescuentosPorId($cotizacion->descuento_id);

$usuario = UsuarioDB::getUsuarioPorID(Sesion::getVariable(Constantes::SESION_USER_ID));

$cantidadAccesorios = 0;
$valorAccesorios = 0;
$totalAccesorios = 0;

$cantidadInstalaciones = 0;
$valorInstalaciones = 0;
$totalInstalaciones = 0;

$valorPlan = 0;
$totalPlan = 0;

$valorDescuento = 0;
$totalDescuento = 0;

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

    $pdf->setNormalFont(15);
    $pdf->setTextBlue();

    //$pdf->Cell(0, 5, "Cotización No {$cotizacion->serial}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    //$pdf->Cell(0, 0, "Fecha", 0, 1, 'R', 0, '', 0, false, 'T', 'M');

    $pdf->setBoldFont(14);
    $pdf->Cell(0, 0, "Datos Cliente", 0, 0, 'L', 0, '', 0, false, 'T', 'B');

    $date = date_create($cotizacion->fecha);
    $fecha = date_format($date, 'd/m/Y');
    $pdf->setNormalFont(14);
    $pdf->Cell(0, 0, "{$fecha}", 0, 1, 'R', 0, '', 0, false, 'T', 'M');

    $pdf->Cell(0, 0, "Nombre: {$cotizacion->nombre_contacto}", 0, 1, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(0, 0, "Dirección: {$cliente->nombre}", 0, 1, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(0, 0, "Email: {$cotizacion->correo_contacto}", 0, 1, 'L', 0, '', 0, false, 'T', 'B');
    //$pdf->Cell(0, 0, 'Email Opcional: {}', 0, 1, 'L', 0, '', 0, false, 'T', 'B');

    $pdf->Ln();
}

function imprimirCabeceras() {
    global $pdf;

    $pdf->setTextBlue();
    $pdf->setBoldFont(13);

    // Title
    $pdf->Cell(getAnchoColumna1(), 10, "Item", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(getAnchoColumna2(), 10, "Cantidad", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(getAnchoColumna3(), 10, "Precio Unitario", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->Cell(0, 10, "Precio", 0, 1, 'C', 0, '', 0, false, 'M', 'B');

    $pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(32, 100, 172)));
    $pdf->line(16, 76, 195, 76);
    $pdf->Ln();
}

function imprimirDatosGPS() {
    global $pdf;
    global $cotizacion;
    global $unidadGps;


    $total = $cotizacion->cantidad_vehiculos * $unidadGps->precioUnidad;

    $pdf->setTextBlack();
    $pdf->setBoldFont(14);

    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Tipo Plan:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    // Nombre GPS
    $pdf->setNormalFont(14);
    $pdf->Cell(getAnchoColumna1(), 14, $unidadGps->nombre, 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->setBoldFont(14);
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio Unitario
    $pdf->setNormalFont(14);
    $pdf->Cell(getAnchoColumna3(), 14, "$" . $unidadGps->precioUnidad, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$" . $total, 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $pdf->Ln(5);
}

function imprimirDatosAccesorios() {

    global $pdf;
    global $cotizacionDetalle;
    global $cantidadAccesorios;
    global $valorAccesorios;
    global $totalAccesorios;

    $pdf->setTextBlack();
    $pdf->setBoldFont(14);
    // Title
    $pdf->Cell(getAnchoColumna1(), 5, "Accesorios:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->setNormalFont(14);

    foreach ($cotizacionDetalle as $detalle) {

        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $precioTotal = $detalle->cantidad_accesorio * $accesorio->precioAccesorio;
        $cantidadAccesorios += $detalle->cantidad_accesorio;
        $valorAccesorios += $accesorio->precioAccesorio;
        $totalAccesorios += $precioTotal;
        // Detalle accesorios
        //$pdf->Cell(getAnchoColumna1(), 14, $accesorio->nombre, 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $pdf->MultiCell(getAnchoColumna1(), 14, $accesorio->nombre, 0, 'L', false, 0, '', '', true);

        // Cantidad
        $pdf->setBoldFont(14);
        $pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio Unitario
        $pdf->setNormalFont(14);
        $pdf->Cell(getAnchoColumna3(), 14, "$" . $accesorio->precioAccesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->setNormalFont(14);
        $pdf->Cell(0, 14, '$' . $precioTotal, 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln(5);
}

function imprimirDatosInstalaciones() {
    global $pdf;
    global $cotizacion;
    global $cotizacionDetalle;
    global $unidadGps;
    global $cantidadInstalaciones;
    global $valorInstalaciones;
    global $totalInstalaciones;

    $pdf->setTextBlack();
    $pdf->setBoldFont(14);
    // Title
    $pdf->Cell(getAnchoColumna1(), 18, "Instalaciones:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->setNormalFont(14);
    $pdf->setTextBlack();

    // Detalle instalacion
    $pdf->Cell(getAnchoColumna1(), 14, "Instalacion {$unidadGps->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->setBoldFont(14);
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio Unitario
    $pdf->setNormalFont(14);
    $pdf->Cell(getAnchoColumna3(), 14, "$" . $unidadGps->precioInstalacion, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->setNormalFont(14);
    $precioInstalacionGps = $unidadGps->precioInstalacion * $cotizacion->cantidad_vehiculos;
    $pdf->Cell(0, 14, "$" . $precioInstalacionGps, 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $cantidadInstalaciones = $cotizacion->cantidad_vehiculos;
    $valorInstalaciones = $unidadGps->precioInstalacion;
    $totalInstalaciones = $precioInstalacionGps;

    foreach ($cotizacionDetalle as $detalle) {

        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $precioTotal = $detalle->cantidad_accesorio * $accesorio->precioInstalacion;

        $cantidadInstalaciones += $detalle->cantidad_accesorio;
        $valorInstalaciones += $accesorio->precioInstalacion;
        $totalInstalaciones += $precioTotal;
        // Detalle instalacion
        //$pdf->Cell(getAnchoColumna1(), 14, "Instalacion {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $pdf->MultiCell(getAnchoColumna1(), 14, "Instalacion {$accesorio->nombre}", 0, 'L', false, 0, '', '', true);

        // Cantidad
        $pdf->setBoldFont(14);
        $pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        $pdf->setNormalFont(14);
        // Precio unitario
        $pdf->Cell(getAnchoColumna3(), 14, "$" . $accesorio->precioInstalacion, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->Cell(0, 14, "$" . $precioTotal, 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln(5);
}

function imprimirDatosPlanes() {

    global $pdf;
    global $cotizacion;
    global $cotizacionDetalle;
    global $valorPlan;
    global $totalPlan;

    $plan = PlanesDB::getPlanePorId($cotizacion->plan_servicio_id);
    $precioPlan = $plan->precio * $cotizacion->cantidad_vehiculos;

    $pdf->setTextBlack();
    $pdf->setBoldFont(14);


    // Tipo Plan
    $pdf->Cell(getAnchoColumna1(), 18, "Tipo Plan:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->setNormalFont(14);
    $pdf->setTextBlack();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "{$plan->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->setBoldFont(14);
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->setNormalFont(14);
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$plan->precio}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$precioPlan}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $valorPlan = $plan->precio;

    foreach ($cotizacionDetalle as $detalle) {
        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $precioTotal = $detalle->cantidad_accesorio * $accesorio->precioMensualidad;

        $valorPlan += $accesorio->precioMensualidad;
        $totalPlan += $precioTotal;
        // Detalle plan
        //$pdf->Cell(getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $pdf->MultiCell(getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 'L', false, 0, '', '', true);

        // Cantidad
        $pdf->setBoldFont(14);
        $pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $pdf->setNormalFont(14);
        $pdf->Cell(getAnchoColumna3(), 14, "$ {$accesorio->precioMensualidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->Cell(0, 14, "$ {$precioTotal}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln(6);
}

function imprimirResumenCotizacion() {

    global $pdf;
    global $cotizacion;
    global $descuento;


    //$descuento = DescuentosDB::getDescuentosPorId($cotizacion->descuento_id);
    $tipoContrato = TiposContratoDB::getTiposContratoPorId($cotizacion->tipo_contrato_id);
    $duracionContrato = DuracionesContratoDB::getDuracionContratoPorId($cotizacion->duracion_contrato_id);

    $pdf->setTextBlack();

    // Tipo Plan
    /* $pdf->setBoldFont(14);
      $pdf->Cell(getAnchoColumna1(), 5, "Tipo de Plan", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
      $pdf->setNormalFont(14);
      // Cantidad
      $pdf->Cell(0, 5, "Valor", 0, 1, 'R', 0, '', 0, false, 'M', 'B'); */

    // Tipo Contrato
    $pdf->setBoldFont(14);
    $pdf->Cell(getAnchoColumna1(), 18, "Tipo de Contrato", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 18, $tipoContrato->nombre, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->setNormalFont(14);
    //$pdf->Cell(0, 14, "Valor", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $pdf->setBoldFont(14);
    if ($tipoContrato->id == 2) {
        // Duracion Contrato
        $pdf->Cell(getAnchoColumna1(), 14, "Duración del contrato", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        // Cantidad
        $pdf->Cell(getAnchoColumna2(), 14, "{$duracionContrato->cantidadMeses} Meses", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    }

    // Numero Vehiculos
    $pdf->Cell(getAnchoColumna1(), 14, "Numero Vehiculos", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->setNormalFont(14);
    //$pdf->Cell(0, 14, "Valor", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    // Detalle plan
    $pdf->setBoldFont(14);
    $pdf->Cell(getAnchoColumna1(), 14, "Porcentaje Descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "{$descuento->descuento}%", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
    $pdf->setNormalFont(14);
    //$pdf->Cell(0, 14, "Valor", 0, 0, 'R', 0, '', 0, false, 'M', 'B');

    $pdf->Ln(5);

    // TOTAL
    //$pdf->setBoldFont(14);
    //$pdf->Cell(getAnchoColumna1(), 0, "TOTAL", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
    // Cantidad
    //$pdf->Cell(0, 0, "Valor", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    //$pdf->Ln();
}

function imprimirDescuentos() {

    global $pdf;
    global $cotizacion;
    global $cotizacionDetalle;
    global $descuento;

    global $valorDescuento;
    global $totalDescuento;


    $plan = PlanesDB::getPlanePorId($cotizacion->plan_servicio_id);

    $decuentoUnidad = $plan->precio * ($descuento->descuento / 100);
    $descuentoTotal = $decuentoUnidad * $cotizacion->cantidad_vehiculos;


    $pdf->setTextBlack();
    $pdf->setBoldFont(14);


    // Tipo Plan
    $pdf->Cell(getAnchoColumna1(), 18, "Descuento:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->setNormalFont(14);
    $pdf->setTextBlack();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "{$plan->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->setBoldFont(14);
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->setNormalFont(14);
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$decuentoUnidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$descuentoTotal}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $valorDescuento = $decuentoUnidad;
    $totalDescuento = $descuentoTotal;


    foreach ($cotizacionDetalle as $detalle) {
        $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
        $decuentoUnidad = $accesorio->precioMensualidad * ($descuento->descuento / 100);
        $descuentoTotal = $decuentoUnidad * $detalle->cantidad_accesorio;

        $valorDescuento += $decuentoUnidad;
        $totalDescuento += $descuentoTotal;

        // Detalle plan
        //$pdf->Cell(getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $pdf->MultiCell(getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 'L', false, 0, '', '', true);

        // Cantidad
        $pdf->setBoldFont(14);
        $pdf->Cell(getAnchoColumna2(), 10, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $pdf->setNormalFont(14);
        $pdf->Cell(getAnchoColumna3(), 10, "$ {$decuentoUnidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $pdf->Cell(0, 10, "$ {$descuentoTotal}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
    }

    $pdf->Ln();
}

function imprimirTotales() {
    global $pdf;
    global $cotizacion;
    global $unidadGps;
    global $descuento;
    global $cantidadAccesorios;
    global $cantidadInstalaciones;
    global $valorAccesorios;
    global $valorInstalaciones;
    global $totalAccesorios;
    global $totalInstalaciones;
    global $valorPlan;
    global $totalPlan;
    global $valorDescuento;
    global $totalDescuento;


    $tipoContrato = TiposContratoDB::getTiposContratoPorId($cotizacion->tipo_contrato_id);
    $duracionContrato = DuracionesContratoDB::getDuracionContratoPorId($cotizacion->duracion_contrato_id);

    $valorPlanSinDescuento = $valorPlan;
    $totalPlanSinDescuento = $totalPlan;

    $valorDescuento = $valorDescuento;
    $totalDescuento = $totalDescuento;

    $valorPlanMensual = $valorPlanSinDescuento - $valorDescuento;
    $totalPlanMensual = $totalPlanSinDescuento - $totalDescuento;


    // COMODATO
    $contrato = "";
    if ($tipoContrato->id == 2) {
        $contrato = "COMODATO";

        $valorPlanSinDescuento = ($unidadGps->precioUnidad / $duracionContrato->cantidadMeses) + $valorPlan;
        $totalPlanSinDescuento = $cotizacion->cantidad_vehiculos * $valorPlanSinDescuento;

        $valorDescuento = $duracionContrato->cantidadMeses * ($descuento->descuento / 100);
        $totalDescuento = $cotizacion->cantidad_vehiculos * $valorDescuento;

        $valorPlanMensual = $valorPlanSinDescuento - $valorDescuento;
        $totalPlanMensual = $totalPlanSinDescuento - $totalDescuento;
    }

    $pdf->setTextBlack();
    $pdf->setBoldFont(14);


    // Tipo Plan
    $pdf->Cell(getAnchoColumna1(), 18, "Totales:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

    $pdf->setNormalFont(14);
    $pdf->setTextBlack();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Totales Accesorios", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cantidadAccesorios, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$valorAccesorios}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$totalAccesorios}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');


    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Totales Instalaciones", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cantidadInstalaciones, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$ $valorInstalaciones", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$totalInstalaciones}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $pdf->Ln();

    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Valor Plan {$contrato} sin Descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "{$cotizacion->cantidad_vehiculos}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$valorPlanSinDescuento}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$totalPlanSinDescuento}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');


    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$valorDescuento}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$totalDescuento}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');


    // Detalle plan
    $pdf->Cell(getAnchoColumna1(), 14, "Valor Plan {$contrato} Mensual", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

    // Cantidad
    $pdf->Cell(getAnchoColumna2(), 14, "-", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio unitario
    $pdf->Cell(getAnchoColumna3(), 14, "$ {$valorPlanMensual}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

    // Precio total
    $pdf->Cell(0, 14, "$ {$totalPlanMensual}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

    $pdf->Ln();
}

function getAnchoColumna1() {
    return 85;
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