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
     * Busca todos los accesorios.
     */
    static function getAccesorios() {
        $conn = getConn();        
        $sql = "SELECT * FROM accesorios WHERE esta_activo = TRUE";
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

}
