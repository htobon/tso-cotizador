<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y encontrar información de los gps.
 *
 * @author hdcarvajal
 */
class UnidadesGpsDB {

    /**
     * [getUnidadesGps Retorna todas las unidades gps activas presentes en la bd]
     * @return array(stdClass) Un arreglo de clases unidadGPS con la informacion
     *                             o null si no hay unidades en la bd.
     */
    static function getUnidadesGpsActivas() {
        $conn = getConn();
        $sql = "SELECT *, 
                CONCAT('$', FORMAT(precio_unidad, 2)) as formato_precio_unidad,
                CONCAT('$', FORMAT(precio_instalacion, 2)) as formato_precio_instalacion FROM tso_unidades_gps WHERE esta_activo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $gpsData = $stmt->fetchAll();
        $unidadesGps = array();

        foreach ($gpsData as $gps) {
            if (isset($gps)) {
                $unidadGps = new stdClass();
                $unidadGps->id = $gps["id"];
                $unidadGps->nombre = $gps["nombre"];
                $unidadGps->codUnidad = $gps["cod_unidad"];
                $unidadGps->codInstalacion = $gps["cod_instalacion"];
                $unidadGps->formato_precio_instalacion = $gps["formato_precio_instalacion"];
                $unidadGps->precioInstalacion = $gps["precio_instalacion"];
                $unidadGps->precioUnidad = $gps["precio_unidad"];
                $unidadGps->formato_precio_unidad = $gps["formato_precio_unidad"];
                $unidadGps->descripcion = $gps["descripcion"];
                $unidadGps->image = $gps["image"];
                $unidadGps->esta_activo = $gps["esta_activo"];
                array_push($unidadesGps, $unidadGps);
            }
        }

        return (count($unidadesGps) <= 0 ) ? null : $unidadesGps;
    }

    static function getUnidadesGpsPorId($id) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_unidades_gps WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $gps = $stmt->fetch();

        if (isset($gps)) {
            $unidadGps = new stdClass();
            $unidadGps->id = $gps["id"];
            $unidadGps->nombre = $gps["nombre"];
            $unidadGps->codUnidad = $gps["cod_unidad"];
            $unidadGps->codInstalacion = $gps["cod_instalacion"];
            $unidadGps->precioInstalacion = $gps["precio_instalacion"];
            $unidadGps->precioUnidad = $gps["precio_unidad"];
            $unidadGps->descripcion = $gps["descripcion"];
            $unidadGps->image = $gps["image"];

            return $unidadGps;
        } else {
            return null;
        }
    }

    /**
     * desactivarUnidad Desactiva la unidad gps indentificada con el 
     * parametro recibido.
     * @param  $unidadID - identificador de unidad gps
     * @return [boolean] - retorna true si la unidad fue desactivado o 
     *                     falso si no se hizo
     */
    static function desactivarUnidad($unidadID) {
        $conn = getConn();
        $sql = "UPDATE tso_unidades_gps SET esta_activo = FALSE WHERE id = ?";
        $values = array($unidadID);

        $count = $conn->executeUpdate($sql, $values);

        return ($count > 0) ? true : false;
    }

    /**
     * actualizarUnidad - Recibe los valores de una unidad gps y los 
     * actualiza en la base de datos. EL proceso consiste en crear una nuevo
     * unidad con los valores actualizados y desactivar la unidad gps  
     * anterior.
     *   
     * @param  [array] $unidad [contiene todos los atributos de la unidad GPS]
     * @return [boolean]       [true si la unidad fue actualizada, false si no]
     */
    static function actualizarUnidad($unidad) {
        $conn = getConn();
        // Insertamos el nuevo registro
        $agregado = self::agregarUnidad($unidad);
        // Desactivamos la unidad gps actual
        $desactivado = self::desactivarUnidad($unidad->id);

        return ($agregado && $desactivado);
    }

    /**
     * Esta funcion permite agregar una nueva unidad gps a la base de datos.
     * @param  [stdClass]   $unidad [valores de la unidad gps]
     * @return [boolean]    [true si la unidad gps fue agregada correctamente, false si no]
     */
    static function agregarUnidad($unidad) {
        $sql = 'INSERT INTO tso_unidades_gps (nombre, cod_unidad, cod_instalacion, precio_instalacion, ';
        $sql .= 'precio_unidad, descripcion, image) VALUES (?, ?, ?, ?, ?, ?, ? );';

        $conn = getConn();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $unidad['nombre']);
        $stmt->bindValue(2, $unidad['cod_unidad']);
        $stmt->bindValue(3, $unidad['cod_instalacion']);
        $stmt->bindValue(4, $unidad['precio_instalacion']);
        $stmt->bindValue(5, $unidad['precio_unidad']);
        $stmt->bindValue(6, $unidad['descripcion']);
        $stmt->bindValue(7, "image");

        $inserted_rows = $stmt->execute();
        return ($inserted_rows == 1);
    }
    
    static function updateUnidad($unidad) {
        
        $conn = getConn();
        
        $sql = "UPDATE tso_unidades_gps set nombre= ? , cod_unidad=?, cod_instalacion=?, precio_instalacion=?, precio_unidad=?, descripcion=?, image=? where id=?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(1, $unidad['nombre']);
        $stmt->bindValue(2, $unidad['cod_unidad']);
        $stmt->bindValue(3, $unidad['cod_instalacion']);
        $stmt->bindValue(4, $unidad['precio_instalacion']);
        $stmt->bindValue(5, $unidad['precio_unidad']);
        $stmt->bindValue(6, $unidad['descripcion']);
        $stmt->bindValue(7, "image");
        $stmt->bindValue(8, $unidad['id']);
        
        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0) ? true : false;
        
    }

}
