<?php

require_once __DIR__ . "/../../../pages/basePDF.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\CotizacionDB;
use db\ClienteDB;
use db\UnidadesGpsDB;
use db\AccesoriosDB;
use db\PlanesDB;
use db\DescuentosDB;
use db\TiposContratoDB;
use db\DuracionesContratoDB;

class generarPdf {

    private $pdf;
    private $cotizacion_id;
    private $cotizacion;
    private $cliente;
    private $cotizacionDetalle;
    private $accesoriosCotizados;
    private $unidadGps;
    private $descuento;
    private $tipoContrato;
    private $duracionContrato;
    private $plan;
    private $cantidadAccesorios = 0;
    private $valorAccesorios = 0;
    private $totalAccesorios = 0;
    private $cantidadInstalaciones = 0;
    private $valorInstalaciones = 0;
    private $totalInstalaciones = 0;
    private $valorPlan = 0;
    private $totalPlan = 0;
    private $valorDescuento = 0;
    private $totalDescuento = 0;

    public function __construct($id) {
        $this->cotizacion_id = $id;
        $this->pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }

    public function generarCotizacionPdf() {

        $this->cotizacion = CotizacionDB::getCotizacion($this->cotizacion_id);
        $this->cotizacionDetalle = CotizacionDB::getAccesoriosCotizados($this->cotizacion_id);
        $this->cliente = ClienteDB::getClientePorId($this->cotizacion->cliente_id);
        $this->unidadGps = UnidadesGpsDB::getUnidadesGpsPorId($this->cotizacion->unidad_gps_id);
        $this->plan = PlanesDB::getPlanePorId($this->cotizacion->plan_servicio_id);
        $this->descuento = DescuentosDB::getDescuentosPorId($this->cotizacion->descuento_id);
        $this->tipoContrato = TiposContratoDB::getTiposContratoPorId($this->cotizacion->tipo_contrato_id);
        $this->duracionContrato = DuracionesContratoDB::getDuracionContratoPorId($this->cotizacion->duracion_contrato_id);

        /* echo "<pre>";
          print_r($this->tipoContrato);
          echo "</pre>";
          exit(); */

        foreach ($this->cotizacionDetalle as $detalle) {
            $accesorio = AccesoriosDB::getAccesoriosPorId($detalle->accesorio_id);
            $accesorio->cantidad_accesorio = $detalle->cantidad_accesorio;

            $this->accesoriosCotizados[] = $accesorio;
        }

        $this->createDocument();
        $this->imprimirDatosCliente();
        $this->imprimirCabeceras();
        $this->imprimirDatosGPS();
        $this->imprimirDatosAccesorios();
        $this->imprimirDatosInstalaciones();
        $this->imprimirDatosPlanes();
        $this->imprimirResumenCotizacion();
        $this->imprimirDescuentos();
        $this->imprimirTotales();
    }

    public function getNamePdf() {
        return "cotizacion-{$this->cotizacion->serial}.pdf";
    }

    public function getPdf() {
        return $this->pdf->Output(__DIR__ . "/../../../tmp/cotizacion-{$this->cotizacion->serial}.pdf", 'F');
    }

    public function getPdfbase64() {
        return $this->pdf->Output('example_003.pdf', 'E');
    }

    private function createDocument() {
        // set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('TSO Mobile');
        $this->pdf->SetTitle('Cotizacion');
        $this->pdf->SetSubject('Cotizacion');

        // set default header data
        $this->pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $this->pdf->SetFont('times', 'BI', 12);

        // add a page
        $this->pdf->AddPage();
    }

    private function imprimirDatosCliente() {


        $this->pdf->setNormalFont(15);
        $this->pdf->setTextBlue();

        //$this->pdf->Cell(0, 5, "Cotización No {$cotizacion->serial}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        //$this->pdf->Cell(0, 0, "Fecha", 0, 1, 'R', 0, '', 0, false, 'T', 'M');

        $this->pdf->setBoldFont(14);
        $this->pdf->Cell(0, 0, "Datos Cliente", 0, 0, 'L', 0, '', 0, false, 'T', 'B');

        $date = date_create($this->cotizacion->fecha);
        $fecha = date_format($date, 'd/m/Y');
        $this->pdf->setNormalFont(14);
        $this->pdf->Cell(0, 0, "{$fecha}", 0, 1, 'R', 0, '', 0, false, 'T', 'M');

        $this->pdf->Cell(0, 0, "Nombre: {$this->cotizacion->nombre_contacto}", 0, 1, 'L', 0, '', 0, false, 'T', 'B');
        $this->pdf->Cell(0, 0, "Dirección: {$this->cliente->nombre}", 0, 1, 'L', 0, '', 0, false, 'T', 'B');
        $this->pdf->Cell(0, 0, "Email: {$this->cotizacion->correo_contacto}", 0, 1, 'L', 0, '', 0, false, 'T', 'B');
        //$this->pdf->Cell(0, 0, 'Email Opcional: {}', 0, 1, 'L', 0, '', 0, false, 'T', 'B');

        $this->pdf->Ln();
    }

    private function imprimirCabeceras() {


        $this->pdf->setTextBlue();
        $this->pdf->setBoldFont(13);

        // Title
        $this->pdf->Cell($this->getAnchoColumna1(), 10, "Item", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->pdf->Cell($this->getAnchoColumna2(), 10, "Cantidad", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->Cell($this->getAnchoColumna3(), 10, "Precio Unitario", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->Cell(0, 10, "Precio", 0, 1, 'C', 0, '', 0, false, 'M', 'B');

        $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(32, 100, 172)));
        $this->pdf->line(16, 76, 195, 76);
        $this->pdf->Ln();
    }

    private function imprimirDatosGPS() {

        $total = $this->cotizacion->cantidad_vehiculos * $this->unidadGps->precioUnidad;

        $this->pdf->setTextBlack();
        $this->pdf->setBoldFont(14);

        // Title
        $this->pdf->Cell($this->getAnchoColumna1(), 5, "Unidad GPS", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        // Nombre GPS
        $this->pdf->setNormalFont(14);
        $this->pdf->Cell($this->getAnchoColumna1(), 14, $this->unidadGps->nombre, 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        $this->pdf->setBoldFont(14);
        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, $this->cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio Unitario
        $this->pdf->setNormalFont(14);
        $this->pdf->Cell($this->getAnchoColumna3(), 14, "$" . $this->unidadGps->precioUnidad, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $this->pdf->Cell(0, 14, "$" . $total, 0, 1, 'R', 0, '', 0, false, 'M', 'B');

        $this->pdf->Ln(5);
    }

    private function imprimirDatosAccesorios() {


        $this->pdf->setTextBlack();
        $this->pdf->setBoldFont(14);
        // Title
        $this->pdf->Cell($this->getAnchoColumna1(), 5, "Accesorios:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');
        /**/
        /**/
        $this->pdf->setNormalFont(14);

        foreach ($this->accesoriosCotizados as $accesorio) {

            //$accesorio = AccesoriosDB::getAccesoriosPorId($accesorio->accesorio_id);
            $precioTotal = $accesorio->cantidad_accesorio * $accesorio->precioAccesorio;

            $this->cantidadAccesorios += $accesorio->cantidad_accesorio;
            $this->valorAccesorios += $accesorio->precioAccesorio;
            $this->totalAccesorios += $precioTotal;

            $h = 7;
            if (strlen($accesorio->nombre) > 30)
                $h = 13;

            // Detalle accesorios
            //$this->pdf->Cell($this->getAnchoColumna1(), 14, $accesorio->nombre, 0, 0, 'L', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna1(), $h, $accesorio->nombre, 0, 'L', false, 0, '', '', true);

            // Cantidad
            $this->pdf->setBoldFont(14);
            //$this->pdf->Cell($this->getAnchoColumna2()(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna2(), $h, $accesorio->cantidad_accesorio, 0, 'C', false, 0, '', '', true);

            // Precio Unitario
            $this->pdf->setNormalFont(14);
            //$this->pdf->Cell($this->getAnchoColumna3()(), 14, "$" . $accesorio->precioAccesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna3(), $h, "$" . $accesorio->precioAccesorio, 0, 'C', false, 0, '', '', true);

            // Precio total
            $this->pdf->setNormalFont(14);
            //$this->pdf->Cell(0, 14, '$' . $precioTotal, 0, 1, 'R', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell(0, $h, '$' . $precioTotal, 0, 'R', false, 1, '', '', true);
        }

        //$this->pdf->Ln(5);
    }

    private function imprimirDatosInstalaciones() {

        $this->pdf->setTextBlack();
        $this->pdf->setBoldFont(14);
        // Title
        $this->pdf->Cell($this->getAnchoColumna1(), 5, "Instalaciones:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        $this->pdf->setNormalFont(14);
        $this->pdf->setTextBlack();

        // Detalle instalacion
        //$this->pdf->Cell($this->getAnchoColumna1(), 7, "Instalacion {$unidadGps->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna1(), 7, "Instalación {$this->unidadGps->nombre}", 0, 'L', false, 0, '', '', true);

        // Cantidad
        $this->pdf->setBoldFont(14);
        //$this->pdf->Cell($this->getAnchoColumna2()(), 7, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna2(), 7, $this->cotizacion->cantidad_vehiculos, 0, 'C', false, 0, '', '', true);

        // Precio Unitario
        $this->pdf->setNormalFont(14);
        //$this->pdf->Cell($this->getAnchoColumna3()(), 7, "$" . $unidadGps->precioInstalacion, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna3(), 7, "$" . $this->unidadGps->precioInstalacion, 0, 'C', false, 0, '', '', true);

        // Precio total
        $this->pdf->setNormalFont(14);
        $precioInstalacionGps = $this->unidadGps->precioInstalacion * $this->cotizacion->cantidad_vehiculos;
        //$this->pdf->Cell(0, 14, "$" . $precioInstalacionGps, 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell(0, 7, '$' . $precioInstalacionGps, 0, 'R', false, 1, '', '', true);

        $this->cantidadInstalaciones = $this->cotizacion->cantidad_vehiculos;
        $this->valorInstalaciones = $this->unidadGps->precioInstalacion;
        $this->totalInstalaciones = $precioInstalacionGps;

        foreach ($this->accesoriosCotizados as $accesorio) {

            //$accesorio = AccesoriosDB::getAccesoriosPorId($accesorio->accesorio_id);
            $precioTotal = $accesorio->cantidad_accesorio * $accesorio->precioInstalacion;

            $this->cantidadInstalaciones += $accesorio->cantidad_accesorio;
            $this->valorInstalaciones += $accesorio->precioInstalacion;
            $this->totalInstalaciones += $precioTotal;

            $h = 7;
            if (strlen("Instalacion {$accesorio->nombre}") > 36)
                $h = 13;

            // Detalle instalacion
            //$this->pdf->Cell($this->getAnchoColumna1(), 14, "Instalacion {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna1(), $h, "Instalación {$accesorio->nombre}", 0, 'L', false, 0, '', '', true);

            // Cantidad
            $this->pdf->setBoldFont(14);
            //$this->pdf->Cell($this->getAnchoColumna2()(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna2(), $h, $accesorio->cantidad_accesorio, 0, 'C', false, 0, '', '', true);

            $this->pdf->setNormalFont(14);
            // Precio unitario
            //$this->pdf->Cell($this->getAnchoColumna3()(), 14, "$" . $accesorio->precioInstalacion, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna3(), $h, "$" . $accesorio->precioInstalacion, 0, 'C', false, 0, '', '', true);

            // Precio total
            //$this->pdf->Cell(0, 14, "$" . $precioTotal, 0, 1, 'R', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell(0, $h, '$' . $precioTotal, 0, 'R', false, 1, '', '', true);
        }

        //$this->pdf->Ln(5);
    }

    private function imprimirDatosPlanes() {

        $precioPlan = $this->plan->precio * $this->cotizacion->cantidad_vehiculos;

        $this->pdf->setTextBlack();
        $this->pdf->setBoldFont(14);


        // Tipo Plan
        $this->pdf->Cell($this->getAnchoColumna1(), 18, "Tipo Plan:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        $this->pdf->setNormalFont(14);
        $this->pdf->setTextBlack();

        // Detalle plan
        //$this->pdf->Cell($this->getAnchoColumna1(), 14, "{$plan->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna1(), 7, "{$this->plan->nombre}", 0, 'L', false, 0, '', '', true);

        // Cantidad
        $this->pdf->setBoldFont(14);
        //$this->pdf->Cell($this->getAnchoColumna2()(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna2(), 7, $this->cotizacion->cantidad_vehiculos, 0, 'C', false, 0, '', '', true);

        // Precio unitario
        $this->pdf->setNormalFont(14);
        //$this->pdf->Cell($this->getAnchoColumna3()(), 14, "$ {$plan->precio}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna3(), 7, "$ {$this->plan->precio}", 0, 'C', false, 0, '', '', true);

        // Precio total
        //$this->pdf->Cell(0, 14, "$ {$precioPlan}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell(0, 7, '$' . $precioPlan, 0, 'R', false, 1, '', '', true);

        $this->valorPlan = $this->plan->precio;
        $this->totalPlan = $precioPlan;

        foreach ($this->accesoriosCotizados as $accesorio) {
            //$accesorio = AccesoriosDB::getAccesoriosPorId($accesorio->accesorio_id);
            $precioTotal = $accesorio->cantidad_accesorio * $accesorio->precioMensualidad;

            $this->valorPlan += $accesorio->precioMensualidad;
            $this->totalPlan += $precioTotal;

            $h = 7;
            if (strlen("Mensualidad {$accesorio->nombre}") > 36)
                $h = 13;

            // Detalle plan
            //$this->pdf->Cell($this->getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna1(), $h, "Mensualidad {$accesorio->nombre}", 0, 'L', false, 0, '', '', true);

            // Cantidad
            $this->pdf->setBoldFont(14);
            //$this->pdf->Cell($this->getAnchoColumna2()(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna2(), $h, $accesorio->cantidad_accesorio, 0, 'C', false, 0, '', '', true);

            // Precio unitario
            $this->pdf->setNormalFont(14);
            //$this->pdf->Cell($this->getAnchoColumna3()(), 14, "$ {$accesorio->precioMensualidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna3(), $h, "$ {$accesorio->precioMensualidad}", 0, 'C', false, 0, '', '', true);

            // Precio total
            //$this->pdf->Cell(0, 14, "$ {$precioTotal}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell(0, $h, '$' . $precioTotal, 0, 'R', false, 1, '', '', true);
        }

        //$this->pdf->Ln();
    }

    private function imprimirResumenCotizacion() {

        $this->pdf->setTextBlack();

        // Tipo Contrato
        $this->pdf->setBoldFont(14);
        $this->pdf->Cell($this->getAnchoColumna1(), 18, "Tipo de Contrato", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 18, $this->tipoContrato->nombre, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->setNormalFont(14);
        //$this->pdf->Cell(0, 14, "Valor", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

        $this->pdf->setBoldFont(14);
        // COMODATO
        if ($this->tipoContrato->id == 1) {
            // Duracion Contrato
            $this->pdf->Cell($this->getAnchoColumna1(), 14, "Duración del contrato", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
            // Cantidad
            $this->pdf->Cell($this->getAnchoColumna2(), 14, "{$this->duracionContrato->cantidadMeses} Meses", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
        }

        // Numero Vehiculos
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Numero Vehiculos", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, $this->cotizacion->cantidad_vehiculos, 0, 1, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->setNormalFont(14);
        //$this->pdf->Cell(0, 14, "Valor", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        // Detalle plan
        $this->pdf->setBoldFont(14);
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Porcentaje Descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, "{$this->descuento->descuento}%", 0, 1, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->setNormalFont(14);
        //$this->pdf->Cell(0, 14, "Valor", 0, 0, 'R', 0, '', 0, false, 'M', 'B');

        $this->pdf->Ln();
    }

    private function imprimirDescuentos() {


        $decuentoUnidad = $this->plan->precio * ($this->descuento->descuento / 100);
        $descuentoTotal = $decuentoUnidad * $this->cotizacion->cantidad_vehiculos;

        $this->pdf->setTextBlack();
        $this->pdf->setBoldFont(14);

        // Tipo Plan
        $this->pdf->Cell($this->getAnchoColumna1(), 18, "Descuento:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        $this->pdf->setNormalFont(14);
        $this->pdf->setTextBlack();

        // Detalle plan
        //$this->pdf->Cell(getAnchoColumna1(), 14, "{$plan->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna1(), 7, "{$this->plan->nombre}", 0, 'L', false, 0, '', '', true);

        // Cantidad
        $this->pdf->setBoldFont(14);
        //$this->pdf->Cell(getAnchoColumna2(), 14, $cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna2(), 7, $this->cotizacion->cantidad_vehiculos, 0, 'C', false, 0, '', '', true);

        // Precio unitario
        $this->pdf->setNormalFont(14);
        //$this->pdf->Cell(getAnchoColumna3(), 14, "$ {$decuentoUnidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell($this->getAnchoColumna3(), 7, "$ {$decuentoUnidad}", 0, 'C', false, 0, '', '', true);

        // Precio total
        //$this->pdf->Cell(0, 14, "$ {$descuentoTotal}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        $this->pdf->MultiCell(0, 7, '$' . $descuentoTotal, 0, 'R', false, 1, '', '', true);

        $this->valorDescuento = $decuentoUnidad;
        $this->totalDescuento = $descuentoTotal;


        foreach ($this->accesoriosCotizados as $accesorio) {

            //$accesorio = AccesoriosDB::getAccesoriosPorId($accesorio->accesorio_id);
            $decuentoUnidad = $accesorio->precioMensualidad * ($this->descuento->descuento / 100);
            $descuentoTotal = $decuentoUnidad * $accesorio->cantidad_accesorio;

            $this->valorDescuento += $decuentoUnidad;
            $this->totalDescuento += $descuentoTotal;

            $h = 7;
            if (strlen("Mensualidad {$accesorio->nombre}") > 36)
                $h = 13;

            // Detalle plan
            //$this->pdf->Cell(getAnchoColumna1(), 14, "Mensualidad {$accesorio->nombre}", 0, 0, 'L', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna1(), $h, "Mensualidad {$accesorio->nombre}", 0, 'L', false, 0, '', '', true);

            // Cantidad
            $this->pdf->setBoldFont(14);
            //$this->pdf->Cell(getAnchoColumna2(), 14, $detalle->cantidad_accesorio, 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna2(), $h, $accesorio->cantidad_accesorio, 0, 'C', false, 0, '', '', true);

            // Precio unitario
            $this->pdf->setNormalFont(14);
            //$this->pdf->Cell(getAnchoColumna3(), 14, "$ {$decuentoUnidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell($this->getAnchoColumna3(), $h, "$ {$decuentoUnidad}", 0, 'C', false, 0, '', '', true);

            // Precio total
            //$this->pdf->Cell(0, 14, "$ {$descuentoTotal}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
            $this->pdf->MultiCell(0, $h, '$' . $descuentoTotal, 0, 'R', false, 1, '', '', true);
        }

        //$this->pdf->Ln();
    }

    private function imprimirTotales() {

        $valorPlanSinDescuento = $this->valorPlan;
        $totalPlanSinDescuento = $this->totalPlan;

        $valorDescuento = $this->valorDescuento;
        $totalDescuento = $this->totalDescuento;

        $valorPlanMensual = $valorPlanSinDescuento - $valorDescuento;
        $totalPlanMensual = $totalPlanSinDescuento - $totalDescuento;


        // COMODATO
        $contrato = "";
        if ($this->tipoContrato->id == 1) {
            $contrato = "COMODATO";

            $valorPlanSinDescuento = ($this->unidadGps->precioUnidad / $this->duracionContrato->cantidadMeses) + $this->valorPlan;
            $totalPlanSinDescuento = $this->cotizacion->cantidad_vehiculos * $valorPlanSinDescuento;

            $valorDescuento = $valorPlanSinDescuento * ($this->descuento->descuento / 100);
            $totalDescuento = $this->cotizacion->cantidad_vehiculos * $valorDescuento;

            $valorPlanMensual = $valorPlanSinDescuento - $valorDescuento;
            $totalPlanMensual = $totalPlanSinDescuento - $totalDescuento;
        }

        $valorPlanSinDescuento = number_format($valorPlanSinDescuento, 2, ",", ".");
        $totalPlanSinDescuento = number_format($totalPlanSinDescuento, 2, ",", ".");
                
        $valorDescuento = number_format($valorDescuento, 2, ",", ".");
        $totalDescuento = number_format($totalDescuento, 2, ",", ".");

        $valorPlanMensual = number_format($valorPlanMensual, 2, ",", ".");
        $totalPlanMensual = number_format($totalPlanMensual, 2, ",", ".");


        $this->pdf->setTextBlack();
        $this->pdf->setBoldFont(14);


        // Tipo Plan
        $this->pdf->Cell($this->getAnchoColumna1(), 18, "Totales:", 0, 1, 'L', 0, '', 0, false, 'M', 'B');

        $this->pdf->setNormalFont(14);
        $this->pdf->setTextBlack();

        if ($this->tipoContrato->id == 2) {
            // COMPRA
            $totalGps = $this->cotizacion->cantidad_vehiculos * $this->unidadGps->precioUnidad;

            // Detalle plan
            $this->pdf->Cell($this->getAnchoColumna1(), 14, "Total Unidad GPS ", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

            // Cantidad
            $this->pdf->Cell($this->getAnchoColumna2(), 14, $this->cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

            // Precio unitario
            $this->pdf->Cell($this->getAnchoColumna3(), 14, "$ {$this->unidadGps->precioUnidad}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

            // Precio total
            $this->pdf->Cell(0, 14, "$ {$totalGps}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');
        }

        // Detalle plan
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Totales Accesorios", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, $this->cantidadAccesorios, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $this->pdf->Cell($this->getAnchoColumna3(), 14, "$ {$this->valorAccesorios}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $this->pdf->Cell(0, 14, "$ {$this->totalAccesorios}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');


        // Detalle plan
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Totales Instalaciones", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, $this->cantidadInstalaciones, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $this->pdf->Cell($this->getAnchoColumna3(), 14, "$ $this->valorInstalaciones", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $this->pdf->Cell(0, 14, "$ {$this->totalInstalaciones}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

        $this->pdf->Ln();

        // Detalle plan
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Valor Plan {$contrato} sin Descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, "{$this->cotizacion->cantidad_vehiculos}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $this->pdf->Cell($this->getAnchoColumna3(), 14, "$ {$valorPlanSinDescuento}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $this->pdf->Cell(0, 14, "$ {$totalPlanSinDescuento}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');


        // Detalle plan
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Descuento", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, $this->cotizacion->cantidad_vehiculos, 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $this->pdf->Cell($this->getAnchoColumna3(), 14, "$ {$valorDescuento}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $this->pdf->Cell(0, 14, "$ {$totalDescuento}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');


        // Detalle plan
        $this->pdf->Cell($this->getAnchoColumna1(), 14, "Valor Plan {$contrato} Mensual", 0, 0, 'L', 0, '', 0, false, 'M', 'B');

        // Cantidad
        $this->pdf->Cell($this->getAnchoColumna2(), 14, "-", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio unitario
        $this->pdf->Cell($this->getAnchoColumna3(), 14, "$ {$valorPlanMensual}", 0, 0, 'C', 0, '', 0, false, 'M', 'B');

        // Precio total
        $this->pdf->Cell(0, 14, "$ {$totalPlanMensual}", 0, 1, 'R', 0, '', 0, false, 'M', 'B');

        $this->pdf->Ln();
    }

    private function getAnchoColumna1() {
        return 85;
    }

    private function getAnchoColumna2() {
        return 30;
    }

    private function getAnchoColumna3() {
        return 35;
    }

    private function getAnchoColumna4() {
        return 35;
    }

}
