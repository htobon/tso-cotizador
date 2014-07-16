$(document).ready(function() {
    App.init();
});

var App = {
    init: function() {
        App.initSpiner();
        App.events();
        App.changeView();
    },
    events: function() {

        $('a[ui-sref]').click(App.changeView);
        $('a[ui-sref=usuarios]').click(App.getUsuarios);
        $('a[ui-sref=accesorios]').click(App.getAccesorios);
        $('a[ui-sref=unidad_gps]').click(App.getUnidadesGPS);
        $('a[ui-sref=contratos]').click(App.getContratos);
        $('a[ui-sref=planes]').click(App.getPlanes);
        $('a[ui-sref=clientes]').click(App.getClientes);
        $('a[ui-sref=cotizacionesGeneradas]').click(App.reporteCotizaciones);
        $('a[ui-sref=archivos]').click(App.getFiles);

        $(document).on('click', 'button[ui-sref]', App.showModals);


        // Administracion de Usuarios
        $(document).on('click', 'button[ui-sref=gestionarUsuarios]', App.showUsuarios);
        $(document).on('click', 'button[sref=guardarUsuario]', App.saveUsuario);
        $(document).on('click', 'button[ui-sref=confirmDialog]', App.putArguments);
        //$(document).on('click', 'button[sref=inactivarUsuario]', App.inactiveUsuario);


        // Administracion de Accesorios
        $(document).on('click', 'button[ui-sref=gestionarAccesorios]', App.showAccesorios);
        $(document).on('click', 'button[sref=guardarAccesorio]', App.saveAccesorio);
        $(document).on('click', 'button[sref=inactivarAccesorio]', App.inactiveAccesorio);

        // Administracion de Unidades GPS
        $(document).on('click', 'button[ui-sref=gestionarUnidadGps]', App.showUnidadGps);
        $(document).on('click', 'button[sref=guardarUnidadGps]', App.saveUnidadGps);
        $(document).on('click', 'button[sref=inactivarUnidadGps]', App.inactiveUnidadGps);

        // Administracion de Tipos de Contrato
        $(document).on('click', 'button[ui-sref=gestionarContratos]', App.showContratos);
        $(document).on('click', 'button[sref=guardarContrato]', App.saveContratos);
        $(document).on('click', 'button[sref=inactivarContrato]', App.inactiveContratos);

        // Administracion de Planes
        $(document).on('click', 'button[ui-sref=gestionarPlanes]', App.showPlan);
        $(document).on('click', 'button[sref=guardarPlan]', App.savePlan);
        $(document).on('click', 'button[sref=inactivarPlan]', App.inactivePlan);

        // Administracion de Planes
        $(document).on('click', 'button[ui-sref=gestionarClientes]', App.showCliente);
        $(document).on('click', 'button[sref=guardarCliente]', App.saveCliente);
        $(document).on('click', 'button[sref=inactivarCliente]', App.inactiveCliente);

        //Reporte
        $(document).on('click', '#cotizaciones  button', App.verPdf);


        // Dialog Confirm
        $(document).on('click', 'button[sref=inactivar_registro]', function(e) {

            if (isJqmGhostClick(e)) {
                return false;
            }

            var id = $("#modal").attr('rel');
            var fn = $("#modal").data('fn');

            App[fn](id);

        });

        //http://markusslima.github.io/bootstrap-filestyle/
        $(":file").filestyle();

        App.maskedInputs();
    },
    changeView: function(e) {

        var view = $(this).attr("ui-sref");

        if (!view || 0 === view.length)
            view = 'dashboard';

        $.ajax({
            type: "GET",
            url: "/templates/sections/admin/" + view + ".tpl",
            success: function(msg) {
                $('#contenido').html(msg);
            }
        });

    },
    showModals: function(e) {
        var view = $(this).attr("ui-sref");

        if (!view || 0 === view.length)
            view = 'dashboard';

        $.ajax({
            type: "GET",
            url: "/templates/sections/admin/" + view + ".tpl",
            success: function(msg) {
                $('#modal').html(msg);
                App.events();
            }
        });
    },
    maskedInputs: function() {
        // Formulario de Usuarios        
        $('#email_usuario').inputmask("email");

        // Formulario Unidades GPS
        $("#precio_unidad_gps, #precio_instalacion_unidad_gps").inputmask('decimal', {digits: 2, rightAlign: false, radixPoint: ".", autoGroup: true, groupSeparator: ",", groupSize: 3});


    },
    putArguments: function(e) {
        if (isJqmGhostClick(e)) {
            return false;
        }
        var id = $(this).data('id');
        var fn = $(this).data('fn');

        $("#modal").removeAttr('data-id');
        $("#modal").removeAttr('data-fn');

        $("#modal").attr('rel', $(this).data('id'));
        $("#modal").attr('data-fn', $(this).data('fn'));


    },
    getUsuarios: function() {

        var columns = [
            {"title": "Codigo", data: 'codigo'},
            {"title": "Nombre", data: 'nombres'},
            {"title": "Apellidos", data: 'apellidos'},
            {"title": "Telefono", data: 'telefono'},
            {"title": "Email", data: 'correo'},
            {"title": "Rol", data: 'rol'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {


                    /*return "<button class='btn btn-outline btn-primary btn-xs' id='usuario_" + obj.id + "' rel='show' type='button' ui-sref='gestionarUsuarios' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarUsuario'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' id='usuario_" + obj.id + "' rel='show' type='button' ui-sref='gestionarUsuarios' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-id='" + obj.id + "' data-fn='inactiveUsuario' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getUsuarios'
            },
            success: function(response) {
                App.generateTable('usuarios', response.usuarios, columns, columnDefs);
            }
        });
    },
    showUsuarios: function(e) {

        if (isJqmGhostClick(e)) {
            return false;
        }

        var action = $(e.target).attr('rel');

        if (action === "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Usuarios', id);

            App.request({
                data: {
                    action: 'getUsuario',
                    usuario_id: id
                },
                success: function(response) {
                    var usuario = response.usuario;

                    $('#btn_guardar_usuario').attr('rel', usuario.id)
                    $('#email_usuario').attr('disabled', 'disabled')

                    $('#codigo_usuario').val(usuario.codigo);
                    $('#nombres_usuario').val(usuario.nombres);
                    $('#apellidos_usuario').val(usuario.apellidos);
                    $('#telefono_usuario').val(usuario.telefono);
                    $('#email_usuario').val(usuario.correo);
                    $('#rol_usuario option[value="' + usuario.rol + '"]').attr('selected', 'selected');
                }
            });
        }
    },
    saveUsuario: function(e) {

        var error = false;
        var usuario_id = $(e.target).attr('rel');

        if (isJqmGhostClick(e)) {
            return false;
        }

        $("#msj_error").empty();
        $("#msj_error").addClass("hidden")

        if ($("#codigo_usuario").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese un código<br/>");
            error = true;
        }
        if ($("#nombres_usuario").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese los Nombre del usuario<br/>");
            error = true;
        }
        if ($("#apellidos_usuario").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese los apellido del usuario<br/>");
            error = true;
        }
        if ($("#telefono_usuario").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el telefono del usuario<br/>");
            error = true;
        }

        var isValidEmail = $.inputmask.isValid($("#email_usuario").val(), {alias: "email"});

        if ($("#email_usuario").val() === '' || !isValidEmail) {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese un email valido para el usuario<br/>");
            error = true;
        }

        if (usuario_id === "" || usuario_id === undefined || usuario_id === "0") {
            // La Clave del usuario es obligatoria cuando se crea un usuario
            if ($("#clave_usuario").val() === '') {
                $("#msj_error").removeClass("hidden");
                $("#msj_error").append(" - Ingrese la clave del usuario<br/>");
                error = true;
            }
        }

        if (!error) {

            var usuario = {
                id: usuario_id,
                codigo: $("#codigo_usuario").val(),
                nombres: $("#nombres_usuario").val(),
                apellidos: $("#apellidos_usuario").val(),
                telefono: $("#telefono_usuario").val(),
                email: $("#email_usuario").val(),
                clave: $("#clave_usuario").val(),
                rol: $("#rol_usuario").val()
            };

            var action = "";
            if (usuario_id === "" || usuario_id === undefined || usuario_id === "0") {
                action = 'saveUsuario';
            } else {
                action = 'updateUsuario';
            }

            App.request({
                data: {
                    action: action,
                    usuario: usuario
                },
                success: function(response) {

                    if (response.message_code === 0) {
                        // Error
                        $("#msj_error").removeClass("hidden");
                        $("#msj_error").append(response.message);
                    } else {
                        //Success
                        $('#modal').modal('hide');
                        $("#msj_success").fadeIn(1600);
                        $("#msj_success").removeClass("hidden");
                        $("#msj_success").empty();
                        $("#msj_success").append(response.message);
                        $("#msj_success").fadeOut(2600, "linear");
                        App.getUsuarios();
                    }
                }
            });

        }
    },
    inactiveUsuario: function(id) {

        console.log('Inactivar usuario', id)

        App.request({
            data: {
                action: 'inactiveUsuario',
                usuario_id: id
            },
            success: function(response) {

                if (response.message_code === 0) {
                    // Error
                    $("#msj_error").removeClass("hidden");
                    $("#msj_error").append(response.message);
                } else {
                    //Success
                    $('#modal').modal('hide');
                    $("#msj_success").fadeIn(1600);
                    $("#msj_success").removeClass("hidden");
                    $("#msj_success").empty();
                    $("#msj_success").append(response.message);
                    $("#msj_success").fadeOut(2600, "linear");
                    App.getUsuarios();
                }
            }
        });

    },
    getAccesorios: function() {

        var columns = [
            {"title": "Codigo", data: 'codAccesorio'},
            {"title": "Nombre", data: 'nombre'},
            {"title": "Precio", data: 'precioAccesorio'},
            {"title": "Cod. Instalacion", data: 'codInstalacion'},
            {"title": "Precio Instalacion", data: 'precioInstalacion'},
            {"title": "Mensualidad", data: 'precioMensualidad'},
            {"title": "Descripcion", data: 'descripcion'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {
                    /*return "<button class='btn btn-outline btn-primary btn-xs' id='accesorio_" + obj.id + "' rel='show' type='button' ui-sref='gestionarAccesorios' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarAccesorio' >Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' id='accesorio_" + obj.id + "' rel='show' type='button' ui-sref='gestionarAccesorios' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-toggle='modal' data-target='#modal' >Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getAccesorios'
            },
            success: function(response) {

                App.generateTable('accesorios', response.accesorios, columns, columnDefs);
            }
        });

    },
    showAccesorios: function(e) {
        var action = $(e.target).attr('rel');
        if (action == "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Accesorio', id);
        } else {
            console.log('Agregar Accesorio');
        }
    },
    saveAccesorio: function() {
        console.log('Guardar/Modificar Accesorio');
    },
    inactiveAccesorio: function() {
        console.log('Inactivar Accesorio');
    },
    getUnidadesGPS: function() {

        var columns = [
            {"title": "Codigo", data: 'codUnidad'},
            {"title": "Nombre", data: 'nombre'},
            {"title": "Precio", data: 'formato_precio_unidad'},
            {"title": "Cod. Instalacion", data: 'codInstalacion'},
            {"title": "Precio Instalacion", data: 'formato_precio_instalacion'},
            {"title": "Descripcion", data: 'descripcion'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {
                    /*return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='unidadgps_" + obj.id + "' rel='show' type='button' ui-sref='gestionarUnidadGps' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarUnidadGps'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='unidadgps_" + obj.id + "' rel='show' type='button' ui-sref='gestionarUnidadGps' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-id='" + obj.id + "' data-fn='inactiveUnidadGps' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getUnidadesGPS'
            },
            success: function(response) {

                App.generateTable('unidadesgps', response.unidades, columns, columnDefs);
            }
        });

    },
    showUnidadGps: function(e) {

        if (isJqmGhostClick(e)) {
            return false;
        }

        var action = $(e.target).attr('rel');

        if (action === "show") {
            var id = $(e.target).attr('id').split('_').pop();

            App.request({
                data: {
                    action: 'getUnidadGPS',
                    unidad_gps_id: id
                },
                success: function(response) {
                    console.log(response);

                    var unidad_gps = response.unidad_gps;

                    $('#codigo_unidad_gps').attr('disabled', 'disabled');
                    $('#btn_guardar_unidad_gps').attr('rel', unidad_gps.id)

                    $('#codigo_unidad_gps').val(unidad_gps.codUnidad);
                    $('#codigo_instalacion_unidad_gps').val(unidad_gps.codInstalacion);
                    $('#nombre_unidad_gps').val(unidad_gps.nombre);
                    $('#descripcion_unidad_gps').val(unidad_gps.descripcion);
                    $('#precio_unidad_gps').val(unidad_gps.precioUnidad);
                    $('#precio_instalacion_unidad_gps').val(unidad_gps.precioInstalacion);
                }
            });
        }
    },
    saveUnidadGps: function(e) {

        if (isJqmGhostClick(e)) {
            return false;
        }

        var error = false;
        var unidad_gps_id = $(e.target).attr('rel');

        $("#msj_error").empty();
        $("#msj_error").addClass("hidden")

        if ($("#codigo_unidad_gps").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese un código<br/>");
            error = true;
        }
        if ($("#codigo_instalacion_unidad_gps").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el codigo de la instalación.<br/>");
            error = true;
        }
        if ($("#nombre_unidad_gps").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el nombre de la unidad.<br/>");
            error = true;
        }
        if ($("#descripcion_unidad_gps").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese la descripcion.<br/>");
            error = true;
        }


        if ($("#precio_unidad_gps").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el precio unitario.<br/>");
            error = true;
        }

        if ($("#precio_instalacion_unidad_gps").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el precio de la instalación.<br/>");
            error = true;
        }


        if (!error) {

            var unidad_gps = {
                id: unidad_gps_id,
                nombre: $("#nombre_unidad_gps").val(),
                cod_unidad: $("#codigo_unidad_gps").val(),
                cod_instalacion: $("#codigo_instalacion_unidad_gps").val(),
                precio_unidad: $("#precio_unidad_gps").val().replace(',', ''),
                precio_instalacion: $("#precio_instalacion_unidad_gps").val().replace(',', ''),
                descripcion: $("#descripcion_unidad_gps").val()
            };

            var action = "";
            if (unidad_gps_id === "" || unidad_gps_id === undefined || unidad_gps_id === "0") {
                action = 'saveUnidadGPS';
            } else {
                action = 'updateUnidadGPS';
            }

            App.request({
                data: {
                    action: action,
                    unidad_gps: unidad_gps
                },
                success: function(response) {

                    if (response.message_code === 0) {
                        // Error
                        $("#msj_error").removeClass("hidden");
                        $("#msj_error").append(response.message);
                    } else {
                        //Success
                        $('#modal').modal('hide');
                        $("#msj_success").fadeIn(1600);
                        $("#msj_success").removeClass("hidden");
                        $("#msj_success").empty();
                        $("#msj_success").append(response.message);
                        $("#msj_success").fadeOut(2600, "linear");
                        App.getUnidadesGPS();
                    }
                }
            });

        }

    },
    inactiveUnidadGps: function(id) {
        console.log('Inactivar Unidad', id);

        App.request({
            data: {
                action: 'inactiveUnidadGPS',
                unidad_gps_id: id
            },
            success: function(response) {

                if (response.message_code === 0) {
                    // Error
                    $("#msj_error").removeClass("hidden");
                    $("#msj_error").append(response.message);
                } else {
                    //Success
                    $('#modal').modal('hide');
                    $("#msj_success").fadeIn(1600);
                    $("#msj_success").removeClass("hidden");
                    $("#msj_success").empty();
                    $("#msj_success").append(response.message);
                    $("#msj_success").fadeOut(2600, "linear");
                    App.getUnidadesGPS();
                }
            }
        });
    },
    getContratos: function() {

        var columns = [
            {"title": "Codigo", data: 'id'},
            {"title": "Nombre", data: 'nombre'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {
                    /*return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='contrato_" + obj.id + "' rel='show' type='button' ui-sref='gestionarContratos' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarContrato'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='contrato_" + obj.id + "' rel='show' type='button' ui-sref='gestionarContratos' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getTiposContratos'
            },
            success: function(response) {

                App.generateTable('contratos', response.tiposContratos, columns, columnDefs);
            }
        });

    },
    showContratos: function(e) {
        var action = $(e.target).attr('rel');
        if (action == "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Tipo Contrato', id);
        } else {
            console.log('Agregar Tipo Contrato');
        }
    },
    saveContratos: function() {
        console.log('Guardar/Modificar Tipo Contrato');
    },
    inactiveContratos: function() {
        console.log('Inactivar Tipo Contrato');
    },
    getPlanes: function() {

        var columns = [
            {"title": "Codigo", data: 'codigo'},
            {"title": "Nombre", data: 'nombre'},
            {"title": "Precio", data: 'precio'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {
                    /*return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='plan_" + obj.id + "' rel='show' type='button' ui-sref='gestionarPlanes' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarPlan'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='plan_" + obj.id + "' rel='show' type='button' ui-sref='gestionarPlanes' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getPlanes'
            },
            success: function(response) {
                App.generateTable('planes', response.planes, columns, columnDefs);
            }
        });

    },
    showPlan: function(e) {
        var action = $(e.target).attr('rel');
        if (action == "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Plan', id);
        } else {
            console.log('Agregar Plan');
        }
    },
    savePlan: function() {
        console.log('Guardar/Modificar Plan');
    },
    inactivePlan: function() {
        console.log('Inactivar Plan');
    },
    getClientes: function() {

        var columns = [
            {"title": "Codigo", data: 'id'},
            {"title": "Nit", data: 'nit'},
            {"title": "Nombre", data: 'nombre'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {
                    /*return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='plan_" + obj.id + "' rel='show' type='button' ui-sref='gestionarClientes' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarCliente'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='plan_" + obj.id + "' rel='show' type='button' ui-sref='gestionarClientes' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getClientes'
            },
            success: function(response) {
                App.generateTable('clientes', response.clientes, columns, columnDefs);
            }
        });
    },
    showCliente: function(e) {
        var action = $(e.target).attr('rel');
        if (action == "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Cliente', id);
        } else {
            console.log('Agregar Cliente');
        }
    },
    saveCliente: function() {
        console.log('Guardar/Modificar Cliente');
    },
    inactiveCliente: function() {
        console.log('Inactivar Cliente');
    },
    reporteCotizaciones: function() {

        var columns = [
            {"title": "Codigo Vendedor", "data": 'codigo_vendedor'},
            {"title": "Empresa", "data": 'cliente'},
            {"title": "No. Cotización", "class": "center", data: 'id'},
            {"title": "Tipo Contrato", data: 'tipo_contrato'},
            {"title": "Plan", data: 'nombre_plan'},
            {"title": "Cnt. Unidades", "class": "center", data: 'cantidad_vehiculos'},
            {"title": "Valor Recurrente", data: 'valor_recurrencia'},
            {"title": "Valor Recurrente", data: 'formato_valor_recurrencia'},
            {"title": "Valor Equipos", data: 'valor_equipos'},
            {"title": "Valor Equipos", data: 'formato_valor_equipos'},
            {"title": "Valor Total", data: 'valor_total'},
            {"title": "Valor Total", data: 'formato_valor_total'},
            {"title": "Ver PDF"}
        ];

        var columnDefs = [
            {
                "targets": [6, 8, 10],
                "visible": false
            },
            {
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='btn_" + full.id + "'>ver PDF</button>";
                }
            }];




        App.request({
            data: {
                action: 'reporteCotizaciones'
            },
            success: function(response) {
                console.log(response.cotizaciones);
                App.generateTable('cotizaciones', response.cotizaciones, columns, columnDefs, App.totalesReporte);
            }
        });
    },
    totalesReporte: function(row, data, start, end, display) {

        var api = this.api(), data;

        data = api.column(5, {page: 'current'}).data();
        var total_unidades = data.length ? data.reduce(function(a, b) {
            return parseInt(a) + parseInt(b);
        }) : 0;

        data = api.column(6, {page: 'current'}).data();
        var total_recurrente = data.length ? data.reduce(function(a, b) {
            return parseFloat(a) + parseFloat(b);
        }) : 0;
        total_recurrente = Number(total_recurrente.toFixed(1)).toLocaleString();


        data = api.column(8, {page: 'current'}).data();
        var total_equipos = data.length ? data.reduce(function(a, b) {
            return parseFloat(a) + parseFloat(b);
        }) : 0;
        total_equipos = Number(total_equipos.toFixed(1)).toLocaleString();

        data = api.column(10, {page: 'current'}).data();
        var total = data.length ? data.reduce(function(a, b) {
            return parseFloat(a) + parseFloat(b);
        }) : 0;
        total = Number(total.toFixed(1)).toLocaleString();

        $(api.column(5).footer()).html(total_unidades);
        $(api.column(7).footer()).html('$' + total_recurrente);
        $(api.column(9).footer()).html('$' + total_equipos);
        $(api.column(11).footer()).html('$' + total);

    },
    verPdf: function(e) {
        var id = $(e.target).attr('id').split('_').pop();
        id = window.btoa(id);
        window.open("/sections/cotizador/showPdf.php?cotizacion=" + id, '_blank');
    },
    getFiles: function() {
        App.request({
            data: {
                action: 'getListFiles'
            },
            success: function(response) {
                if (response.message_code === 1) {
                    $('#listaCotizaciones').empty();
                    $('#listaDetalles').empty();
                    if (response.cotizaciones.length > 0) {
                        $.each(response.cotizaciones, function(i, file) {

                            var $div = $('<div/>').addClass('col-sm-12');
                            var name = file.name;
                            if (name.length > 40) {
                                name = $.trim(name).substring(0, 35).split(' ').slice(0, -1).join(' ') + '..';
                            }
                            name = name + '.' + file.extension;
                            var $label = $('<label/>').html(name).addClass('col-sm-6 col-sm-offset-1 text-left');
                            var $button = $('<button/>').addClass('btn btn-outline btn-primary btn-xs').html('Decargar');
                            $button.click({filename: file.filename}, App.deleteFile);

                            //add
                            $label.appendTo($div);
                            $button.appendTo($div);
                            $div.appendTo($('#listaCotizaciones'));
                        });
                    }
                    if (response.detalles.length > 0) {
                        $.each(response.detalles, function(i, file) {

                            //$('#listaCotizaciones').html("Hola");

                            var $div = $('<div/>').addClass('col-sm-12');
                            var name = file.name;
                            if (name.length > 40) {
                                name = $.trim(name).substring(0, 35).split(' ').slice(0, -1).join(' ') + '..';
                            }
                            name = name + '.' + file.extension;
                            var $label = $('<label/>').html(name).addClass('col-sm-6 col-sm-offset-1 text-left');
                            var $button = $('<button/>').addClass('btn btn-outline btn-primary btn-xs').html('Decargar');
                            ;
                            $button.click({filename: file.filename}, App.deleteFile);

                            //add
                            $label.appendTo($div);
                            $button.appendTo($div);
                            $div.appendTo($('#listaDetalles'));
                        });
                    }
                }
            }
        });
    },
    request: function(json) {

        $('#loading').modal('show');

        $.ajax({
            url: 'actions.php',
            action: json.action,
            data: json.data,
            dataType: 'json',
            method: 'post',
            beforeSend: function() {
                if (typeof json.beforeSend === 'function')
                    json.beforeSend();
            },
            success: function(response) {
                if (typeof json.success === 'function')
                    json.success(response);

                $('#loading').modal('hide');
            },
            complete: function() {
                if (typeof json.complete === 'function')
                    json.complete();

                $('#loading').modal('hide');
            },
            error: function(e) {
                console.log('error', e);
                $('#loading').modal('hide');
            }
        });
    },
    generateTable: function(table, data, columns, columnDefs, footerCallback) {

        $('#' + table).dataTable({
            "destroy": true,
            "paging": true,
            "ordering": false,
            "info": false,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros por Página",
                "search": "Buscar: ",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "data": data,
            "columns": columns,
            "columnDefs": columnDefs,
            "footerCallback": footerCallback
        });
    },
    initSpiner: function() {
        var opts = {
            lines: 15, // The number of lines to draw
            length: 18, // The length of each line
            width: 10, // The line thickness
            radius: 25, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 44, // The rotation offset
            direction: 1, // 1: clockwise, -1: counterclockwise
            color: '#000', // #rgb or #rrggbb or array of colors
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: '50%', // Top position relative to parent
            left: '50%' // Left position relative to parent
        };
        var target = document.getElementById('spiner');
        var spinner = new Spinner(opts).spin(target);
        target.appendChild(spinner.el);
    }

}