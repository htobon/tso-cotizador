<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

//print"<pre>"; print_r($_REQUEST);print"</pre>";

class ClienteDB {

    public static function getCliente($nit) {
        
        $conn = getConn();
        $sql = "SELECT * FROM tso_clientes WHERE nit= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $nit);
        $stmt->execute();
        $c = $stmt->fetch();
        
        if (isset($c)) {
            $cliente = new stdClass();
            $cliente->id = $c["id"];
            $cliente->nit = $c["nit"];
            $cliente->nombre_cliente = $c["nombre_cliente"];
            $cliente->telefono = $c["telefono"];            
            return $cliente;
        } else {
            return NULL;
        }
    }

    public static function agregarCliente() {
        $conn = getConn();
        $sql = "INSERT INTO tso_clientes (nit,empresa,telefono,correo,correo2) 
		VALUES (,,,,)";
    }

    public static function agregarContactoCliente() {
        $conn = getConn();
        $sql = "INSERT INTO tso_clientes_contactos (cliente_id,nombre)
		VALUES (,)";
    }

}

?>