<?php
require_once '../../config/db.php';
namespace db;

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
        $sql = "SELECT * FROM accesorios";
        $stmt = $conn->query($sql);
        $user = $stmt->fetch();
        print_r($user);
    }
}
