<?php
namespace db;

require_once (__DIR__."/../../config/db.php");

use stdClass;

/**
 * Esta clase se encarga de conectar a la base de datos y encontrar información del usuario.
 *
 * @author hftobon
 */
class UserDB {
    /*
     * Busca un usuario con el correo que se le pasa por parámetro.
     */
    static function getUser($email) {
        $conn = getConn();        
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $u = $stmt->fetch();        
        if(isset($u)) {
            $usuario = new stdClass();
            $usuario->id = $u["id"];
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
    
    static function validarUsuario($correo, $password) {
        
    }
}
