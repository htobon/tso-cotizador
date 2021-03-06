$(document).on('pageinit', function()
{
    $(".point").bind("tap", eventoPuntosTap);

    // Evento cuando selecciona una unidad GPS     
    $("input[name='gps']").on("click", function(event) {

        $("#unidad-gps").addClass("seleccionado");
        $("#checkbox-unidad-gps").prop('checked', true).checkboxradio('refresh');
        $("#modal-unidad-gps").popup("close");
        validarRestricciones();

        if (isJqmGhostClick(event)) {
            return false;
        }

        // Actualizando la lista de Cantidad Accesorios en el formulario de #adicionales.    
        var $point = $(event.target);
        var $labelGPS = $("#seleccion-accesorios #modal-unidad-gps").find("label[for='gps-" + $point.val() + "']");
        var $labelCantidadGPS = $("#tabla-cantidad-accesorios #unidad-gps").find("label");
        $labelCantidadGPS.text($labelGPS.text());

    });

    // Evento cuando se selecciona un plan
    $("#planes-servicio select#plan").on("change", validarRestricciones);

    //Evento para abrir panel deslizando el dedo a la izquierda
    $("body").on("swipeleft", abrirPanel);

    //Evento para mostrar el número de meses si se ha seleccionado tipo de plan "comodato".
    $("#adicionales #contrato").on("change", function(event) {
        if ($(event.target).val() === "1") {
            $("#adicionales #duraciones").show("slow");
        } else {
            $("#adicionales #duraciones").hide("slow");
        }
    });


    // Validar Cantidades Ingresadas de los Accesorios Seleccionados
    $("input[name*=cantidad-accesorio]").bind('keyup change', function(e) {

        if (isJqmGhostClick(e)) {
            return false;
        }

        // Convierto a Número Entero
        var cantidadUnidadGps = parseInt($('#cantidad-unidad-gps').val(), 10);
        var cantidadIngresada = parseInt($(this).val(), 10);

        // Validar que los valores no sean Negativos ni mayores a la unidad GPS 
        if (cantidadIngresada <= 0 || cantidadIngresada > cantidadUnidadGps)
            $(this).val("");

    });


    //buscar cliente por Nit
    /*$('#nit').on('change', function(e) {
     console.log('Search Client');
     $.ajax({
     url: 'actions.php',
     data: {action: 'buscarClientePorNit', nit: $(this).val()},
     dataType: 'json',
     method: 'post',
     success: function(response) {
     $('#empresa').val("");
     if (response.id !== null) {
     $('#empresa').val(response.nombre);
     }
     },
     complete: function() {
     },
     error: function(e) {
     console.log('error', e);
     }
     });
     });*/

    $("#autocomplete").on("filterablebeforefilter", function(e, data) {

        //console.log('here');
        var $ul = $(this);
        var $input = $(data.input);
        var value = $input.val();
        var html = "";

        $('#nit').val("");
        $('#empresa').val($input.val());
        $ul.html("");

        // SI Ingresan Mas de Dos Caracteres-> Empiece a buscar
        if (value && value.length > 2) {
            //console.log('here');
            $ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
            $ul.listview("refresh");

            $.ajax({
                url: 'actions.php',
                data: {action: 'buscarClientePorNombre', empresa: $input.val()},
                dataType: 'json',
                method: 'post',
            })
                    .then(function(response) {
                        //console.log(response);
                        if (response !== null) {
                            $.each(response, function(i, val) {
                                //console.log(val);
                                html += "<li id='" + val.nit + "'>" + val.nombre + "</li>";
                            });
                            $ul.html(html);
                            $ul.listview("refresh");
                            $ul.trigger("updatelayout");
                        }
                    });
        }


    });

    //Cuando le da Click a la empresa seleccionada
    $(document).on("click", "#autocomplete li", function() {
        var selectedItem = $(this).html();
        $('#nit').val($(this).attr('id'));
        $('#empresa').val(selectedItem);
        $(this).parent().parent().find('input').val(selectedItem);
        $('#autocomplete').hide();
    });



    /*
     * ********************** PRE-VISUALIZACIÓN DE COTIZACIÓN ************************
     */
    // Variables Unidad GPS.
    var precioUnidadGPS;
    var totalPrecioUnidadGPS;
    var cantidadUnidadesGPS;

    // Variables Accesorios.
    var totalPrecioUnitarioAccesorios;
    var totalPrecioAccesorios;
    var totalCantidadAccesorios;

    // Variables Instalaciones (Unidad GPS + Accesorios).
    var totalPrecioInstalacionUnitariaAccesorios;
    var totalPrecioInstalacionAccesorios;
    var totalCantidadInstalaciones;

    // Variables Tipo de Plan.
    var totalPrecioUnitarioTipoPlan;
    var totalPrecioTipoPlan;

    // Variables Descuentos.
    var porcentajeDescuento;
    var cantidadMesesComodato;
    var totalDescuentosUnitarios;
    var totalDescuentos;

    // Variables Totales.
    var totalValorPlanSinDescuentoUnitario;
    var totalValorPlanSinDescuento;
    var totalDescuentoUnitarioTotal;
    var totalDescuentoTotal;
    var totalValorPlanMensualUnitario;
    var totalValorPlanMensual;

    // Se corre este evento antes de que la sección de previsualización de la cotización se muestre.

    $("#prev-cotizacion").on("pagebeforeshow", function(event) {
        // Evitando Ghost Click
        if (isJqmGhostClick(event)) {
            return false;
        }

        // 1. Resetear todos los items para que se oculten.
        $("#prev-cotizacion .item").hide();

        // 2. Mostrar la información de aquellos items que se seleccionaron en la cotización.

        ////// Gps:
        var gpsId = $("input[name=gps]:checked", "#seleccion-accesorios").val();
        if (gpsId !== undefined) {
            $("#prev-cotizacion #gps-" + gpsId).show();
            // Cantidad.
            cantidadUnidadesGPS = $("#tabla-cantidad-accesorios #cantidad-unidad-gps").val();
            $("#prev-cotizacion #gps-" + gpsId).find(".cantidad").html(cantidadUnidadesGPS);

            precioUnidadGPS = 0;
            totalPrecioUnidadGPS = 0;
            // arregloGpsJSON es una variable en JSON que proviene desde el TPL.
            // Encontrando al GPS y cambiando el label de nombre.
            for (var i = 0; i < arregloGpsJSON.length; i++) {
                if (arregloGpsJSON[i].id === gpsId) {
                    // Precio Unitario
                    var numero = Number(arregloGpsJSON[i].precioUnidad);
                    precioUnidadGPS = numero;
                    numero = Number(numero.toFixed(1)).toLocaleString(); // Formateando a moneda.
                    $("#prev-cotizacion #gps-" + gpsId).find(".precioUnitario").html("$" + numero);

                    numero = precioUnidadGPS * cantidadUnidadesGPS;
                    // Asignando total unidad GPS.
                    totalPrecioUnidadGPS = numero;
                    numero = Number(numero.toFixed(1)).toLocaleString(); // Formateando a moneda.
                    $("#prev-cotizacion #gps-" + gpsId).find(".precioTotal").html("$" + numero);
                    i = arregloGpsJSON.length;
                }
            }
        }

        ////// Accesorios e Instalaciones:
        var accesoriosIds = $(".point.seleccionado", "#seleccion-accesorios");

        ////// Precios Accesorios.
        totalPrecioUnitarioAccesorios = 0;
        totalPrecioAccesorios = 0;
        totalCantidadAccesorios = 0;
        accesoriosIds.each(function() {
            // Accesorio id unidad-gps es el GPS y no se necesita aquí.
            // accesorioId = accesorio-##
            var accesorioId = $(this).attr("id");
            if ($(this).attr("id") !== "unidad-gps") {
                // Mostrando el item de accesorio.
                $("#prev-cotizacion #" + accesorioId).show();
                // Actualizando cantidad.
                var cantidadAccesorio = $("#tabla-cantidad-accesorios #" + accesorioId).find("input").val();
                totalCantidadAccesorios += Number(cantidadAccesorio);
                var valorAccesorio = 0;
                var valorAccesorioUnitario = 0;
                // accesoriosJSON es una variable en JSON que proviene desde el TPL.
                // Encontrando el accesorio y calculando el valor por cantidad.
                for (var i = 0; i < accesoriosJSON.length; i++) {
                    if ("accesorio-" + accesoriosJSON[i].id === accesorioId) {
                        valorAccesorioUnitario = Number(accesoriosJSON[i].precioAccesorio);
                        valorAccesorio = valorAccesorioUnitario * cantidadAccesorio;
                        i = accesoriosJSON.length; // parando el ciclo.
                    }
                }

                // Sumando a los totales
                totalPrecioUnitarioAccesorios += valorAccesorioUnitario;
                totalPrecioAccesorios += valorAccesorio;

                // Actualizando interfaz con cantidad y valores del accesorio.
                $("#prev-cotizacion #" + accesorioId).find(".cantidad").html(cantidadAccesorio);
                var numero = Number(valorAccesorioUnitario.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #" + accesorioId).find(".precioUnitario").html("$" + numero);
                numero = Number(valorAccesorio.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #" + accesorioId).find(".precioTotal").html("$" + numero);
            }
        });

        ////// Precios Instalación Accesorios.
        totalPrecioInstalacionUnitariaAccesorios = 0;
        totalPrecioInstalacionAccesorios = 0;
        totalCantidadInstalaciones = 0;
        accesoriosIds.each(function() {
            // id = accesorio-##
            // Accesorio id unidad-gps es el GPS y no se necesita aquí.
            var accesorioId = $(this).attr("id");
            if ($(this).attr("id") !== "unidad-gps") {
                // Mostrando el item de instalación accesorio.
                $("#prev-cotizacion #instalacion-" + accesorioId).show();
                // Actualizando cantidad.
                var cantidadAccesorio = $("#tabla-cantidad-accesorios #" + accesorioId).find("input").val();
                totalCantidadInstalaciones += Number(cantidadAccesorio);
                var valorInstalacionAccesorio = 0;
                var valorInstalacionAccesorioUnitario = 0;
                // accesoriosJSON es una variable en JSON que proviene desde el TPL.
                // Encontrando el accesorio y calculando el valor por cantidad.
                for (var i = 0; i < accesoriosJSON.length; i++) {
                    if ("accesorio-" + accesoriosJSON[i].id === accesorioId) {
                        valorInstalacionAccesorioUnitario = Number(accesoriosJSON[i].precioInstalacion);
                        valorInstalacionAccesorio = valorInstalacionAccesorioUnitario * cantidadAccesorio;
                        i = accesoriosJSON.length; // parando ciclo.
                    }
                }

                // Sumando los totales
                totalPrecioInstalacionUnitariaAccesorios += valorInstalacionAccesorioUnitario;
                totalPrecioInstalacionAccesorios += valorInstalacionAccesorio;

                // Actualizando cantidad y valor de la instalación del accesorio.
                $("#prev-cotizacion #instalacion-" + accesorioId).find(".cantidad").html(cantidadAccesorio);
                var numero = Number(valorInstalacionAccesorioUnitario.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #instalacion-" + accesorioId).find(".precioUnitario").html("$" + numero);
                numero = Number(valorInstalacionAccesorio.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #instalacion-" + accesorioId).find(".precioTotal").html("$" + numero);
            }
        });

        ////// Precios Instalación Unidad GPS.
        if (gpsId !== undefined) {
            $("#prev-cotizacion #instalacion-gps-" + gpsId).show();
            $("#prev-cotizacion #instalacion-gps-" + gpsId).find(".cantidad").html(cantidadUnidadesGPS);

            var precioInstalacionUnidadGPS = 0;
            var totalPrecioInstalacionUnidadGPS = 0;
            // arregloGpsJSON es una variable en JSON que proviene desde el TPL.
            // Encontrando al GPS y cambiando el label de nombre.
            for (var i = 0; i < arregloGpsJSON.length; i++) {
                if (arregloGpsJSON[i].id === gpsId) {
                    // Precio Unitario de la Instalacion
                    precioInstalacionUnidadGPS = Number(arregloGpsJSON[i].precioInstalacion);
                    var numero = Number(precioInstalacionUnidadGPS.toFixed(1)).toLocaleString(); // Formateando a moneda.
                    $("#prev-cotizacion #instalacion-gps-" + gpsId).find(".precioUnitario").html("$" + numero);

                    totalPrecioInstalacionUnidadGPS = precioInstalacionUnidadGPS * cantidadUnidadesGPS;
                    numero = Number(totalPrecioInstalacionUnidadGPS.toFixed(1)).toLocaleString(); // Formateando a moneda.
                    $("#prev-cotizacion #instalacion-gps-" + gpsId).find(".precioTotal").html("$" + numero);

                    // Sumando a los totales.
                    totalCantidadInstalaciones += Number(cantidadUnidadesGPS);
                    totalPrecioInstalacionUnitariaAccesorios += precioInstalacionUnidadGPS;
                    totalPrecioInstalacionAccesorios += totalPrecioInstalacionUnidadGPS;
                    i = arregloGpsJSON.length;
                }
            }
        }

        ////// Tipo de Plan
        totalPrecioUnitarioTipoPlan = 0;
        totalPrecioTipoPlan = 0;
        var planServicioId = $("#plan", "#adicionales").val();
        if (planServicioId !== -1) {
            // Mostrando el plan de servicio.
            $("#prev-cotizacion #plan-" + planServicioId).show();
            // Buscando el precio del plan para guardarlo en variable totalPlanServicios
            // para usarlo posteriormente.
            for (var i = 0; i < planesJSON.length; i++) {
                if (planesJSON[i].id === planServicioId) {
                    // Aplicando cantidad del plan dependiendo de las unidades GPS.
                    $("#prev-cotizacion #plan-" + planServicioId).find(".cantidad").html(cantidadUnidadesGPS);

                    // Calculando el precio del tipo de plan.
                    var precioTipoPlan = Number(planesJSON[i].precio);
                    totalPrecioUnitarioTipoPlan += precioTipoPlan;
                    var numero = Number(precioTipoPlan.toFixed(1)).toLocaleString();
                    $("#prev-cotizacion #plan-" + planServicioId).find(".precioUnitario").html("$" + numero);

                    var precioTipoPlanTotal = precioTipoPlan * cantidadUnidadesGPS;
                    totalPrecioTipoPlan += precioTipoPlanTotal;
                    numero = Number(precioTipoPlanTotal.toFixed(1)).toLocaleString();
                    $("#prev-cotizacion #plan-" + planServicioId).find(".precioTotal").html("$" + numero);

                    i = planesJSON.length; // Parando ciclo.
                }
            }
            // mensualidades de accesorios
            accesoriosIds.each(function() {
                // id = accesorio-##
                // Accesorio id unidad-gps es el GPS y no se necesita aquí.
                var accesorioId = $(this).attr("id");
                if ($(this).attr("id") !== "unidad-gps") {
                    // Mostrando el item de mensualidad accesorio.
                    $("#prev-cotizacion #mensualidad-" + accesorioId).show();

                    // Calculando la cantidad de accesorios.
                    var cantidadAccesorio = $("#tabla-cantidad-accesorios #" + accesorioId).find("input").val();

                    // accesoriosJSON es una variable en JSON que proviene desde el TPL.
                    // Encontrando el accesorio y calculando el valor por cantidad.
                    for (var i = 0; i < accesoriosJSON.length; i++) {
                        if ("accesorio-" + accesoriosJSON[i].id === accesorioId) {

                            // Aplicando valores a la interfaz.
                            $("#prev-cotizacion #mensualidad-" + accesorioId).find(".cantidad").html(cantidadAccesorio);

                            var precioMensualidadUnitario = Number(accesoriosJSON[i].precioMensualidad);
                            totalPrecioUnitarioTipoPlan += precioMensualidadUnitario;
                            var numero = Number(precioMensualidadUnitario.toFixed(1)).toLocaleString();
                            $("#prev-cotizacion #mensualidad-" + accesorioId).find(".precioUnitario").html("$" + numero);

                            var precioMensualidadTotal = precioMensualidadUnitario * cantidadAccesorio;
                            totalPrecioTipoPlan += precioMensualidadTotal;
                            numero = Number(precioMensualidadTotal.toFixed(1)).toLocaleString();
                            $("#prev-cotizacion #mensualidad-" + accesorioId).find(".precioTotal").html("$" + numero);

                            i = accesoriosJSON.length; // parando ciclo.
                        }
                    }
                }
            });
        }

        /////// Cantidad vehiculos. La cantidad de vehículos está dada por la cantidad de unidades GPS.    
        if (cantidadUnidadesGPS !== undefined && $.isNumeric(cantidadUnidadesGPS)) {
            $("#numero-vehiculos .item", "#prev-cotizacion").show();
            $("#numero-vehiculos .item", "#prev-cotizacion").html(cantidadUnidadesGPS);
        }

        ////// Porcentaje de Descuento: El descuento depende de la variable anterior Cantidad de Vehiculos.
        porcentajeDescuento = 0;
        $("#porcentaje-descuento .item", "#prev-cotizacion").show();
        for (var i = 0; i < descuentosJSON.length; i++) {
            if ((Number(cantidadUnidadesGPS) >= Number(descuentosJSON[i].cantidadMin)) &&
                    (Number(cantidadUnidadesGPS) <= Number(descuentosJSON[i].cantidadMax))) {
                //console.log(' Descuentos ',descuentosJSON[i]);
                porcentajeDescuento = Number(descuentosJSON[i].descuento);
                var numero = Number(porcentajeDescuento.toFixed(1)).toLocaleString();
                $("#porcentaje-descuento .item", "#prev-cotizacion").html(numero + "%");
                $("#descuento").val(descuentosJSON[i].id);
                i = descuentosJSON.length; // parando ciclo.
            }
        }

        ////// Tipo Contrato
        cantidadMesesComodato = 0;
        var tipoContrato = $("#contrato", "#adicionales").val();
        if (tipoContrato !== -1) {
            $("#prev-cotizacion #contrato-" + tipoContrato).show();
            // Si tipo de contrato es COMODATO.
            if (tipoContrato === "1") {
                // Mostrar la duración del contrato.
                $("#prev-cotizacion #duracion").show();
                // Si el tipo de contrato es comodato.
                // Averiguar la cantidad de meses (#duraciones).
                var mesesId = $("#duracion", "#adicionales").val();
                for (var i = 0; i < duracionesJSON.length; i++) {
                    if (duracionesJSON[i].id === mesesId) {
                        cantidadMesesComodato = Number(duracionesJSON[i].cantidadMeses);
                        i = duracionesJSON.length;
                    }
                }
                $("#prev-cotizacion #duracion").find(".cantidad").html(cantidadMesesComodato + " meses");
            } else {
                // Si el tipo de contato es COMPRA.
                // 
                // Ocultar Duración del contrato porque se paga todo de una.
                $("#prev-cotizacion #duracion").hide();
            }
        }

        ////// Descuentos.
        totalDescuentosUnitarios = 0;
        totalDescuentos = 0;
        // Calculando descuento del tipo de Plan.
        var planServicioId = $("#plan", "#adicionales").val();
        if (planServicioId !== -1) {
            // Mostrando el descuento del plan de servicio.
            $("#prev-cotizacion #descuento-plan-" + planServicioId).show();
            // Buscando el precio del plan para usarlo posteriormente.
            for (var i = 0; i < planesJSON.length; i++) {
                if (planesJSON[i].id === planServicioId) {
                    // Aplicando cantidad del plan dependiendo de las unidades GPS.
                    $("#prev-cotizacion #descuento-plan-" + planServicioId).find(".cantidad").html(cantidadUnidadesGPS);

                    // Calculando el descuento del tipo de plan.
                    var descuentoTipoPlan = Number(planesJSON[i].precio) * porcentajeDescuento / 100;
                    totalDescuentosUnitarios += descuentoTipoPlan;
                    var numero = Number(descuentoTipoPlan.toFixed(1)).toLocaleString();
                    $("#prev-cotizacion #descuento-plan-" + planServicioId).find(".precioUnitario").html("$" + numero);

                    var descuentoTipoPlanTotal = descuentoTipoPlan * cantidadUnidadesGPS;
                    totalDescuentos += descuentoTipoPlanTotal;
                    numero = Number(descuentoTipoPlanTotal.toFixed(1)).toLocaleString();
                    $("#prev-cotizacion #descuento-plan-" + planServicioId).find(".precioTotal").html("$" + numero);

                    i = planesJSON.length; // Parando ciclo.
                }
            }
        }
        // Calculando descuentos de cada una de las mensualidades.
        accesoriosIds.each(function() {
            // id = accesorio-##
            // Accesorio id unidad-gps es el GPS y no se necesita aquí.
            var accesorioId = $(this).attr("id");
            if ($(this).attr("id") !== "unidad-gps") {
                // Mostrando el item de mensualidad accesorio.
                $("#prev-cotizacion #descuento-mensualidad-" + accesorioId).show();

                // Calculando la cantidad de accesorios.
                var cantidadAccesorio = $("#tabla-cantidad-accesorios #" + accesorioId).find("input").val();

                // accesoriosJSON es una variable en JSON que proviene desde el TPL.
                // Encontrando el accesorio y calculando el valor por cantidad.
                for (var i = 0; i < accesoriosJSON.length; i++) {
                    if ("accesorio-" + accesoriosJSON[i].id === accesorioId) {
                        // Aplicando valores a la interfaz.
                        $("#prev-cotizacion #descuento-mensualidad-" + accesorioId).find(".cantidad").html(cantidadAccesorio);

                        var descuentoMensualidadUnitario = Number(accesoriosJSON[i].precioMensualidad) * porcentajeDescuento / 100;
                        totalDescuentosUnitarios += descuentoMensualidadUnitario;
                        var numero = Number(descuentoMensualidadUnitario.toFixed(1)).toLocaleString();
                        $("#prev-cotizacion #descuento-mensualidad-" + accesorioId).find(".precioUnitario").html("$" + numero);

                        var descuentoMensualidadTotal = descuentoMensualidadUnitario * cantidadAccesorio;
                        totalDescuentos += descuentoMensualidadTotal;
                        numero = Number(descuentoMensualidadTotal.toFixed(1)).toLocaleString();
                        $("#prev-cotizacion #descuento-mensualidad-" + accesorioId).find(".precioTotal").html("$" + numero);

                        i = accesoriosJSON.length; // parando ciclo.
                    }
                }
            }
        });

        ////// TOTALES
        var tipoContrato = $("#contrato", "#adicionales").val();
        if (tipoContrato !== -1) {
            var numero;

            var total_unidad_gps = 0;
            var total_accesorios = 0;
            var total_instalaciones = 0;

            var valor_recurrencia = 0;
            var valor_equipos = 0;
            var valor_total = 0;

            // Si tipo de contrato es COMPRA.
            if (tipoContrato === "2") {
                // Se debe incluir Total Unidad GPS.
                $("#prev-cotizacion #total-unidad-gps").show();
                $("#prev-cotizacion #total-unidad-gps").find(".cantidad").html(cantidadUnidadesGPS);
                numero = Number(precioUnidadGPS.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-unidad-gps").find(".precioUnitario").html("$" + numero);
                numero = Number(totalPrecioUnidadGPS.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-unidad-gps").find(".precioTotal").html("$" + numero);
                total_unidad_gps = Number(totalPrecioUnidadGPS.toFixed(1));
            }
            // Total Accesorios.
            $("#prev-cotizacion #total-accesorios").show();
            $("#prev-cotizacion #total-accesorios").find(".cantidad").html(totalCantidadAccesorios);
            numero = Number(totalPrecioUnitarioAccesorios.toFixed(1)).toLocaleString();
            $("#prev-cotizacion #total-accesorios").find(".precioUnitario").html("$" + numero);
            numero = Number(totalPrecioAccesorios.toFixed(1)).toLocaleString();
            $("#prev-cotizacion #total-accesorios").find(".precioTotal").html("$" + numero);
            total_accesorios = Number(totalPrecioAccesorios.toFixed(1));

            // Total Instalaciones
            $("#prev-cotizacion #total-instalaciones").show();
            $("#prev-cotizacion #total-instalaciones").find(".cantidad").html(totalCantidadInstalaciones);
            numero = Number(totalPrecioInstalacionUnitariaAccesorios.toFixed(1)).toLocaleString();
            $("#prev-cotizacion #total-instalaciones").find(".precioUnitario").html("$" + numero);
            numero = Number(totalPrecioInstalacionAccesorios.toFixed(1)).toLocaleString();
            $("#prev-cotizacion #total-instalaciones").find(".precioTotal").html("$" + numero);
            total_instalaciones = Number(totalPrecioInstalacionAccesorios.toFixed(1));

            //Valor Equipos
            console.log(total_unidad_gps, total_accesorios, total_instalaciones);
            valor_equipos = total_unidad_gps + total_accesorios + total_instalaciones;
            $('#valor_equipos').val(valor_equipos);

            // Total Valor Plan Sin descuento
            totalValorPlanSinDescuentoUnitario = 0;
            if (tipoContrato === "1") {
                // Si tipo de contrato es COMODATO:
                // Valor Plan COMODATO Sin Descuento
                $("#prev-cotizacion #total-plan-comodato-sin-descuento").show();
                $("#prev-cotizacion #total-plan-comodato-sin-descuento").find(".cantidad").html(cantidadUnidadesGPS);
                totalValorPlanSinDescuentoUnitario = Number((precioUnidadGPS / cantidadMesesComodato) + totalPrecioUnitarioTipoPlan);
                numero = Number(totalValorPlanSinDescuentoUnitario.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-comodato-sin-descuento").find(".precioUnitario").html("$" + numero);
                totalValorPlanSinDescuento = totalValorPlanSinDescuentoUnitario * cantidadUnidadesGPS;
                numero = Number(totalValorPlanSinDescuento.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-comodato-sin-descuento").find(".precioTotal").html("$" + numero);
                valor_recurrencia = Number(totalValorPlanSinDescuento.toFixed(1));
                $('#valor_recurrencia').val(valor_recurrencia);
            } else {
                // Si tipo de contrato es COMPRA:
                // Valor Plan Sin Descuento
                $("#prev-cotizacion #total-plan-sin-descuento").show();
                $("#prev-cotizacion #total-plan-sin-descuento").find(".cantidad").html(cantidadUnidadesGPS);
                totalValorPlanSinDescuentoUnitario = totalPrecioUnitarioTipoPlan;
                numero = Number(totalValorPlanSinDescuentoUnitario.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-sin-descuento").find(".precioUnitario").html("$" + numero);
                totalValorPlanSinDescuento = totalPrecioTipoPlan;
                numero = Number(totalValorPlanSinDescuento.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-sin-descuento").find(".precioTotal").html("$" + numero);
                valor_recurrencia = Number(totalValorPlanSinDescuento.toFixed(1));
                $('#valor_recurrencia').val(valor_recurrencia);
            }
            // Valor total
            valor_total = parseFloat(valor_equipos)+ parseFloat(valor_recurrencia);
            $('#valor_total').val(valor_total);


            // Total Descuento
            totalDescuentoUnitarioTotal = 0;
            totalDescuentoTotal = 0;
            $("#prev-cotizacion #total-descuento").show();
            $("#prev-cotizacion #total-descuento").find(".cantidad").html(cantidadUnidadesGPS);
            if (tipoContrato === "1") {
                // Si tipo de contrato es COMODATO:
                totalDescuentoUnitarioTotal = Number(totalValorPlanSinDescuentoUnitario * porcentajeDescuento / 100);
                totalDescuentoTotal = Number(totalDescuentoUnitarioTotal * cantidadUnidadesGPS);
            } else {
                // Si tipo de contrato es COMPRA:
                totalDescuentoUnitarioTotal = totalDescuentosUnitarios;
                totalDescuentoTotal = totalDescuentos;
            }
            numero = Number(totalDescuentoUnitarioTotal.toFixed(1)).toLocaleString();
            $("#prev-cotizacion #total-descuento").find(".precioUnitario").html("$" + numero);
            numero = Number(totalDescuentoTotal.toFixed(1)).toLocaleString();
            $("#prev-cotizacion #total-descuento").find(".precioTotal").html("$" + numero);

            // Total Valor Plan Mensual
            totalValorPlanMensualUnitario = 0;
            totalValorPlanMensual = 0;
            if (tipoContrato === "1") {
                // Si tipo de contrato es COMODATO:
                $("#prev-cotizacion #total-plan-comodato-mensual").show();
                totalValorPlanMensualUnitario = Number(totalValorPlanSinDescuentoUnitario - totalDescuentoUnitarioTotal);
                numero = Number(totalValorPlanMensualUnitario.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-comodato-mensual").find(".precioUnitario").html("$" + numero);
                totalValorPlanMensual = Number(totalValorPlanSinDescuento - totalDescuentoTotal);
                numero = Number(totalValorPlanMensual.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-comodato-mensual").find(".precioTotal").html("$" + numero);
            } else {
                // Si tipo de contrato es COMPRA:
                $("#prev-cotizacion #total-plan-mensual").show();
                totalValorPlanMensualUnitario = Number(totalValorPlanSinDescuentoUnitario - totalDescuentoUnitarioTotal);
                numero = Number(totalValorPlanMensualUnitario.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-mensual").find(".precioUnitario").html("$" + numero);
                totalValorPlanMensual = Number(totalValorPlanSinDescuento - totalDescuentoTotal);
                numero = Number(totalValorPlanMensual.toFixed(1)).toLocaleString();
                $("#prev-cotizacion #total-plan-mensual").find(".precioTotal").html("$" + numero);
            }

        }



        /*
         * TODO -  
         * 1. Resetear todos los items para que se oculten.
         * 2. Mostrar la información de aquellos items que se seleccionaron en la cotización.
         * 3. Calcular los valores.
         *      
         */

    });
    /************************ INGRESO DE DATOS DE CLIENTE *************************/
    /**VALIDACION FORMULARIO***/
    $("form").validity(function() {
        $("#nit").require();
        $("#empresa").require();
        $("#nombre").require();
        $("#correo").require().match("email");
        $("#correo2").match("email");
    });

    $("form").submit(function() {
        //var contrato =$("#contrato").val();
        //alert('envio'+contrato);
        $("#msgError").empty();
        if ($("#plan").val() === '-1') {
            $("#msgError").append("No has ingresado el tipo de Plan");
        }
        if ($("#cantidad-unidad-gps").val() === '') {
            $("#msgError").append("<br/>No has ingresado Cantidad de GPS");
        }
        if ($("#contrato").val() === '-1') {
            $("#msgError").append("<br/>No has ingresado el tipo de Contrato");
        }
        else
            return true;

    });
});

function abrirPanel() {
    $("#accesorios-seleccionados").panel("open");
}

function eventoPuntosTap(event) {
    var punto = event.target;
    var puntoID = $(punto).attr("id"); // retorna accesorio-##
    var $accesorioCheckbox = $("#checkbox-" + puntoID);

    // accesorio id unidad-gps -> Unidad Satelital (Dual) GPS  
    if ($(punto).attr("id") !== "unidad-gps") {

        if (isJqmGhostClick(event)) {
            return false;
        }

        if (!$(punto).hasClass("deshabilitado")) {
            // Se cambiara el estilo cuando el punto es "tapeado(clickeado)"
            $(punto).toggleClass("seleccionado");
            // Se seleccionara automaticamente el listado que se visualiza en el panel derecho 
            if ($accesorioCheckbox.is(':checked')) {
                $accesorioCheckbox.prop('checked', false).checkboxradio('refresh');
                // Actualizando la lista de cantidades de la interfaz de adicionales.
                // ocultando item de la lista porque se está des-habilitando desde el camión.                
                var $itemCantidadAccesorio = $("#adicionales #tabla-cantidad-accesorios").find("#" + puntoID);
                $itemCantidadAccesorio.find("input").val("");
                $itemCantidadAccesorio.hide();
            } else {
                // Actualizando la lista de cantidades de la interfaz de adicionales.
                // Mostrando item de la lista porque se está habilitando desde el camión.                
                var $itemCantidadAccesorio = $("#adicionales #tabla-cantidad-accesorios").find("#" + puntoID);
                $itemCantidadAccesorio.show();
                $accesorioCheckbox.prop('checked', true).checkboxradio('refresh');
            }
            validarRestricciones();

        }
    }

}


/* ---------------------------------------------
 Validacion de restricciones
 -------------------------------------------------  */

/**
 * Este metodo se encarga de activar todas las restricciones de los
 * elementos que estén seleccionados
 */
function validarRestricciones() {
    // Habilitamos todo de nuevo
    habilitarGps();
    habilitarAccesorios();
    habilitarPlanesServicio();

    // Deshabilitamos elementos
    deshabilitarPlanesIncompatiblesAccesorios();
    deshabilitarAccesoriosIncompatiblesConPlan();
    deshabilitarAccesoriosIncompatibleConGPS();
    deshabilitarGpsIncompatiblesConAccesorios();
}

function habilitarAccesorios() {
    if ($("[id^='accesorio']").hasClass("deshabilitado")) {
        $("[id^='accesorio']").removeClass("deshabilitado");
    }
}

function habilitarGps() {
    $("[name='gps']").checkboxradio({disabled: false});
    $("[name='gps']").checkboxradio('refresh');
}

function habilitarPlanesServicio() {
    $("#planes-servicio #plan option").attr("disabled", false);

    // El refresh solo debe realizarse cuando el selectmenu se ha inicializado. 
    // De lo contrario no cargará bien.
    if ($("#planes-servicio #plan").data("mobileSelectmenu") !== undefined) {
        $("#planes-servicio #plan").selectmenu("refresh", true);
    }
}

function deshabilitarAccesorio(accesorioID) {
    $("#accesorio-" + accesorioID).addClass("deshabilitado");
}

function deshabilitarGps(gpsID) {
    $("#gps-" + gpsID).checkboxradio({disabled: true});
    $("#gps-" + gpsID).checkboxradio('refresh');
}

function deshabilitarPlanServicio(planServicioID) {
    $("#plan-servicio-" + planServicioID).attr("disabled", true);

    // El refresh solo debe realizarse cuando el selectmenu se ha inicializado. De lo contrario no cargará bien.
    if ($("#planes-servicio #plan").data("mobileSelectmenu") !== undefined) {
        $("#planes-servicio #plan").selectmenu("refresh", true);
    }
}

/**
 * Esta funcion desahibilita los accesorios que no se puedan
 * seleccionar dado el gps que se encuentre seleccionado
 * 
 */
function deshabilitarAccesoriosIncompatibleConGPS() {
    var gpsSeleccionado = $("input[name^='gps']:checked");

    // Si no se ha seleccionado gps
    if (gpsSeleccionado.length == 0)
        return;

    var gpsID = $(gpsSeleccionado).prop("id").split("-")[1];

    if (accesoriosIncompatiblesGPS[gpsID]) {
        $.each(accesoriosIncompatiblesGPS[gpsID], function() {
            deshabilitarAccesorio(this);
        });
    }
}

function deshabilitarAccesoriosIncompatiblesConPlan() {
    var planSeleccionado = $("#planes-servicio select#plan").val();

    if (accesoriosIncompatiblesPlanes[planSeleccionado]) {
        $.each(accesoriosIncompatiblesPlanes[planSeleccionado], function() {
            deshabilitarAccesorio(this);
        });
    }
}

function deshabilitarPlanesIncompatiblesAccesorios() {
    var accesoriosSeleccionados = $("input[name$='accesorios']:checked");
    habilitarPlanesServicio();
    $(accesoriosSeleccionados).each(function() {
        var accesorioID = $(this).attr("id").split("-")[2];
        planesIncompatiblesAccesorio

        if (planesIncompatiblesAccesorio[accesorioID]) {
            $.each(planesIncompatiblesAccesorio[accesorioID], function() {
                deshabilitarPlanServicio(this);
            });
        }
    });
}

/**
 * Esta funcion deshabilita los gps que no se puedan seleccionar
 * dados los accesorios que se encuentren seleccionados
 */

function deshabilitarGpsIncompatiblesConAccesorios() {
    var accesoriosSeleccionados = $("input[name$='accesorios']:checked");
    habilitarGps();

    $(accesoriosSeleccionados).each(function() {
        var accesorioID = $(this).prop("id").split("-")[2];

        if (gpsIncompatiblesAccesorio[accesorioID]) {
            $.each(gpsIncompatiblesAccesorio[accesorioID], function() {
                deshabilitarGps(this);
            });
        }
    });
}



/* ---------------------------------------------
 Hasta aqui validacion de restricciones
 -------------------------------------------------  */