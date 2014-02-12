<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y 
 * encontrar información de los tipos de contrato.
 *
 * @author hdcarvajal
 */
class TiposContratoDB {

  /**
   * getTiposContrato retorna los tipos de contrato almacenados en la bd
   * @return array(StdClass) arreglo con clases TipoContrato con la info
   */
  static function getTiposContrato() {
    $conn = getConn();
    $sql = "SELECT * FROM tso_tipos_contrato";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $tiposContratoData = $stmt->fetchAll();
    $tiposContrato = array();

    foreach ($tiposContratoData as $tc) {
      if (isset($tc)) {
        $tipoContrato = new stdClass();
        $tipoContrato->id = $tc["id"];
        $tipoContrato->nombre = $tc["nombre"];
        array_push($tiposContrato, $tipoContrato);
      }
    }

    return (count($tiposContrato) <= 0 ) ? null : $tiposContrato;
  }

}
