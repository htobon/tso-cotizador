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
