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
            $cliente->nombre = $c["nombre"];
            return $cliente;
        } else {
            return NULL;
        }
    }

    public static function getClientes() {

        $conn = getConn();
        $sql = "SELECT * FROM tso_clientes";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll();
        $clientes = array();
        foreach ($datos as $c) {
            if (isset($c)) {
                $cliente = new stdClass();
                $cliente->id = $c["id"];
                $cliente->nit = $c["nit"];
                $cliente->nombre = $c["nombre"];
                array_push($clientes, $cliente);
            }
        }
        return $clientes;
    }

    public static function getClientePorId($id) {

        $conn = getConn();
        $sql = "SELECT * FROM tso_clientes WHERE id= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $c = $stmt->fetch();

        if (isset($c)) {
            $cliente = new stdClass();
            $cliente->id = $c["id"];
            $cliente->nit = $c["nit"];
            $cliente->nombre = $c["nombre"];
            return $cliente;
        } else {
            return NULL;
        }
    }

    public static function getClientePorNombre($nombre) {

        $conn = getConn();
        $sql = "SELECT * FROM tso_clientes WHERE nombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, "%{$nombre}%");
        $stmt->execute();
        $clienteData = $stmt->fetchAll();
        $clientes = array();

        foreach ($clienteData as $c) {
            if (isset($c)) {
                $cliente = new stdClass();
                $cliente->id = $c["id"];
                $cliente->nit = $c["nit"];
                $cliente->nombre = $c["nombre"];
                array_push($clientes, $cliente);
            }
        }
        return (count($clientes) <= 0 ) ? null : $clientes;
    }

    public static function agregarCliente($cliente) {
        $conn = getConn();

        $sql = "INSERT INTO tso_clientes (nit,nombre) VALUES (?,?)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $cliente->nit);
        $stmt->bindValue(2, $cliente->nombre);

        $inserted_rows = $stmt->execute();
        return ($inserted_rows == 1);
    }

}

?>