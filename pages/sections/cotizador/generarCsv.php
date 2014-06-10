<?php

require_once __DIR__ . "/../../../pages/basePDF.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\CotizacionDB;

class generarCsv {

    private $directorio_csv;
    private $nombre_archivo;
    private $ruta;
    private $cotizacion_id;
    private $cotizacion;

    function __construct($cotizacion_id) {
        $this->directorio_csv = $_SERVER['DOCUMENT_ROOT'] . "/tmp/csv/";
        $this->nombre_archivo = date('d-m-Y') . ".csv";
        $this->ruta = $this->directorio_csv . $this->nombre_archivo;

        $this->cotizacion_id = $cotizacion_id;
        $this->cotizacion = CotizacionDB::getCotizacion($this->cotizacion_id);
        $this->cotizacionDetalle = CotizacionDB::getAccesoriosCotizados($this->cotizacion_id);
    }

    public function generarArchivosPlano() {

        $date = date_create($this->cotizacion->fecha);
        $fecha = date_format($date, 'd/m/Y');
        $fecha_cierre = date("t-m-Y", strtotime($fecha));

        $datos = array(
            $this->cotizacion_id,
            "150000",
            $fecha_cierre,
            $this->cotizacion->tipo_contrato,
            $this->cotizacion->codigo_plan,
            $this->cotizacion->nit,
            $this->cotizacion->cantidad_vehiculos,
            "2500000",
            "3500000",
            "6500000",
            $this->cotizacion->salesforce_id);

        $fp = fopen($this->ruta, 'a');
        fputcsv($fp, $datos);
        fclose($fp);
    }

}
