<?php
namespace db;
require_once (__DIR__."/../../config/db.php");

use stdClass;
use db\AccesoriosGpsDB;

/**
 * Esta clase se encarga de conectar a la base de datos y encontrar información de los accesorios.
 *
 * @author hftobon
 */
class AccesoriosDB {
    /*
     * Busca todos los accesorios independientemente si están activos o no.
     */
    public static function getAccesorios() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $accesoriosData = $stmt->fetchAll();
        $accesorios = array();

        foreach ($accesoriosData as $a ) {
            if(isset($a)){
                $accesorio = new stdClass();
                $accesorio->id = $a["id"];
                $accesorio->nombre = $a["nombre"];
                $accesorio->codAccesorio = $a["cod_accesorio"];
                $accesorio->codInstalacion = $a["cod_instalacion"];
                $accesorio->precioInstalacion = $a["precio_instalacion"];
                $accesorio->precioAccesorio = $a["precio_accesorio"];
                $accesorio->precioInstalacion = $a["precio_instalacion"];
                $accesorio->descripcion = $a["descripcion"];
                $accesorio->image = $a["image"];
                $accesorio->posicionX = $a["posicion_x"];
                $accesorio->posicionY = $a["posicion_y"];
                array_push($accesorios, $accesorio);
            }
        }

        return (count($accesorios) <= 0 ) ? null : $accesorios;
    }
    
    /**
     * Busca todos los accesorios activos de la base de datos.
     * @return type
     */
    public static function getAccesoriosActivos() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios WHERE esta_activo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $accesoriosData = $stmt->fetchAll();
        $accesorios = array();

        foreach ($accesoriosData as $a ) {
            if(isset($a)){
                $accesorio = new stdClass();
                $accesorio->id = $a["id"];
                $accesorio->nombre = $a["nombre"];
                $accesorio->codAccesorio = $a["cod_accesorio"];
                $accesorio->codInstalacion = $a["cod_instalacion"];
                $accesorio->precioInstalacion = $a["precio_instalacion"];
                $accesorio->precioAccesorio = $a["precio_accesorio"];
                $accesorio->precioInstalacion = $a["precio_instalacion"];
                $accesorio->descripcion = $a["descripcion"];
                $accesorio->image = $a["image"];
                $accesorio->posicionX = $a["posicion_x"];
                $accesorio->posicionY = $a["posicion_y"];
                array_push($accesorios, $accesorio);
            }
        }

        return (count($accesorios) <= 0 ) ? null : $accesorios;
    }
    
    /**
     * Busca todos los accesorios activos con el código dado.
     * @return type
     */
    public static function getAccesorioActivoPorCodigo($codigo) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios WHERE cod_accesorio = ? AND esta_activo = TRUE";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $codigo);
        $stmt->execute();
        $accesorioData = $stmt->fetchAll();

        $accesorio = new stdClass();
        $_accesorio = $accesorioData[0];

        if(!empty($_accesorio)){
            $accesorio->id = $_accesorio["id"];
            $accesorio->nombre = $_accesorio["nombre"];
            $accesorio->codAccesorio = $_accesorio["cod_accesorio"];
            $accesorio->codInstalacion = $_accesorio["cod_instalacion"];
            $accesorio->precioInstalacion = $_accesorio["precio_instalacion"];
            $accesorio->precioAccesorio = $_accesorio["precio_accesorio"];
            $accesorio->precioInstalacion = $_accesorio["precio_instalacion"];
            $accesorio->descripcion = $_accesorio["descripcion"];
            $accesorio->image = $_accesorio["image"];
            $accesorio->posicionX = $_accesorio["posicion_x"];
            $accesorio->posicionY = $_accesorio["posicion_y"];
        }

        return ( empty($accesorioData) ) ? null : $accesorio;
    }

    /**
     * desactivarAccesorio Desactiva el accesorio indentificado con el 
     * parametro recibido.
     * @param  $accesorioID - identificador de accesorio
     * @return [boolean] - retorna true si el accesorio fue desactivado o 
     *                     falso si no se hizo
     */
    static function desactivarAccesorio($accesorioID){
        $conn = getConn();
        $sql = "UPDATE tso_accesorios SET esta_activo = FALSE WHERE id = ?";
        $values = array($accesorioID);

        $count = $conn->executeUpdate($sql, $values);

        return ($count > 0) ? true : false;
    }

    /**
     * actualizarAccesorio - Recibe los valores de un accesorio  y los 
     * actualiza en la base de datos. EL proceso consiste en crear un nuevo
     * accesorio con los valores actualizados y desactivar el accesorio 
     * anterior.
     *   
     * @param  [array] $accesorio [contiene todos los atributos del accesorio]
     * @return [boolean]          [true si el accesorio fue actualizado, 
     *                             false si no]
     */
    static function actualizarAccesorio($accesorio){
        $conn = getConn();
        // Insertamos el nuevo registro
        $agregado = self::agregarAccesorio($accesorio);
        // Desactivamos el accesorio actual
        $desactivado = self::desactivarAccesorio($accesorio->id);

        // Obtenemos las restricciones con gps del accesorio actual
        $restricciones = AccesoriosGpsDB::getRestriccionesPorAccesorio($accesorio->id);
        // Obtenemos el id del regidtro insertado
        $accesorioActualizado = self::getAccesorioActivoPorCodigo($accesorio->codAccesorio);

        // Actualizamos las restricciones con el nuevo id y las agregamos a la bd
        for($i=0; $i < count($restricciones); $i++){
            $restricciones[$i]["accesorio_id"] = $accesorioActualizado ->id;
        }
        $restriccionesAgregadas = AccesoriosGpsDB::agregarRestricciones($restricciones);

        return ($agregado && $desactivado && $restriccionesAgregadas);
    }

    /**
     * Esta funcion permite agregar un nuevo accesorio a la base de datos.
     * @param  [stdClass]   $accesorio [valores del accesorio]
     * @return [boolean]    [true si el accesorio fue agregado correctamente, false si no]
     */
    static function agregarAccesorio($accesorio){
        $sql = 'INSERT INTO tso_accesorios (nombre, cod_accesorio, cod_instalacion, precio_instalacion, ';
        $sql .= 'precio_accesorio, descripcion, image, posicion_x, posicion_y) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';

        $conn = getConn();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $accesorio->nombre);
        $stmt->bindValue(2, $accesorio->codAccesorio);
        $stmt->bindValue(3, $accesorio->codInstalacion);
        $stmt->bindValue(4, $accesorio->precioInstalacion);
        $stmt->bindValue(5, $accesorio->precioAccesorio);
        $stmt->bindValue(6, $accesorio->descripcion);
        $stmt->bindValue(7, $accesorio->image);
        $stmt->bindValue(8, $accesorio->posicionX);
        $stmt->bindValue(9, $accesorio->posicionY);

        $inserted_rows = $stmt->execute();
        return ($inserted_rows == 1);
    }
}
