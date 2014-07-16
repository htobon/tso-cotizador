<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\CotizacionDB;
use db\ClienteDB;
use db\PlanesDB;
use db\TiposContratoDB;
use db\UnidadesGpsDB;
use db\AccesoriosDB;
use db\UsuarioDB;

if (isset($_POST['action'])) {
    $action = new Action();
    $function = $_POST['action'];
    if (method_exists($action, $function))
        die($action->$function());
    else
        die('bad request.');
} else {
    die('bad request..');
}
echo json_encode($response);

class Action {

    public function __construct() {
        
    }

    public function getUsuarios() {
        $usuarios = UsuarioDB::getUsuarios();
        //Success
        return $this->_response(1, NULL, array('usuarios' => $usuarios));
    }

    public function getUsuario() {
        if (isset($_POST['usuario_id']) && $_POST['usuario_id'] != 0) {

            $usuario_id = $_POST['usuario_id'];
            $usuario = UsuarioDB::getUsuarioPorID($usuario_id);
            return $this->_response(1, NULL, array('usuario' => $usuario));
        }
    }

    public function saveUsuario() {

        if (isset($_POST['usuario'])) {

            $usuario = $_POST['usuario'];

            $isValid = UsuarioDB::validarEmail($usuario['email']);

            if (!$isValid) {
                $result = UsuarioDB::addUsuario($usuario);
                if ($result == 1) {

                    return $this->_response(1, 'Registro Ingresado Correctamente', array());
                } else {

                    return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
                }
            } else {
                return $this->_response(0, 'El email ya está registrado', array());
            }
        }
    }

    public function updateUsuario() {

        if (isset($_POST['usuario'])) {

            $usuario = $_POST['usuario'];

            $result = UsuarioDB::updateUsuario($usuario);
            if ($result) {
                return $this->_response(1, 'Registro Actualizado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function inactiveUsuario() {

        if (isset($_POST['usuario_id'])) {

            $usuario_id = $_POST['usuario_id'];

            $result = UsuarioDB::inactiveUsuario($usuario_id);
            if ($result) {
                return $this->_response(1, 'Registro Inactivado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function getAccesorios() {
        $accesorios = AccesoriosDB::getAccesorios();
        //Success
        return $this->_response(1, NULL, array('accesorios' => $accesorios));
    }

    public function getUnidadesGPS() {
        $unidades = UnidadesGpsDB::getUnidadesGpsActivas();
        //Success
        return $this->_response(1, NULL, array('unidades' => $unidades));
    }

    public function getUnidadGPS() {

        if (isset($_POST['unidad_gps_id']) && $_POST['unidad_gps_id'] != 0) {

            $unidad_gps_id = $_POST['unidad_gps_id'];
            $unidad_gps = UnidadesGpsDB::getUnidadesGpsPorId($unidad_gps_id);
            return $this->_response(1, NULL, array('unidad_gps' => $unidad_gps));
        }
    }

    public function saveUnidadGPS() {

        if (isset($_POST['unidad_gps'])) {

            $unidad_gps = $_POST['unidad_gps'];

            
            $result = UnidadesGpsDB::agregarUnidad($unidad_gps);
            if ($result == 1) {

                return $this->_response(1, 'Registro Ingresado Correctamente', array());
            } else {

                return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
            }
        }
    }

    public function updateUnidadGPS() {

        if (isset($_POST['unidad_gps'])) {

            $unidad_gps = $_POST['unidad_gps'];

            $result = UnidadesGpsDB::updateUnidad($unidad_gps);
            if ($result) {
                return $this->_response(1, 'Registro Actualizado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }
    
    public function inactiveUnidadGPS() {

        if (isset($_POST['unidad_gps_id'])) {

            $unidad_gps_id = $_POST['unidad_gps_id'];

            $result = UnidadesGpsDB::desactivarUnidad($unidad_gps_id);
            if ($result) {
                return $this->_response(1, 'Registro Inactivado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function getTiposContratos() {
        $tiposContratos = TiposContratoDB::getTiposContrato();
        //Success
        return $this->_response(1, NULL, array('tiposContratos' => $tiposContratos));
    }

    public function getPlanes() {
        $planes = PlanesDB::getPlanes();
        //Success
        return $this->_response(1, NULL, array('planes' => $planes));
    }

    public function getClientes() {
        $clientes = ClienteDB::getClientes();
        //Success
        return $this->_response(1, NULL, array('clientes' => $clientes));
    }

    public function reporteCotizaciones() {
        $cotizaciones = CotizacionDB::listarCotizaciones();
        //Success        
        return $this->_response(1, NULL, array('cotizaciones' => $cotizaciones));
    }

    public function getListFiles() {
        $handle = opendir('../../../tmp/csv');
        if ($handle !== FALSE) {
            $valid_extension = array('csv');
            $cotizaciones = array();
            $detalles = array();
            while (false !== ($filename = readdir($handle))) {
                $_name = explode('.', $filename);
                if (is_array($_name) && count($_name) > 0) {
                    $_ext = array_pop($_name);
                    if (in_array($_ext, $valid_extension)) {

                        $files = explode('-', implode('.', $_name));
                        if (in_array("cotizaciones", $files)) {
                            $cotizaciones[] = array(
                                'extension' => $_ext,
                                'name' => implode('.', $_name),
                                'filename' => $filename
                            );
                        }
                        if (in_array("detalles", $files)) {
                            $detalles[] = array(
                                'extension' => $_ext,
                                'name' => implode('.', $_name),
                                'filename' => $filename
                            );
                        }
                    }
                }
            }
            closedir($handle);

            //Success
            return $this->_response(1, NULL, array('cotizaciones' => $cotizaciones, 'detalles' => $detalles));
        }
        //Erros
        return $this->_response(2, 'Cannot open file');
    }

    // Status 1- Success 2- Failed
    private function _response($status = 2, $message = '', $params = array()) {

        $_response = array(
            'message_code' => $status,
            'message' => $message
        );

        $response = array_merge($_response, $params);

        return json_encode($response);
    }

}
