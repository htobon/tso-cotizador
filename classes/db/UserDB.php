<?php
namespace db;

require_once (__DIR__."/../../config/db.php");


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
        print_r($stmt->fetch());        
    }
    
    static function validarUsuario($correo, $password) {
        
    }
}
