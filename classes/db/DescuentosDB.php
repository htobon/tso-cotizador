<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y encontrar información de los accesorios.
 *
 * @author hftobon
 */
class DescuentosDB {
  /*
   * Busca todos los descuentos independientemente si están activos o no.
   */

  public static function getDescuentos() {
    $conn = getConn();
    $sql = "SELECT * FROM tso_descuentos_cantidad_vehiculos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $descuentosData = $stmt->fetchAll();
    $descuentos = array();
    foreach ($descuentosData as $d) {
      if (isset($d)) {
        $descuento = new stdClass();
        $descuento->id = $d["id"];
        $descuento->cantidadMin = $d["cantidad_min"];
        $descuento->cantidadMax = $d["cantidad_max"];
        $descuento->descuento = $d["descuento"];
        $descuento->estaActivo = $d["esta_activo"];
        array_push($descuentos, $descuento);
      }
    }
    return (count($descuentos) <= 0 ) ? null : $descuentos;
  }

  /*
   * Busca todos los descuentos que están activos.
   */

  public static function getDescuentosActivos() {
    $conn = getConn();
    $sql = "SELECT * FROM tso_descuentos_cantidad_vehiculos WHERE esta_activo = TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $descuentosData = $stmt->fetchAll();
    $descuentos = array();
    foreach ($descuentosData as $d) {
      if (isset($d)) {
        $descuento = new stdClass();
        $descuento->id = $d["id"];
        $descuento->cantidadMin = $d["cantidad_min"];
        $descuento->cantidadMax = $d["cantidad_max"];
        $descuento->descuento = $d["descuento"];
        $descuento->estaActivo = $d["esta_activo"];
        array_push($descuentos, $descuento);
      }
    }
    return (count($descuentos) <= 0 ) ? null : $descuentos;
  }

  /**
   * desactivarDescuento Desactiva el descuento indentificado con el 
   * parametro recibido.
   * @param  $descuentoID - identificador del descuento
   * @return [boolean] - retorna true si el descuento fue desactivado o 
   *                     falso en caso contrario.
   */
  static function desactivarDescuento($descuentoID) {
    $conn = getConn();
    $sql = "UPDATE tso_descuentos_cantidad_vehiculos SET esta_activo = FALSE WHERE id = ?";
    $values = array($descuentoID);
    $count = $conn->executeUpdate($sql, $values);
    return ($count > 0) ? true : false;
  }

  /**
   * Esta funcion permite agregar un nuevo descuento a la base de datos.
   * @param  [stdClass]   $descuento [valores del descuento]
   * @return [boolean]    [true si el descuento fue agregado correctamente, false en caso contrario]
   */
  static function agregarDescuento($descuento) {
    $sql = 'INSERT INTO tso_descuentos_cantidad_vehiculos (cantidad_min, cantidad_max, descuento)';
    $sql .= ' VALUES (?, ?, ?);';

    $conn = getConn();
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(1, $descuento->cantidadMin);
    $stmt->bindValue(2, $descuento->cantidadMax);
    $stmt->bindValue(3, $descuento->descuento);

    $inserted_rows = $stmt->execute();
    return ($inserted_rows == 1);
  }

  /**
   * actualizarDescuento - Recibe los valores de un descuento y los 
   * actualiza en la base de datos. EL proceso consiste en crear un nuevo
   * descuento con los valores actualizados y desactivar el descuento 
   * anterior.
   *   
   * @param  [array] $descuento [contiene todos los atributos del descuento]
   * @return [boolean]          [true si el descuento fue actualizado, 
   *                             false en caso contrario]
   */
  static function actualizarDescuento($descuento) {
    // Insertamos el nuevo registro
    $agregado = self::agregarDescuento($descuento);
    if ($agregado) {
      // Desactivamos el descuento actual
      $desactivado = self::desactivarDescuento($descuento->id);
      if($desactivado) {
        return true;
      }
    }
    return false;
  }

}
