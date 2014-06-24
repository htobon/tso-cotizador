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
        $this->nombre_archivo = date('Y-m-d') . "-cotizaciones.csv";
        $this->nombre_archivo_detalle = date('Y-m-d') . "-detalles.csv";

        $this->cotizacion_id = $cotizacion_id;
        $this->cotizacion = CotizacionDB::getCotizacion($this->cotizacion_id);
        $this->cotizacionDetalle = CotizacionDB::getDetalleCotizacion($this->cotizacion_id);
    }

    public function generarArchivosPlano() {

        // Generar Cabecera de la Cotización
        $this->ruta = $this->directorio_csv . $this->nombre_archivo;
        $date = date_create($this->cotizacion->fecha);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_cierre = date("t-m-Y", strtotime($fecha));

        $cabecera = array();
        if (!file_exists($this->ruta)) {
            $cabecera = array("Nombre Oportunidad",
                "Etapa",
                "Fecha de Cierre",
                "Tipo Contrato",
                "Tipo de Plan",
                "Nombre de la Cuenta",
                "Numero Unidades",
                "Valor en Recurrencias",
                "Valor Equipos",
                "Valor Total",
                "Id del Propietario");
        }

        $datos = array(
            $this->cotizacion_id,
            "Cotización",
            $fecha_cierre,
            $this->cotizacion->tipo_contrato,
            $this->cotizacion->codigo_plan,
            $this->cotizacion->nit,
            $this->cotizacion->cantidad_vehiculos,
            $this->cotizacion->valor_recurrencia,
            $this->cotizacion->valor_equipos,
            $this->cotizacion->valor_total,
            $this->cotizacion->salesforce_id);

        $fp = fopen($this->ruta, 'a');

        if (!empty($cabecera))
            fputcsv($fp, $cabecera);

        fputcsv($fp, $datos);
        fclose($fp);


        // Generar Detalle de la Cotización
        $this->ruta = $this->directorio_csv . $this->nombre_archivo_detalle;

        $cabecera = array();
        if (!file_exists($this->ruta)) {
            $cabecera = array("Nombre Oportunidad",
                "Codigo de Producto",
                "Cantidad Unidades / Meses Plan",
                "Precio de Venta",
                "Descuento");
        }

        $fp = fopen($this->ruta, 'a');
        if (!empty($cabecera))
            fputcsv($fp, $cabecera);

        foreach ($this->cotizacionDetalle as $value) {
            fputcsv($fp, array($value->cotizacion_id, $value->codigo_accesorio, $value->cantidad_meses, $value->precio_accesorio, $value->descuento));
        }
        fclose($fp);
    }

}
