<?php
namespace db;
require_once (__DIR__."/../../config/db.php");
use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y encontrar información
 * de los planes de servicio que ofrece TSO Mobile.
 *
 * @author hftobon
 */
class PlanesDB {
    /*
     * Busca todos los planes de la base de datos.
     */
    public static function getPlanes() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_planes_servicio";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $planesData = $stmt->fetchAll();
        $planes = array();
        foreach ($planesData as $p ) {
            if(isset($p)){
                $plan = new stdClass();
                $plan->id = $p["id"];
                $plan->nombre = $p["nombre"];
                $plan->codigo = $p["codigo"];
                $plan->precio = $p["precio"];
                $plan->estaActivo = $p["esta_activo"];
                $plan->fechaCreacion = $p["fecha_creacion"];                
                array_push($planes, $plan);
            }
        }
        return (count($planes) <= 0 ) ? null : $planes;
    }
    
    public static function getPlanesActivos() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_planes_servicio WHERE esta_activo = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $planesData = $stmt->fetchAll();
        $planes = array();
        foreach ($planesData as $p ) {
            if(isset($p)){
                $plan = new stdClass();
                $plan->id = $p["id"];
                $plan->nombre = $p["nombre"];
                $plan->codigo = $p["codigo"];
                $plan->precio = $p["precio"];
                $plan->estaActivo = $p["esta_activo"];
                $plan->fechaCreacion = $p["fecha_creacion"];                
                array_push($planes, $plan);
            }
        }
        return (count($planes) <= 0 ) ? null : $planes;
    }

    /**
     * desactivarPlan Desactiva el plan indentificado con el identificador
     * recibido por parámetro.
     * @param  $planID - identificador de plan
     * @return [boolean] - retorna true si el plab fue desactivado o 
     *                     falso si no se hizo
     */
    public static function desactivarPlan($planID){
        $conn = getConn();
        $sql = "UPDATE tso_planes_servicio SET esta_activo = FALSE WHERE id = ?";
        $values = array($planID);

        $count = $conn->executeUpdate($sql, $values);

        return ($count > 0) ? true : false;
    }

    /**
     * actualizarPlan - Recibe los valores de un plan  y los 
     * actualiza en la base de datos. EL proceso consiste en crear un nuevo
     * plan con los valores actualizados y desactivar el plan 
     * anterior.
     *   
     * @param  [array] $plan [contiene todos los atributos del plan]
     * @return [boolean]          [true si el accesorio fue actualizado, 
     *                             false si no]
     */
    public static function actualizarPlan($plan){
        $conn = getConn();
        // Insertamos el nuevo registro
        self::agregarPlan($plan);
        // Desactivamos el plan actual
        $conn->update("tso_planes_servicios", array('esta_activo' => false), array('id' => $plan["id"]));

        return (array) $std;
    }

    /**
     * Esta funcion permite agregar un nuevo plan a la base de datos.
     * @param  [stdClass] $plan [valores del plan]
     * @return [boolean]          [true si el plan fue agregado 
     *                             correctamente, false si no]
     */
    static function agregarPlan($plan){

        /*
        Aqui se debe pasar de atributos stdClass a arreglo
        y escapar las entradas!!!
         */
        $conn = getConn();

        // Quitamos del arreglo los valores que no queremos que sean guardados.
        unset($plan["id"]);
        unset($plan["esta_activo"]);
        unset($plan["fecha_creacion"]);

        $conn->insert("tso_planes_servicio", $plan );

        return (count($plan) <= 0 ) ? null : $plan;
    }
}
