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
use db\DuracionesContratoDB;
use db\DescuentosDB;
use db\AccesoriosPlanesDB;
use db\AccesoriosGpsDB;

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

                    // Mover Firma Digital 
                    $folder_tmp = __DIR__ . "/../../../tmp/{$usuario['firma']}";
                    $folder_firmas = __DIR__ . "/../../../images/firmas/{$usuario['firma']}";

                    $msj = ' Error Cargando Firma Digital';
                    if (copy($folder_tmp, $folder_firmas)) {
                        unlink($folder_tmp);
                        $msj = 'Exito';
                    }


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

                if (!empty($usuario['firma'])) {
                    // Mover Firma Digital 
                    $folder_tmp = __DIR__ . "/../../../tmp/{$usuario['firma']}";
                    $folder_firmas = __DIR__ . "/../../../images/firmas/{$usuario['firma']}";

                    $msj = ' Error Cargando Firma Digital';
                    if (copy($folder_tmp, $folder_firmas)) {
                        unlink($folder_tmp);
                        if (!empty($usuario['firma_actual'])) {
                            $folder_firmas = __DIR__ . "/../../../images/firmas/{$usuario['firma_actual']}";
                            if (file_exists($folder_firmas))
                                unlink($folder_firmas);
                        }
                        $msj = 'Exito';
                    }
                }

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

    public function deleteImageTmp() {
        
    }

    public function getAccesorios() {
        $accesorios = AccesoriosDB::getAccesorios();
        //Success
        return $this->_response(1, NULL, array('accesorios' => $accesorios));
    }

    public function getAccesorio() {

        if (isset($_POST['accesorio_id']) && $_POST['accesorio_id'] != 0) {

            $accesorio_id = $_POST['accesorio_id'];
            $accesorio = AccesoriosDB::getAccesoriosPorId($accesorio_id);

            $restricciones_planes = AccesoriosPlanesDB::getRestriccionesPorAccesorio($accesorio_id);
            $restricciones_unidades = AccesoriosGpsDB::getRestriccionesPorAccesorio($accesorio_id);

            return $this->_response(1, NULL, array('accesorio' => $accesorio, 'restricciones_planes' => $restricciones_planes, 'restricciones_unidades' => $restricciones_unidades));
        }
    }

    public function saveAccesorio() {

        if (isset($_POST['accesorio']) && isset($_POST['restricciones_unidades']) && isset($_POST['restricciones_planes'])) {

            $accesorio = $_POST['accesorio'];
            $restricciones_unidades = $_POST['restricciones_unidades'];
            $restricciones_planes = $_POST['restricciones_planes'];

            $result = AccesoriosDB::agregarAccesorio($accesorio);

            if ($result) {

                $accesorioNuevo = AccesoriosDB::getAccesorioActivoPorCodigo($accesorio["codAccesorio"]);

                $accesorios_gps = array();

                foreach ($restricciones_unidades as $value) {
                    array_push($accesorios_gps, array("accesorio_id" => $accesorioNuevo->id, "unidad_gps_id" => $value));
                }

                $accesorios_planes = array();

                foreach ($restricciones_planes as $value) {
                    array_push($accesorios_planes, array("accesorio_id" => $accesorioNuevo->id, "planes_id" => $value));
                }

                $agregarRestricionesGps = AccesoriosGpsDB::agregarRestricciones($accesorios_gps);
                $agregarRestricionesPlanes = AccesoriosPlanesDB::agregarRestricciones($accesorios_planes);
            }


            if ($result && $agregarRestricionesGps && $agregarRestricionesPlanes) {

                return $this->_response(1, 'Registro Ingresado Correctamente', array());
            } else {

                return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
            }
        }
    }

    public function updateAccesorio() {

        if (isset($_POST['accesorio']) && isset($_POST['restricciones_unidades']) && isset($_POST['restricciones_planes'])) {

            $accesorio = $_POST['accesorio'];
            $restricciones_unidades = $_POST['restricciones_unidades'];
            $restricciones_planes = $_POST['restricciones_planes'];

            $result = AccesoriosDB::actualizarAccesorio($accesorio);

            if ($result) {

                $accesorioActualizado = AccesoriosDB::getAccesorioActivoPorCodigo($accesorio["codAccesorio"]);

                $accesorios_gps = array();

                foreach ($restricciones_unidades as $value) {
                    array_push($accesorios_gps, array("accesorio_id" => $accesorioActualizado->id, "unidad_gps_id" => $value));
                }

                $accesorios_planes = array();

                foreach ($restricciones_planes as $value) {
                    array_push($accesorios_planes, array("accesorio_id" => $accesorioActualizado->id, "planes_id" => $value));
                }


                $eliminarRestriccionesGps = AccesoriosGpsDB::eliminarRestriccionesPorAccesorio($accesorioActualizado->id);
                $agregarRestricionesGps = AccesoriosGpsDB::agregarRestricciones($accesorios_gps);

                $eliminarRestriccionesPlanes = AccesoriosPlanesDB::eliminarRestriccionesPorAccesorio($accesorioActualizado->id);
                $agregarRestricionesPlanes = AccesoriosPlanesDB::agregarRestricciones($accesorios_planes);
            }

            if ($result && $eliminarRestriccionesGps && $agregarRestricionesGps && $eliminarRestriccionesPlanes && $agregarRestricionesPlanes) {

                return $this->_response(1, 'Registro Ingresado Correctamente', array());
            } else {

                return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
            }
        }
    }

    public function inactiveAccesorio() {

        if (isset($_POST['accesorio_id'])) {

            $accesorio_id = $_POST['accesorio_id'];

            $result = AccesoriosDB::desactivarAccesorio($accesorio_id);
            if ($result) {
                return $this->_response(1, 'Registro Inactivado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
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

    public function getMeses() {
        $duraciones = DuracionesContratoDB::getDuracionesContratoActivas();
        //Success
        return $this->_response(1, NULL, array('duraciones_contrato' => $duraciones));
    }

    public function getDuracionContrato() {
        if (isset($_POST['duracion_id']) && $_POST['duracion_id'] != 0) {

            $duracion_id = $_POST['duracion_id'];
            $duracion_contrato = DuracionesContratoDB::getDuracionContratoPorId($duracion_id);
            return $this->_response(1, NULL, array('duracion_contrato' => $duracion_contrato));
        }
    }

    public function saveDuracionContrato() {

        if (isset($_POST['duracion_contrato'])) {

            $duracion = $_POST['duracion_contrato'];


            $result = DuracionesContratoDB::agregarDuracionContrato($duracion);

            if ($result) {
                return $this->_response(1, 'Registro Ingresado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
            }
        }
    }

    public function updateDuracionContrato() {

        if (isset($_POST['duracion_contrato'])) {

            $duracion_contrato = $_POST['duracion_contrato'];

            $result = DuracionesContratoDB::modificarDuracionContrato($duracion_contrato);
            if ($result) {
                return $this->_response(1, 'Registro Actualizado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function inactiveDuracionContrato() {
        if (isset($_POST['duracion_id'])) {

            $duracion_id = $_POST['duracion_id'];

            $result = DuracionesContratoDB::inactivarDuracionContrato($duracion_id);
            if ($result) {
                return $this->_response(1, 'Registro Inactivado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function getDescuentos() {
        $descuentos = DescuentosDB::getDescuentosActivos();
        //Success
        return $this->_response(1, NULL, array('descuentos' => $descuentos));
    }

    public function getDescuento() {
        if (isset($_POST['descuento_id']) && $_POST['descuento_id'] != 0) {

            $descuento_id = $_POST['descuento_id'];
            $descuento = DescuentosDB::getDescuentosPorId($descuento_id);
            return $this->_response(1, NULL, array('descuento' => $descuento));
        }
    }

    public function saveDescuento() {

        if (isset($_POST['descuento'])) {

            $descuento = $_POST['descuento'];


            $result = DescuentosDB::agregarDescuento($descuento);

            if ($result == 1) {
                return $this->_response(1, 'Registro Ingresado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
            }
        }
    }

    public function updateDescuento() {

        if (isset($_POST['descuento'])) {

            $descuento = $_POST['descuento'];

            $result = DescuentosDB::actualizarDescuento($descuento);
            if ($result) {
                return $this->_response(1, 'Registro Actualizado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function inactiveDescuento() {

        if (isset($_POST['descuento_id'])) {

            $descuento_id = $_POST['descuento_id'];

            $result = DescuentosDB::desactivarDescuento($descuento_id);
            if ($result) {
                return $this->_response(1, 'Registro Inactivado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function getPlanes() {
        $planes = PlanesDB::getPlanes();
        //Success
        return $this->_response(1, NULL, array('planes' => $planes));
    }

    public function getPlan() {
        if (isset($_POST['plan_id']) && $_POST['plan_id'] != 0) {

            $plan_id = $_POST['plan_id'];
            $plan = PlanesDB::getPlanePorId($plan_id);
            return $this->_response(1, NULL, array('plan' => $plan));
        }
    }

    public function savePlan() {

        if (isset($_POST['plan'])) {

            $plan = $_POST['plan'];


            $result = PlanesDB::agregarPlan($plan);

            if ($result) {
                return $this->_response(1, 'Registro Ingresado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error ingresando el registro.', array());
            }
        }
    }

    public function updatePlan() {

        if (isset($_POST['plan'])) {

            $plan = $_POST['plan'];

            $result = PlanesDB::actualizarPlan($plan);
            if ($result) {
                return $this->_response(1, 'Registro Actualizado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
    }

    public function inactivePlan() {

        if (isset($_POST['plan_id'])) {

            $plan_id = $_POST['plan_id'];

            $result = PlanesDB::desactivarPlan($plan_id);
            if ($result) {
                return $this->_response(1, 'Registro Inactivado Correctamente', array());
            } else {
                return $this->_response(0, 'Ha ocurrido un error actualizando el registro.', array());
            }
        }
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

    public function filtrarCotizaciones() {


        if (isset($_POST['vendedor_id']) && isset($_POST['fecha_inicial']) && isset($_POST['fecha_final'])) {

            $vendedor_id = $_POST['vendedor_id'];
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_final = $_POST['fecha_final'];

            $cotizaciones = CotizacionDB::filtrarCotizaciones($vendedor_id, $fecha_inicial, $fecha_final);
            return $this->_response(1, NULL, array('cotizaciones' => $cotizaciones));
        }
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
