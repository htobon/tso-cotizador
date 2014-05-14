<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

//print"<pre>"; print_r($_REQUEST);print"</pre>";

class CotizacionDB {

    public static function agregarCotizacion($cotizacion) {

        $conn = getConn();
        $sql = "INSERT INTO tso_cotizaciones (usuario_id,cliente_id,unidad_gps_id,tipo_contrato_id,plan_servicio_id,descuento_id,duracion_contrato_id,cantidad_vehiculos,fecha,serial) 
		VALUES (?,?,?,?,?,?,?,?,CURDATE(),?)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cotizacion['usuario_id']);
        $stmt->bindValue(2, $cotizacion['cliente_id']);
        $stmt->bindValue(3, $cotizacion['unidad_gps_id']);
        $stmt->bindValue(4, $cotizacion['tipo_contrato_id']);
        $stmt->bindValue(5, $cotizacion['plan_servicio_id']);
        $stmt->bindValue(6, $cotizacion['descuento_id']);
        $stmt->bindValue(7, $cotizacion['duracion_contrato_id']);
        $stmt->bindValue(8, $cotizacion['cantidad_vehiculos']);
        $stmt->bindValue(9, $cotizacion['serial']);

        // Exepcion en caso de que el Insert Falle
        try {
            $inserted_rows = $stmt->execute();
            $cotizacion_id = $conn->lastInsertId();

            return $cotizacion_id;
        } catch (\Doctrine\DBAL\DBALException $exc) {
            //echo $exc->getTraceAsString();
            return 0;
        }
    }

    public static function agregarAccesoriosCotizados($cotizacion_id, $cotizacionDetalle) {
        $conn = getConn();

        $sql = "INSERT INTO tso_accesorios_cotizados (cotizacion_id,accesorio_id,cantidad_accesorio) VALUES ";

        for ($i = 0; $i < count($cotizacionDetalle); $i++) {
            $sql .= ($i > 0) ? ", ( {$cotizacion_id}, ?, ? )" : "( {$cotizacion_id}, ?, ?)";
        }

        $stmt = $conn->prepare($sql);

        for ($i = 0, $j = 1; $i < count($cotizacionDetalle); $i++, $j+=2) {
            $stmt->bindValue(($j), $cotizacionDetalle[$i]["id"]);
            $stmt->bindValue(($j + 1), $cotizacionDetalle[$i]["cantidad-accesorio"]);
        }

        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0);
    }

    public static function getCotizacion() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_cotizaciones WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $cotizacion = $stmt->fetchAll();

        return $cotizacion;
    }

    public static function getAccesoriosCotizados() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios_cotizados WHERE cotizacion_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $gpsID);
        $stmt->execute();

        $accesorioCotizado = $stmt->fetchAll();

        return $accesorioCotizado;
    }

    public static function actualizarSerialCotizacion($cotizacion_id, $serial) {
        
        $conn = getConn();
        $sql = "UPDATE tso_cotizaciones set serial= ? where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $serial);
        $stmt->bindValue(2, $cotizacion_id);
        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0) ? true : false;
    }

}

?>