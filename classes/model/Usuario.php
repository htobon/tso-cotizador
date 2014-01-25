<?php

namespace model;

/**
 * Esta clase representa un usuario del sistema.
 *
 * @author hftobon
 */
class Usuario {

    Const ROL_VENDEDOR = "Vendedor";
    Const ROL_ADMIN = "Admin";

    private $nombres;
    private $apellidos;
    private $telefono;
    private $correo;
    private $rol;

    public function getNombre() {
        return $this->nombres;
    }

    public function getApellidos() {
        return $this->apellidos;
    }
    
    public function esVendedor() {
        return $this->rol == self::ROL_VENDEDOR;
    }
    public function esAdmin() {
        return $this->rol == self::ROL_ADMIN;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setNombres($nombres) {
        $this->nombres = $nombres;
    }

    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

}
