<?php
namespace db;
require_once (__DIR__."/../../config/db.php");
use stdClass;
//print"<pre>"; print_r($_REQUEST);print"</pre>";

class ClienteDB{

	public static function getCliente{
		$conn = getConn();
		$sql = "SELECT nit FROM tso_clientes WHERE nit=";
	}

	public static function agregarCliente{
		$conn = getConn();
		$sql = "INSERT INTO tso_clientes (nit,empresa,telefono,correo,correo2) 
		VALUES (,,,,)";
	}

	public static function agregarContactoCliente{
		$conn = getConn();
		$sql = "INSERT INTO tso_clientes_contactos (cliente_id,nombre)
		VALUES (,)";
	}


}
?>