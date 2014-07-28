<?php

namespace db;

require_once (__DIR__ . "/../../config/db.php");

use stdClass;

//print"<pre>"; print_r($_REQUEST);print"</pre>";

class CotizacionDB {

    public static function agregarCotizacion($cotizacion) {

        $conn = getConn();
        $sql = "INSERT INTO tso_cotizaciones (usuario_id,cliente_id,unidad_gps_id,tipo_contrato_id,plan_servicio_id,descuento_id,duracion_contrato_id,cantidad_vehiculos,fecha,serial, 
                nombre_contacto, correo_contacto, correo_alterno_contacto, valor_recurrencia, valor_equipos, valor_total) VALUES (?,?,?,?,?,?,?,?,CURDATE(),?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cotizacion['usuario_id']);
        $stmt->bindValue(2, $cotizacion['cliente_id']);
        $stmt->bindValue(3, $cotizacion['unidad_gps_id']);
        $stmt->bindValue(4, $cotizacion['tipo_contrato_id']);
        $stmt->bindValue(5, $cotizacion['plan_servicio_id']);
        $stmt->bindValue(6, $cotizacion['descuento_id']);
        $stmt->bindValue(7, $cotizacion['duracion_contrato_id']);
        $stmt->bindValue(8, $cotizacion['cantidad_vehiculos']);
        $stmt->bindValue(9, $cotizacion['serial']);
        $stmt->bindValue(10, $cotizacion['nombre_contacto']);
        $stmt->bindValue(11, $cotizacion['correo_contacto']);
        $stmt->bindValue(12, $cotizacion['correo_alterno_contacto']);
        $stmt->bindValue(13, $cotizacion['valor_recurrencia']);
        $stmt->bindValue(14, $cotizacion['valor_equipos']);
        $stmt->bindValue(15, $cotizacion['valor_total']);

        // Exepcion en caso de que el Insert Falle
        try {
            $inserted_rows = $stmt->execute();
            $cotizacion_id = $conn->lastInsertId();

            return $cotizacion_id;
        } catch (\Doctrine\DBAL\DBALException $exc) {
            echo $exc->getTraceAsString();
            return 0;
        }
    }

    public static function agregarAccesoriosCotizados($cotizacion_id, $cotizacionDetalle) {
        $conn = getConn();

        $sql = "INSERT INTO tso_accesorios_cotizados (cotizacion_id,accesorio_id,cantidad_accesorio) VALUES ";

        for ($i = 0; $i < count($cotizacionDetalle); $i++) {
            $sql .= ($i > 0) ? ", ( {$cotizacion_id}, ?, ? )" : "( {$cotizacion_id}, ?, ?)";
        }

        $stmt = $conn->prepare($sql);

        for ($i = 0, $j = 1; $i < count($cotizacionDetalle); $i++, $j+=2) {
            $stmt->bindValue(($j), $cotizacionDetalle[$i]["id"]);
            $stmt->bindValue(($j + 1), $cotizacionDetalle[$i]["cantidad-accesorio"]);
        }

        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0);
    }

    public static function getCotizacion($id) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_cotizaciones WHERE id = ?";

        $sql = "SELECT
                a.id,
                a.usuario_id, 
                b.salesforce_id,
                b.nombres as nombre_vendedor, 
                a.cliente_id,
                c.nit, 
                c.nombre as cliente,
                a.nombre_contacto,
                a.correo_contacto,
                a.correo_alterno_contacto,
                a.unidad_gps_id,
                d.cod_unidad,
                d.nombre as unidad_gps,
                a.tipo_contrato_id,
                e.nombre as tipo_contrato,
                a.plan_servicio_id,
                f.codigo as codigo_plan,
                f.nombre as nombre_plan,
                a.descuento_id,
                g.descuento,
                a.duracion_contrato_id,
                h.cantidad_meses,
                a.cantidad_vehiculos,
                a.valor_recurrencia, 
                a.valor_equipos, 
                a.valor_total,
                a.fecha,
                a.serial
                FROM tso_cotizaciones a
                inner join tso_usuarios b on a.usuario_id = b.id
                inner join tso_clientes c on a.cliente_id = c.id
                inner join tso_unidades_gps d on a.unidad_gps_id = d.id
                inner join tso_tipos_contrato e on a.tipo_contrato_id = e.id
                inner join tso_planes_servicio f on a.plan_servicio_id = f.id
                inner join tso_descuentos_cantidad_vehiculos g on a.descuento_id = g.id
                inner join tso_duracion_contratos h on a.duracion_contrato_id = h.id 
                WHERE a.id = ? ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        $_cotizacion = $stmt->fetch();

        if (isset($_cotizacion)) {
            $cotizacion = new stdClass();
            $cotizacion->id = $_cotizacion['id'];
            $cotizacion->usuario_id = $_cotizacion['usuario_id'];
            $cotizacion->salesforce_id = $_cotizacion['salesforce_id'];
            $cotizacion->nombre_vendedor = $_cotizacion['nombre_vendedor'];
            $cotizacion->cliente_id = $_cotizacion['cliente_id'];
            $cotizacion->nit = $_cotizacion['nit'];
            $cotizacion->cliente = $_cotizacion['cliente'];
            $cotizacion->nombre_contacto = $_cotizacion['nombre_contacto'];
            $cotizacion->correo_contacto = $_cotizacion['correo_contacto'];
            $cotizacion->correo_alterno_contacto = $_cotizacion['correo_alterno_contacto'];
            $cotizacion->unidad_gps_id = $_cotizacion['unidad_gps_id'];
            $cotizacion->cod_unidad = $_cotizacion['cod_unidad'];
            $cotizacion->unidad_gps = $_cotizacion['unidad_gps'];
            $cotizacion->tipo_contrato_id = $_cotizacion['tipo_contrato_id'];
            $cotizacion->tipo_contrato = $_cotizacion['tipo_contrato'];
            $cotizacion->plan_servicio_id = $_cotizacion['plan_servicio_id'];
            $cotizacion->codigo_plan = $_cotizacion['codigo_plan'];
            $cotizacion->nombre_plan = $_cotizacion['nombre_plan'];
            $cotizacion->descuento_id = $_cotizacion['descuento_id'];
            $cotizacion->descuento = $_cotizacion['descuento'];
            $cotizacion->duracion_contrato_id = $_cotizacion['duracion_contrato_id'];
            $cotizacion->cantidad_meses = $_cotizacion['cantidad_meses'];
            $cotizacion->cantidad_vehiculos = $_cotizacion['cantidad_vehiculos'];
            $cotizacion->valor_recurrencia = $_cotizacion['valor_recurrencia'];
            $cotizacion->valor_equipos = $_cotizacion['valor_equipos'];
            $cotizacion->valor_total = $_cotizacion['valor_total'];
            $cotizacion->fecha = $_cotizacion['fecha'];
            $cotizacion->serial = $_cotizacion['serial'];

            return $cotizacion;
        } else {
            return NULL;
        }
    }

    public static function getAccesoriosCotizados($cotizacionId) {
        $conn = getConn();
        $sql = "SELECT * FROM tso_accesorios_cotizados WHERE cotizacion_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cotizacionId);
        $stmt->execute();
        $accesorios = array();
        $_accesorioCotizado = $stmt->fetchAll();

        foreach ($_accesorioCotizado as $a) {
            if (isset($a)) {
                $accesorio = new stdClass();
                $accesorio->id = $a["id"];
                $accesorio->cotizacion_id = $a["cotizacion_id"];
                $accesorio->accesorio_id = $a["accesorio_id"];
                $accesorio->cantidad_accesorio = $a["cantidad_accesorio"];
                array_push($accesorios, $accesorio);
            }
        }

        return (count($accesorios) <= 0 ) ? null : $accesorios;
    }

    public static function getDetalleCotizacion($cotizacionId) {
        $conn = getConn();

        $sql = "SELECT 
                a.id, 
                a.cotizacion_id, 
                a.accesorio_id, 
                b.cod_accesorio as codigo_accesorio,  
                b.precio_accesorio, 
                a.cantidad_accesorio,
                d.descuento,
                CASE c.tipo_contrato_id when 1 then e.cantidad_meses else 0 end as cantidad_meses
                FROM tso_accesorios_cotizados a
                inner join tso_accesorios b on a.accesorio_id = b.id
                inner join tso_cotizaciones c on a.cotizacion_id = c.id
                inner join tso_descuentos_cantidad_vehiculos d on c.descuento_id = d.id
                inner join tso_duracion_contratos e on c.duracion_contrato_id = e.id
                WHERE a.cotizacion_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cotizacionId);
        $stmt->execute();
        $accesorios = array();
        $_accesorioCotizado = $stmt->fetchAll();

        foreach ($_accesorioCotizado as $a) {
            if (isset($a)) {
                $accesorio = new stdClass();
                $accesorio->id = $a["id"];
                $accesorio->cotizacion_id = $a["cotizacion_id"];
                $accesorio->accesorio_id = $a["accesorio_id"];
                $accesorio->codigo_accesorio = $a["codigo_accesorio"];
                $accesorio->precio_accesorio = $a["precio_accesorio"];
                $accesorio->cantidad_accesorio = $a["cantidad_accesorio"];
                $accesorio->descuento = $a["descuento"];
                $accesorio->cantidad_meses = $a["cantidad_meses"];
                array_push($accesorios, $accesorio);
            }
        }

        return (count($accesorios) <= 0 ) ? null : $accesorios;
    }

    public static function actualizarSerialCotizacion($cotizacionId, $serial) {

        $conn = getConn();
        $sql = "UPDATE tso_cotizaciones set serial= ? where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $serial);
        $stmt->bindValue(2, $cotizacionId);
        $inserted_rows = $stmt->execute();

        return ($inserted_rows > 0) ? true : false;
    }

    public static function listarCotizaciones() {
        $conn = getConn();

        $sql = "SELECT
                a.id,
                a.usuario_id, 
                b.salesforce_id,
                b.codigo as codigo_vendedor,
                b.nombres as nombre_vendedor, 
                a.cliente_id,
                c.nit, 
                c.nombre as cliente,
                a.nombre_contacto,
                a.correo_contacto,
                a.correo_alterno_contacto,
                a.unidad_gps_id,
                d.cod_unidad,
                d.nombre as unidad_gps,
                a.tipo_contrato_id,
                e.nombre as tipo_contrato,
                a.plan_servicio_id,
                f.codigo as codigo_plan,
                f.nombre as nombre_plan,
                a.descuento_id,
                g.descuento,
                a.duracion_contrato_id,
                h.cantidad_meses,
                a.cantidad_vehiculos,
                a.valor_recurrencia, 
                CONCAT('$', FORMAT(a.valor_recurrencia, 2)) as formato_valor_recurrencia,
                a.valor_equipos, 
                CONCAT('$', FORMAT(a.valor_equipos, 2)) as formato_valor_equipos,
                a.valor_total,
                CONCAT('$', FORMAT(a.valor_total, 2)) as formato_valor_total,
                a.fecha,
                a.serial
                FROM tso_cotizaciones a
                inner join tso_usuarios b on a.usuario_id = b.id
                inner join tso_clientes c on a.cliente_id = c.id
                inner join tso_unidades_gps d on a.unidad_gps_id = d.id
                inner join tso_tipos_contrato e on a.tipo_contrato_id = e.id
                inner join tso_planes_servicio f on a.plan_servicio_id = f.id
                inner join tso_descuentos_cantidad_vehiculos g on a.descuento_id = g.id
                inner join tso_duracion_contratos h on a.duracion_contrato_id = h.id 
                order by a.fecha desc";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $cotizaciones = array();
        $datos = $stmt->fetchAll();

        foreach ($datos as $_cotizacion) {
            if (isset($_cotizacion)) {
                $cotizacion = new stdClass();
                $cotizacion->id = $_cotizacion['id'];
                $cotizacion->usuario_id = $_cotizacion['usuario_id'];
                $cotizacion->salesforce_id = $_cotizacion['salesforce_id'];
                $cotizacion->codigo_vendedor = $_cotizacion['codigo_vendedor'];
                $cotizacion->nombre_vendedor = $_cotizacion['nombre_vendedor'];
                $cotizacion->cliente_id = $_cotizacion['cliente_id'];
                $cotizacion->nit = $_cotizacion['nit'];
                $cotizacion->cliente = $_cotizacion['cliente'];
                $cotizacion->nombre_contacto = $_cotizacion['nombre_contacto'];
                $cotizacion->correo_contacto = $_cotizacion['correo_contacto'];
                $cotizacion->correo_alterno_contacto = $_cotizacion['correo_alterno_contacto'];
                $cotizacion->unidad_gps_id = $_cotizacion['unidad_gps_id'];
                $cotizacion->cod_unidad = $_cotizacion['cod_unidad'];
                $cotizacion->unidad_gps = $_cotizacion['unidad_gps'];
                $cotizacion->tipo_contrato_id = $_cotizacion['tipo_contrato_id'];
                $cotizacion->tipo_contrato = $_cotizacion['tipo_contrato'];
                $cotizacion->plan_servicio_id = $_cotizacion['plan_servicio_id'];
                $cotizacion->codigo_plan = $_cotizacion['codigo_plan'];
                $cotizacion->nombre_plan = $_cotizacion['nombre_plan'];
                $cotizacion->descuento_id = $_cotizacion['descuento_id'];
                $cotizacion->descuento = $_cotizacion['descuento'];
                $cotizacion->duracion_contrato_id = $_cotizacion['duracion_contrato_id'];
                $cotizacion->cantidad_meses = $_cotizacion['cantidad_meses'];
                $cotizacion->cantidad_vehiculos = $_cotizacion['cantidad_vehiculos'];
                $cotizacion->valor_recurrencia = $_cotizacion['valor_recurrencia'];
                $cotizacion->formato_valor_recurrencia = $_cotizacion['formato_valor_recurrencia'];
                $cotizacion->valor_equipos = $_cotizacion['valor_equipos'];
                $cotizacion->formato_valor_equipos = $_cotizacion['formato_valor_equipos'];
                $cotizacion->valor_total = $_cotizacion['valor_total'];
                $cotizacion->formato_valor_total = $_cotizacion['formato_valor_total'];
                $cotizacion->fecha = $_cotizacion['fecha'];
                $cotizacion->serial = $_cotizacion['serial'];

                array_push($cotizaciones, $cotizacion);
            }
        }

        return (count($cotizaciones) <= 0 ) ? null : $cotizaciones;
    }

    public static function filtrarCotizaciones($vendedor_id, $fecha_inicial, $fecha_final) {
        $conn = getConn();

        $sql_aux = array();

        if (!empty($vendedor_id) && $vendedor_id != 0) {
            array_push($sql_aux, " usuario_id = {$vendedor_id}");
        }

        if (!empty($fecha_inicial) && $fecha_inicial != "" && !empty($fecha_final) && $fecha_final != "") {
            array_push($sql_aux, " a.fecha between '{$fecha_inicial}' and '{$fecha_final}' ");
        }

        $where = "";
        if (count($sql_aux))
            $where = " where " . implode(" AND ", $sql_aux);

        $sql = "SELECT
                a.id,
                a.usuario_id, 
                b.salesforce_id,
                b.codigo as codigo_vendedor,
                b.nombres as nombre_vendedor, 
                a.cliente_id,
                c.nit, 
                c.nombre as cliente,
                a.nombre_contacto,
                a.correo_contacto,
                a.correo_alterno_contacto,
                a.unidad_gps_id,
                d.cod_unidad,
                d.nombre as unidad_gps,
                a.tipo_contrato_id,
                e.nombre as tipo_contrato,
                a.plan_servicio_id,
                f.codigo as codigo_plan,
                f.nombre as nombre_plan,
                a.descuento_id,
                g.descuento,
                a.duracion_contrato_id,
                h.cantidad_meses,
                a.cantidad_vehiculos,
                a.valor_recurrencia, 
                CONCAT('$', FORMAT(a.valor_recurrencia, 2)) as formato_valor_recurrencia,
                a.valor_equipos, 
                CONCAT('$', FORMAT(a.valor_equipos, 2)) as formato_valor_equipos,
                a.valor_total,
                CONCAT('$', FORMAT(a.valor_total, 2)) as formato_valor_total,
                a.fecha,
                a.serial
                FROM tso_cotizaciones a
                inner join tso_usuarios b on a.usuario_id = b.id
                inner join tso_clientes c on a.cliente_id = c.id
                inner join tso_unidades_gps d on a.unidad_gps_id = d.id
                inner join tso_tipos_contrato e on a.tipo_contrato_id = e.id
                inner join tso_planes_servicio f on a.plan_servicio_id = f.id
                inner join tso_descuentos_cantidad_vehiculos g on a.descuento_id = g.id
                inner join tso_duracion_contratos h on a.duracion_contrato_id = h.id 
                {$where}
                order by a.fecha desc";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $cotizaciones = array();
        $datos = $stmt->fetchAll();

        foreach ($datos as $_cotizacion) {
            if (isset($_cotizacion)) {
                $cotizacion = new stdClass();
                $cotizacion->id = $_cotizacion['id'];
                $cotizacion->usuario_id = $_cotizacion['usuario_id'];
                $cotizacion->salesforce_id = $_cotizacion['salesforce_id'];
                $cotizacion->codigo_vendedor = $_cotizacion['codigo_vendedor'];
                $cotizacion->nombre_vendedor = $_cotizacion['nombre_vendedor'];
                $cotizacion->cliente_id = $_cotizacion['cliente_id'];
                $cotizacion->nit = $_cotizacion['nit'];
                $cotizacion->cliente = $_cotizacion['cliente'];
                $cotizacion->nombre_contacto = $_cotizacion['nombre_contacto'];
                $cotizacion->correo_contacto = $_cotizacion['correo_contacto'];
                $cotizacion->correo_alterno_contacto = $_cotizacion['correo_alterno_contacto'];
                $cotizacion->unidad_gps_id = $_cotizacion['unidad_gps_id'];
                $cotizacion->cod_unidad = $_cotizacion['cod_unidad'];
                $cotizacion->unidad_gps = $_cotizacion['unidad_gps'];
                $cotizacion->tipo_contrato_id = $_cotizacion['tipo_contrato_id'];
                $cotizacion->tipo_contrato = $_cotizacion['tipo_contrato'];
                $cotizacion->plan_servicio_id = $_cotizacion['plan_servicio_id'];
                $cotizacion->codigo_plan = $_cotizacion['codigo_plan'];
                $cotizacion->nombre_plan = $_cotizacion['nombre_plan'];
                $cotizacion->descuento_id = $_cotizacion['descuento_id'];
                $cotizacion->descuento = $_cotizacion['descuento'];
                $cotizacion->duracion_contrato_id = $_cotizacion['duracion_contrato_id'];
                $cotizacion->cantidad_meses = $_cotizacion['cantidad_meses'];
                $cotizacion->cantidad_vehiculos = $_cotizacion['cantidad_vehiculos'];
                $cotizacion->valor_recurrencia = $_cotizacion['valor_recurrencia'];
                $cotizacion->formato_valor_recurrencia = $_cotizacion['formato_valor_recurrencia'];
                $cotizacion->valor_equipos = $_cotizacion['valor_equipos'];
                $cotizacion->formato_valor_equipos = $_cotizacion['formato_valor_equipos'];
                $cotizacion->valor_total = $_cotizacion['valor_total'];
                $cotizacion->formato_valor_total = $_cotizacion['formato_valor_total'];
                $cotizacion->fecha = $_cotizacion['fecha'];
                $cotizacion->serial = $_cotizacion['serial'];

                array_push($cotizaciones, $cotizacion);
            }
        }

        return (count($cotizaciones) <= 0 ) ? $cotizaciones : $cotizaciones;
    }

}

?>