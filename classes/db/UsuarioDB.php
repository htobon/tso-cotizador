<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y encontrar información del usuario.
 *
 * @author hftobon
 */
class UsuarioDB {
    /*
     * Busca un usuario con el correo que se le pasa por parámetro.
     */

    static function getUsuarioPorCorreo($email) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $u = $stmt->fetch();
        if (isset($u)) {
            $usuario = new stdClass();
            $usuario->id = $u["id"];
            $usuario->codigo = $u["codigo"];
            $usuario->nombres = $u["nombres"];
            $usuario->apellidos = $u["apellidos"];
            $usuario->telefono = $u["telefono"];
            $usuario->correo = $u["correo"];
            $usuario->estaActivo = $u["esta_activo"];
            $usuario->rol = $u["rol"];
            $usuario->firma = $u["firma_digital"];
            $usuario->fechaCreacion = $u["fecha_creacion"];
            return $usuario;
        } else {
            return NULL;
        }
    }

    /*
     * Busca un usuario con el id que se le pasa por parámetro.
     */

    static function getUsuarioPorID($id) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $u = $stmt->fetch();
        if (isset($u)) {
            $usuario = new stdClass();
            $usuario->id = $u["id"];
            $usuario->codigo = $u["codigo"];
            $usuario->nombres = $u["nombres"];
            $usuario->apellidos = $u["apellidos"];
            $usuario->telefono = $u["telefono"];
            $usuario->correo = $u["correo"];
            $usuario->estaActivo = $u["esta_activo"];
            $usuario->rol = $u["rol"];
            $usuario->firma = $u["firma_digital"];
            $usuario->fechaCreacion = $u["fecha_creacion"];
            return $usuario;
        } else {
            return NULL;
        }
    }

    /*
     * Valida nombre de usuario y contraseña contra la base de datos.
     */

    static function validarUsuario($correo, $password) {
        $conn = getConn();
        $sql = "SELECT count(id) as cantidad FROM tso_usuarios WHERE correo = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->bindValue(2, md5($password));
        $stmt->execute();
        $res = $stmt->fetch();
        if ($res["cantidad"] == 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Valida si un email existe o no.
     */

    static function validarEmail($correo) {
        $conn = getConn();
        $sql = "SELECT count(id) as cantidad FROM tso_usuarios WHERE correo = ? ;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $correo);
        $stmt->execute();
        $res = $stmt->fetch();
        if ($res["cantidad"] == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Esta funcion retorna todos los usuarios presentes en la bd
     * @return [type] [description]
     */
    static function getUsuarios() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_usuarios WHERE esta_activo= true; ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        if (isset($resultado)) {
            $usuarios = array();
            foreach ($resultado as $u) {
                $usuario = new stdClass();
                $usuario->id = $u["id"];
                $usuario->codigo = $u["codigo"];
                $usuario->nombres = $u["nombres"];
                $usuario->apellidos = $u["apellidos"];
                $usuario->telefono = $u["telefono"];
                $usuario->correo = $u["correo"];
                $usuario->estaActivo = $u["esta_activo"];
                $usuario->rol = $u["rol"];
                $usuario->firma = $u["firma_digital"];
                $usuario->fechaCreacion = $u["fecha_creacion"];
                array_push($usuarios, $usuario);
            }
            return $usuarios;
        }
        return NULL;
    }

    static function addUsuario($usuario) {

        $sql = "INSERT INTO tso_usuarios (salesforce_id, codigo, nombres, apellidos, telefono, correo, password, rol, firma_digital) 
                values ('1', ?,?,?,?,?,md5(?),?,?);";

        $conn = getConn();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $usuario['codigo']);
        $stmt->bindValue(2, $usuario['nombres']);
        $stmt->bindValue(3, $usuario['apellidos']);
        $stmt->bindValue(4, $usuario['telefono']);
        $stmt->bindValue(5, $usuario['email']);
        $stmt->bindValue(6, $usuario['clave']);
        $stmt->bindValue(7, $usuario['rol']);
        $stmt->bindValue(8, $usuario['firma']);

        $inserted_rows = $stmt->execute();
        return ($inserted_rows == 1);
    }
    
    public static function updateUsuario($usuario) {

        $conn = getConn();
        
        $sql_aux = " ";
        if(!empty($usuario['clave'])){
            $sql_aux = " password=md5('{$usuario['clave']}'), ";
        }
        
         if(!empty($usuario['firma'])){             
            $sql_aux = " firma_digital='{$usuario['firma']}', ";
         }
        
        $sql = "UPDATE tso_usuarios set salesforce_id= '1', codigo=?, nombres=?, apellidos=?, telefono=?, correo=?, {$sql_aux} rol=? where id=?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(1, $usuario['codigo']);
        $stmt->bindValue(2, $usuario['nombres']);
        $stmt->bindValue(3, $usuario['apellidos']);
        $stmt->bindValue(4, $usuario['telefono']);
        $stmt->bindValue(5, $usuario['email']);
        $stmt->bindValue(6, $usuario['rol']);
        $stmt->bindValue(7, $usuario['id']);
        
        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0) ? true : false;
    }
    
    public static function inactiveUsuario($id) {

        $conn = getConn();       
        $sql = "UPDATE tso_usuarios set esta_activo = false where id=?";
        $stmt = $conn->prepare($sql);        
        $stmt->bindValue(1, $id);        
        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0) ? true : false;
    }

}
