<?php
namespace db;
require_once (__DIR__."/../../config/db.php");
use stdClass;
//print"<pre>"; print_r($_REQUEST);print"</pre>";

class CotizacionDB{

	public static function agregarCotizacion{
		$conn = getConn();
		$sql = "INSERT INTO tso_cotizaciones (usuario_id,cliente_id,unidad_gps_id,tipo_contrato_id,plan_servicio_id,descuento_id,duracion_contrato_id,cantidad_vehiculos,fecha,serial) 
		VALUES (,,,,,,,,,)";
	}

	public static function agregarAccesoriosCotizados{
		$conn = getConn();
		$sql = "INSERT INTO tso_accesorios_cotizados (cotizacion_id,accesorio_id,cantidad_accesorio) 
		VALUES (,,)";

	}

	public static function getCotizacion{
		$conn = getConn();
        $sql = "SELECT * FROM tso_cotizaciones WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $cotizacion = $stmt->fetchAll();
        
        return $cotizacion;
	}
	
	public static function getAccesoriosCotizados{
		$conn = getConn();
        $sql = "SELECT * FROM tso_accesorios_cotizados WHERE cotizacion_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $gpsID);
        $stmt->execute();

        $accesorioCotizado = $stmt->fetchAll();
        
        return $accesorioCotizado;
	}	
}
?>