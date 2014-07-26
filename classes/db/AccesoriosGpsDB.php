<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y 
 * manejar la información de la tabla tso_gps_accesorios_restricciones
 *
 * @author hdcarvajal
 */
class AccesoriosGpsDB {
    /*
     * Busca todos las restricciones entre accesorios y gps.
     */

    public static function getAccesoriosGpsRestricciones() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_gps_accesorios_restricciones";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $restriccionesAG = $stmt->fetchAll();

        return $restriccionesAG;
    }

    /**
     * Obtiene las restricciones que relacionen el accesorio recibido
     * @param  [int] $accesorioID identificador del accesorio
     * @return [arrray]  arreglo con las restricciones o arreglo vacio
     *                   si no hay ninguna restricción.
     */
    public static function getRestriccionesPorAccesorio($accesorioID) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_gps_accesorios_restricciones WHERE accesorio_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $accesorioID);
        $stmt->execute();

        $restriccionesAG = $stmt->fetchAll();

        // Eliminamos el valor del id del arreglo
        for ($i = 0; $i < count($restriccionesAG); $i++) {
            unset($restriccionesAG[$i]["id"]);
        }

        return $restriccionesAG;
    }

    /**
     * Obtiene las restricciones que relacionen la unidad de gps recibida
     * @param  [int] $gpsID identificador de la unidad gps
     * @return [arrray]  arreglo con las restricciones o arreglo vacio
     *                   si no hay ninguna restricción.
     */
    public static function getRestriccionesPorGPS($gpsID) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_gps_accesorios_restricciones WHERE unidad_gps_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $gpsID);
        $stmt->execute();

        $restriccionesAG = $stmt->fetchAll();

        return $restriccionesAG;
    }

    /**
     * Esta funcion agrega restricciones entre accesorios 
     * y unidades gps a la base de date_offset_get()
     * 
     * @param  [array] $restricciones arreglo de arreglos asociativos de formato: 
     *                 ("unidad_gps_id" => valor, "accesorio_id" => valor)
     * @return [bool]  true si la restriccion se agrego correctamente, false si no
     */
    public static function agregarRestricciones($restricciones) {
        $conn = getConn();
        $sql = "INSERT INTO tso_gps_accesorios_restricciones (unidad_gps_id, accesorio_id) VALUES ";

        $i = 0;
        foreach ($restricciones as $restriccion) {
            $sql .= ($i > 0) ? ", ( ?, ? )" : "( ?, ? )";
            $i++;
        }


        $stmt = $conn->prepare($sql);
        for ($i = 0, $j = 1; $i < count($restricciones); $i++, $j+=2) {
            $stmt->bindValue(($j), $restricciones[$i]["unidad_gps_id"]);
            $stmt->bindValue(($j + 1), $restricciones[$i]["accesorio_id"]);
        }

        $inserted_rows = $stmt->execute();
        return ($inserted_rows > 0);
    }

    public static function eliminarRestriccionesPorAccesorio($accesorio_id) {

        $conn = getConn();
        $sql = " DELETE FROM tso_gps_accesorios_restricciones WHERE accesorio_id = ? ;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $accesorio_id);
        $count = $stmt->execute();
        return ($count > 0) ? true : false;
    }

}
