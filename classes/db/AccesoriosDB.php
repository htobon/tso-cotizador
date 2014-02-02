<?php
namespace db;
require_once (__DIR__."/../../config/db.php");
use stdClass;

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
        self::agregarAccesorio($accesorio);
        // Desactivamos el accesorio actual
        $conn->update("tso_accesorios", array('esta_activo' => false), array('id' => $accesorio["id"]));

        return (array) $std;
    }

    /**
     * Esta funcion permite agregar un nuevo accesorio a la base de datos.
     * @param  [stdClass]   $accesorio [valores del accesorio]
     * @return [boolean]    [true si el accesorio fue agregado correctamente, false si no]
     */
    static function agregarAccesorio($accesorio){


        // Transformamos la clase en arreglo
        /*
        $arregloAccesorio = array();
        $arregloAccesorio["nombre"] = $accesorio->nombre . "anything' OR 'x'='x \as asdfoqw ñ asdf r$%& ";
        $arregloAccesorio["cod_accesorio"] = $accesorio->codAccesorio;
        $arregloAccesorio["cod_instalacion"] = $accesorio->codInstalacion;
        $arregloAccesorio["precio_instalacion"] = $accesorio->precioInstalacion;
        $arregloAccesorio["precio_accesorio"] = $accesorio->precioAccesorio;
        $arregloAccesorio["precio_instalacion"] = $accesorio->precioInstalacion;
        $arregloAccesorio["descripcion"] = $accesorio->descripcion;
        $arregloAccesorio["image"] = $accesorio->image;
        $arregloAccesorio["posicion_x"] = $accesorio->posicionX;
        $arregloAccesorio["posicion_y"] = $accesorio->posicionY;
        $conn->insert("tso_accesorios", $arregloAccesorio );

        $conn = getConn();

        */
        
    }

    static function pruebaInjection1(){
        $conn = getConn();
        $sql = "UPDATE tso_accesorios SET nombre = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $accesorio->nombre . " ' AND (select count(*) from fake) %3e0 OR '1'%3d' " );
        $stmt->bindValue(2, 10 );
        $stmt->execute();        
    }

    static function pruebaInjection2(){

        $arregloAccesorio = array();
        $arregloAccesorio["nombre"] = $accesorio->nombre . "anything' OR 'x'='x \as asdfoqw ñ asdf r$%& ";
        $arregloAccesorio["cod_accesorio"] = $accesorio->codAccesorio;
        $arregloAccesorio["cod_instalacion"] = $accesorio->codInstalacion;
        $arregloAccesorio["precio_instalacion"] = $accesorio->precioInstalacion;
        $arregloAccesorio["precio_accesorio"] = $accesorio->precioAccesorio;
        $arregloAccesorio["precio_instalacion"] = $accesorio->precioInstalacion;
        $arregloAccesorio["descripcion"] = $accesorio->descripcion;
        $arregloAccesorio["image"] = $accesorio->image;
        $arregloAccesorio["posicion_x"] = $accesorio->posicionX;
        $arregloAccesorio["posicion_y"] = $accesorio->posicionY;

        $conn = getConn();

        $conn->insert("tso_accesorios", $arregloAccesorio );
    }
}
