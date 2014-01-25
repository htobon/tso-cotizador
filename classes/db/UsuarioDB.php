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

    /**
     * Esta funcion retorna todos los usuarios presentes en la bd
     * @return [type] [description]
     */
    static function getUsuarios() {
        $conn = getConn();
        $sql = "SELECT * FROM tso_usuarios";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll();

        foreach ($usuarios as $u) {
            # code...
        }
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
            $usuario->fechaCreacion = $u["fecha_creacion"];
            return $usuario;
        } else {
            return NULL;
        }
    }

}
