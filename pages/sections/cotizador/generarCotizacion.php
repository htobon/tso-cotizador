<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";

use db\UsuarioDB;
use db\ClienteDB;
use db\CotizacionDB;
use utils\Constantes;
use utils\Sesion;

if (Sesion::sesionActiva()) {

    $datos = $_POST;
   
    $cotizacion_id = 0;
    $mensajeCotizacion = "";

    if (!empty($datos)) {

        $cotizacion = array();
        $cotizacionDetalle = array();


        $usuario = UsuarioDB::getUsuarioPorID(Sesion::getVariable(Constantes::SESION_USER_ID));
        $cliente = ClienteDB::getCliente($datos["nit"]);

        // Cabecera Cotizacion
        $cotizacion["usuario_id"] = $usuario->id;
        $cotizacion["cliente_id"] = $cliente->id;
        $cotizacion["nombre_contacto"] = $datos['nombre'];
        $cotizacion["correo_contacto"] = $datos['correo'];
        $cotizacion["correo_alterno_contacto"] = $datos['correo2'];
        $cotizacion["unidad_gps_id"] = $datos["gps"];
        $cotizacion["tipo_contrato_id"] = $datos["contrato"];
        $cotizacion["plan_servicio_id"] = $datos["plan"];
        $cotizacion["descuento_id"] = 1; //$datos["descuento"];
        $cotizacion["duracion_contrato_id"] = $datos["duracion"];
        $cotizacion["cantidad_vehiculos"] = $datos["cantidad-unidad-gps"];
        $cotizacion["serial"] = "C0-10";

        // Detalle Cotizacion    
        foreach ($datos["accesorios"] as $accesorio) {
            $cantidad = $accesorio['cantidad-accesorio'];
            if (!empty($cantidad) && $accesorio['cantidad-accesorio'] > 0) {
                $cotizacionDetalle[] = $accesorio;
            }
        }

        // validar Existan Datos a Guardar.
        if (!empty($cotizacion) && count($cotizacionDetalle) > 0) {

            $mensajeCotizacion = "SE HA GENERADO UN ERROR REGISTRANDO LA COTIZACION";

            // Guardar cabecera cotizacion
            $cotizacion_id = CotizacionDB::agregarCotizacion($cotizacion);
            
            $serial = $usuario->codigo.'-'.$cotizacion_id;
            $actualizarSerial = CotizacionDB::actualizarSerialCotizacion($cotizacion_id, $serial);
            //Validar qse guardo la cotizacion y actualizo el serial
            if ($cotizacion_id > 0 && $actualizarSerial) {
                $mensajeCotizacion = "COTIZACION GENERADA!";
                $accesoriosCotizados = CotizacionDB::agregarAccesoriosCotizados($cotizacion_id, $cotizacionDetalle);
            }
        } else {
            $mensajeCotizacion = "NO EXISTEN DATOS PARA GENERAR UNA COTIZACION";
        }
    } else {
        $mensajeCotizacion = "NO EXISTEN DATOS PARA GENERAR UNA COTIZACION";
    }

    $smarty->assign("cotizacion_id", $cotizacion_id);
    $smarty->assign("mensajeCotizacion", $mensajeCotizacion);
    $smarty->display("sections/cotizador/generarCotizacion.tpl");
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}

