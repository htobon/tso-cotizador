<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
require_once './generarPdf.php';
require_once './generarCsv.php';
require_once './sendEmail.php';

use db\UsuarioDB;
use db\ClienteDB;
use db\CotizacionDB;
use utils\Constantes;
use utils\Sesion;


if (Sesion::sesionActiva()) {

    $datos = $_POST;

    $cotizacion_id = 0;
    $mensajeCotizacion = "";
    $mensajeCorreosEnviados = "";
    $error = true;
    $nombreCotizacion = "#";




    if (!empty($datos)) {

        $cotizacion = array();
        $cotizacionDetalle = array();


        $usuario = UsuarioDB::getUsuarioPorID(Sesion::getVariable(Constantes::SESION_USER_ID));
        $cliente = ClienteDB::getCliente($datos["nit"]);


        // Si el Cliente no Existe lo creo
        if ($cliente->id == null) {

            $cliente->nit = $datos["nit"];
            $cliente->nombre = $datos["empresa"];

            $_c = ClienteDB::agregarCliente($cliente);
            if ($_c > 0) {
                $cliente = ClienteDB::getCliente($datos["nit"]);
            }
        }

        // Cabecera Cotizacion
        $cotizacion["usuario_id"] = $usuario->id;
        $cotizacion["cliente_id"] = $cliente->id;
        $cotizacion["nombre_contacto"] = $datos['nombre'];
        $cotizacion["correo_contacto"] = $datos['correo'];
        $cotizacion["correo_alterno_contacto"] = $datos['correo2'];
        $cotizacion["unidad_gps_id"] = $datos["gps"];
        $cotizacion["tipo_contrato_id"] = $datos["contrato"];
        $cotizacion["plan_servicio_id"] = $datos["plan"];
        $cotizacion["descuento_id"] = $datos["descuento"];
        $cotizacion["duracion_contrato_id"] = $datos["duracion"];

        if ($datos["duracion"] == "-1")
            $cotizacion["duracion_contrato_id"] = 1;

        $cotizacion["cantidad_vehiculos"] = $datos["cantidad-unidad-gps"];
        $cotizacion["valor_recurrencia"] = $datos["valor_recurrencia"];
        $cotizacion["valor_equipos"] = $datos["valor_equipos"];
        $cotizacion["valor_total"] = $datos["valor_total"];
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

            $serial = $usuario->codigo . '-' . $cotizacion_id;
            $actualizarSerial = CotizacionDB::actualizarSerialCotizacion($cotizacion_id, $serial);
            //Validar qse guardo la cotizacion y actualizo el serial
            if ($cotizacion_id > 0 && $actualizarSerial) {
                $mensajeCotizacion = "COTIZACION GENERADA!";
                $accesoriosCotizados = CotizacionDB::agregarAccesoriosCotizados($cotizacion_id, $cotizacionDetalle);

                $error = false;
                // Generar Pdf 
                $_pdf = new generarPdf($cotizacion_id);
                $_pdf->generarCotizacionPdf();
                $_pdf->getPdf();
                $cotizacionPdf = $_pdf->getPdfbase64();
                $nombreCotizacion = "/tmp/pdf/" . $_pdf->getNamePdf();

                //Generar CSV
                $csv = new generarCsv($cotizacion_id);
                $csv->generarArchivosPlano();

                // Enviar por Correo Electronico
                $enviarCorreo = new sendPdfEmail("Cotizacion #{{$serial}} TSO-mobile", "cotizacion-{$serial}.pdf", $cotizacionPdf);
                $enviarCorreo->setTo($cotizacion["nombre_contacto"], $cotizacion["correo_contacto"], $cotizacion["correo_alterno_contacto"]);
                $enviarCorreo->setFrom($usuario->nombres . " " . $usuario->apellidos, $usuario->correo, $usuario->firma);

                if ($enviarCorreo->enviarEmail()) {

                    $mensajeCorreosEnviados = "Cotizacion Enviada a : {$cotizacion["correo_contacto"]}";
                    if (!empty($cotizacion["correo_alterno_contacto"])) {
                        $mensajeCorreosEnviados .=" - {$cotizacion["correo_alterno_contacto"]}";
                    }

                    //echo "Se Envia Correo - funcion Enviar";
                } else {

                    $mensajeCorreosEnviados = "No se Puedo enviar Email a : {$cotizacion["correo_contacto"]}";
                    if (!empty($cotizacion["correo_alterno_contacto"])) {
                        $mensajeCorreosEnviados .=" - {$cotizacion["correo_alterno_contacto"]}";
                    }
                    //echo "No se envia correo - funcion Enviar";
                }
            }
        } else {
            $mensajeCotizacion = "NO EXISTEN DATOS PARA GENERAR UNA COTIZACION.";
        }
    } else {
        $mensajeCotizacion = "NO EXISTEN DATOS PARA GENERAR UNA COTIZACION..";
    }

    //$smarty->assign("cotizacion_id", $cotizacion_id);
    $smarty->assign("error", $error);
    $smarty->assign("nombreCotizacion", $nombreCotizacion);
    $smarty->assign("mensajeCotizacion", $mensajeCotizacion);
    $smarty->assign("mensajeCorreosEnviados", $mensajeCorreosEnviados);
    $smarty->display("sections/cotizador/generarCotizacion.tpl");
} else {
    $smarty->assign("ocultarLogout", 1);
    $smarty->assign("error", "Usted debe iniciar sesión primero.");
    $smarty->display("index-iniciarSesion.tpl");
}

