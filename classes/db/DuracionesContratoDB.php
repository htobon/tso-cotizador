<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y 
 * encontrar información de las duraciones de los contratos.
 *
 * @author hdcarvajal
 */
class DuracionesContratoDB {

    /**
     * getDuracionesContrato retorna las duraciones de contrato almacenados en la bd
     * @return array(StdClass) arreglo con clases DuracionContrato con la info
     */
    public static function getDuracionesContratoActivas() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_duracion_contratos WHERE esta_activo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $duracionContratoData = $stmt->fetchAll();
        $duracionesContrato = array();
        foreach ($duracionContratoData as $dc ) {
            if(isset($dc)){
                $duracionContrato = new stdClass();
                $duracionContrato->id = $dc["id"];
                $duracionContrato->cantidadMeses = $dc["cantidad_meses"];
                array_push($duracionesContrato, $duracionContrato);
            }
        }
        return (count($duracionesContrato) <= 0 ) ? null : $duracionesContrato;
    }
}
