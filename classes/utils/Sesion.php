<?php

namespace utils;

use Exception;

/**
 * Esta clase se encargará de manejar todas las variables de la sesión del usuario.
 * El objetivo entonces será poder acceder a las variables utilizando métodos y nunca utilizando
 * la variable $_SESSION.
 *
 * @author hftobon
 */
class Sesion {

    /**
     * 
     * @return Retorna verdadero si la sesión ya exst y falso en caso contrario.
     */
    private static function existeSesion() {
        if (session_id() == '') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Esta función identifica si la sesión ya fue iniciada previamente por algún usuario.
     * @return boolean Verdadero si fue iniciada. Falso en caso contario.
     */
    public static function sesionActiva() {
        return self::existeVariable(Constantes::SESION_USER_ID);
    }

    /**
     * Esta función valida si una variable en la sesión del usuario existe.
     * @param type $nombreVariable
     * @return boolean
     */
    public static function existeVariable($nombreVariable) {
        if (self::existeSesion()) {
            if (isset($_SESSION[$nombreVariable])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Esta función aplica guarda una variable a la sesión y arroja una excepción en caso
     * que no se haya podido llevar a cabo la tarea.
     * @param type $nombreVariable
     * @param type $valorVariable
     * @throws Exception
     */
    public static function setVariable($nombreVariable, $valorVariable) {
        if (self::existeSesion()) {
            $_SESSION[$nombreVariable] = $valorVariable;
            if (self::existeVariable($nombreVariable) == false) {
                throw new Exception('No se pudo crear la sesión');
            }
        }
    }

    /**
     * Esta función encuentra el valor de una variable. Debe ser utilizada en conjunto
     * con la función existeVariable para evitar que la excepción se propague.
     * @param type $variable
     * @return type
     * @throws Exception
     */
    public static function getVariable($variable) {
        if (isset($_SESSION[$variable])) {
            return $_SESSION[$variable];
        } else {
            throw new Exception('El valor de la sesión no existe.');
        }
    }

    /**
     * Esta función termina la sesión del usuario. Retorna true si la sesión
     * fue terminada exitosamente y false en caso contrario.
     * @return boolean
     */
    public static function terminarSesion() {
        if (self::existeSesion()) {
            session_destroy();
        }
        return true;
    }

    /**
     * Esta función elimina una variable de la sesión del usuario.
     * @param type $nombreVariable
     * @return boolean
     */
    public static function removerVariable($nombreVariable) {
        if (self::existeSesion()) {
            unset($_SESSION[$nombreVariable]);
            if (self::existeVariable($nombreVariable) == false) {
                return true;
            }
        }
        return false;
    }

}
