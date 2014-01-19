<?php

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
     * @return Retorna verdadero si la sesión ha sido iniciada. Falso en caso contrario.
     */
    private function estaIniciada() {
        if (session_id() == '') {
            return false;
        } else {
            return true;
        }
    }
    
    public function iniciarSesion() {
        if ($this->estaIniciada() == false) {
            session_start();
        }
    }

    /**
     * Esta función valida si una variable en la sesión del usuario existe.
     * @param type $nombreVariable
     * @return boolean
     */
    public function existeVariable($nombreVariable) {
        if ($this->estaIniciada() == false) {
            session_start();
        }
        if (isset($_SESSION[$nombreVariable])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Esta función aplica guarda una variable a la sesión y arroja una excepción en caso
     * que no se haya podido llevar a cabo la tarea.
     * @param type $nombreVariable
     * @param type $valorVariable
     * @throws Exception
     */
    public function setVariable($nombreVariable, $valorVariable) {
        if ($this->estaIniciada() != true) {
            session_start();
        }
        $_SESSION[$nombreVariable] = $valorVariable;
        if ($this->existeVariable($nombreVariable) == false) {
            throw new Exception('No se pudo crear la sesión');
        }
    }

    /**
     * Esta función encuentra el valor de una variable. Debe ser utilizada en conjunto
     * con la función existeVariable para evitar que la excepción se propague.
     * @param type $variable
     * @return type
     * @throws Exception
     */
    public function getVariable($variable) {
        if (isset($_SESSION[$variable])) {
            return $_SESSION[$variable];
        } else {
            throw new Exception('El valor de la sesión no existe.');
        }
    }

}
