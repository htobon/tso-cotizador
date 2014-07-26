<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y 
 * manejar la información de la tabla tso_accesorios_planes_restricciones
 *
 * @author hdcarvajal
 */
class AccesoriosPlanesDB {
    /*
     * Busca todos las restricciones entre accesorios y planes.
     */

    public static function getAccesoriosPlanesRestricciones() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios_planes_restricciones";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $restriccionesAP = $stmt->fetchAll();

        return $restriccionesAP;
    }

    /**
     * Obtiene las restricciones que relacionen el accesorio recibido
     * @param  [int] $accesorioID identificador del accesorio
     * @return [arrray]  arreglo con las restricciones o arreglo vacio
     *                   si no hay ninguna restricción.
     */
    public static function getRestriccionesPorAccesorio($accesorioID) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios_planes_restricciones WHERE accesorio_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $accesorioID);
        $stmt->execute();

        $restriccionesAP = $stmt->fetchAll();

        // Eliminamos el valor del id del arreglo
        for ($i = 0; $i < count($restriccionesAP); $i++) {
            unset($restriccionesAP[$i]["id"]);
        }

        return $restriccionesAP;
    }

    /**
     * Obtiene las restricciones que relacionen el plan de servicio recibido
     * @param  [int] $planID identificador del plan de servicio
     * @return [arrray]  arreglo con las restricciones o arreglo vacio
     *                   si no hay ninguna restricción.
     */
    public static function getRestriccionesPorPlan($planID) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios_planes_restricciones WHERE planes_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $planID);
        $stmt->execute();

        $restriccionesAP = $stmt->fetchAll();

        return $restriccionesAP;
    }

    /**
     * Esta funcion agrega restricciones entre accesorios 
     * y planes de servicio a la base de datos
     * 
     * @param  [array] $restricciones arreglo de arreglos asociativos de formato: 
     *                 ("accesorio_id" => valor, "planes_id" => valor)
     * @return [bool]  true si la restriccion se agrego correctamente, false si no
     */
    public static function agregarRestricciones($restricciones) {
        $conn = getConn();
        $sql = "INSERT INTO tso_accesorios_planes_restricciones (accesorio_id, planes_id) VALUES ";

        for ($i = 0; $i < count($restricciones); $i++) {
            $sql .= ($i > 0) ? ", ( ?, ? )" : "( ?, ? )";
        }


        $stmt = $conn->prepare($sql);
        for ($i = 0, $j = 1; $i < count($restricciones); $i++, $j+=2) {
            $stmt->bindValue(($j), $restricciones[$i]["accesorio_id"]);
            $stmt->bindValue(($j + 1), $restricciones[$i]["planes_id"]);
        }

        $inserted_rows = $stmt->execute();
        return ($inserted_rows > 0);
    }

    public static function eliminarRestriccionesPorAccesorio($accesorio_id) {

        $conn = getConn();
        $sql = " DELETE FROM tso_accesorios_planes_restricciones WHERE accesorio_id = ? ;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $accesorio_id);
        $count = $stmt->execute();
        return ($count > 0) ? true : false;
    }

}
