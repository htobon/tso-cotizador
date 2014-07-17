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
        foreach ($duracionContratoData as $dc) {
            if (isset($dc)) {
                $duracionContrato = new stdClass();
                $duracionContrato->id = $dc["id"];
                $duracionContrato->cantidadMeses = $dc["cantidad_meses"];
                array_push($duracionesContrato, $duracionContrato);
            }
        }
        return (count($duracionesContrato) <= 0 ) ? null : $duracionesContrato;
    }

    public static function getDuracionContratoPorId($id) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_duracion_contratos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $dc = $stmt->fetch();

        if (isset($dc)) {
            $duracionContrato = new stdClass();
            $duracionContrato->id = $dc["id"];
            $duracionContrato->cantidadMeses = $dc["cantidad_meses"];
            return $duracionContrato;
        } else {
            return null;
        }
    }

    public static function agregarDuracionContrato($duracion_contrato) {

        $conn = getConn();
        
        unset($duracion_contrato["id"]);
        
        $conn->insert("tso_duracion_contratos", $duracion_contrato);
        return (count($duracion_contrato) <= 0 ) ? null : $duracion_contrato;
    }

    public static function modificarDuracionContrato($duracion_contrato) {
        
        $conn = getConn();
        // Insertamos el nuevo registro
        $std = self::agregarDuracionContrato($duracion_contrato);
        // Desactivamos el plan actual
        $conn->update("tso_duracion_contratos", array('esta_activo' => false), array('id' => $duracion_contrato["id"]));

        return (array) $std; 
    }

    public static function inactivarDuracionContrato($id) {
        
        $conn = getConn();
        $sql = "UPDATE tso_duracion_contratos SET esta_activo = FALSE WHERE id = ?";
        $values = array($id);

        $count = $conn->executeUpdate($sql, $values);

        return ($count > 0) ? true : false;
        
    }

}

