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
        $(document).on('click', 'button[ui-sref]', App.showModals);

        $('a[ui-sref=dashboard]').click(App.dashboard);
        $('a[ui-sref=usuarios]').click(App.getUsuarios);
        $('a[ui-sref=accesorios]').click(App.getAccesorios);
        $('a[ui-sref=unidad_gps]').click(App.getUnidadesGPS);
        $('a[ui-sref=contratos]').click(App.getContratos);
        $('a[ui-sref=meses]').click(App.getDuracionesContrato);
        $('a[ui-sref=descuentos]').click(App.getDescuentos);
        $('a[ui-sref=planes]').click(App.getPlanes);
        $('a[ui-sref=clientes]').click(App.getClientes);
        $('a[ui-sref=exportar_importar_clientes]').click(App.exportarImportarClientes);
        $('a[ui-sref=cotizacionesGeneradas]').click(App.reporteCotizaciones);
        $('a[ui-sref=archivos]').click(App.getFiles);



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
        /*$(document).on('click', 'button[ui-sref=gestionarContratos]', App.showContratos);
         $(document).on('click', 'button[sref=guardarContrato]', App.saveContratos);
         $(document).on('click', 'button[sref=inactivarContrato]', App.inactiveContratos);*/

        // Administracion de Duraciones de Contrato
        $(document).on('click', 'button[ui-sref=gestionarMeses]', App.showDuracionesContrato);
        $(document).on('click', 'button[sref=guardarDuracionContrato]', App.saveDuracionesContrato);

        // Administracion de Descuentos
        $(document).on('click', 'button[ui-sref=gestionarDescuentos]', App.showDescuento);
        $(document).on('click', 'button[sref=guardarDescuento]', App.saveDescuento);


        // Administracion de Planes
        $(document).on('click', 'button[ui-sref=gestionarPlanes]', App.showPlan);
        $(document).on('click', 'button[sref=guardarPlan]', App.savePlan);
        $(document).on('click', 'button[sref=inactivarPlan]', App.inactivePlan);

        // Administracion de Clientes
        $(document).on('click', 'button[ui-sref=gestionarClientes]', App.showCliente);
        $(document).on('click', 'button[sref=guardarCliente]', App.saveCliente);
        $(document).on('click', 'button[sref=inactivarCliente]', App.inactiveCliente);

        //Reporte
        $(document).on('click', '#cotizaciones  button', App.verPdf);
        // Filtrar Reporte
        $(document).on('click', '#filtrar_reporte', App.filtraReporteCotizaciones);


        // Exportar Clientes 
        $(document).on('click', '#exportarClientes', function(e) {

            if (isJqmGhostClick(e)) {
                return false;
            }

            $('#msj_error2').html('Opcion No Disponible, Trabajando Actualmente sobre esta funcionalidad!!!!');
        });



        // Dialog Confirm
        $(document).on('click', 'button[sref=inactivar_registro]', function(e) {

            e.stopImmediatePropagation();

            var id = $("#modal").attr('rel');
            var fn = $("#modal").data('fn');

            App[fn](id);

        });

        $(":file").filestyle();

        App.maskedInputs();

        $("#upload_image").submit(App.uploadFirmaDigital);
        $("#upload_csv").submit(App.uploadCsvClientes);




        var checkin = $('#fecha_inicial').datepicker().on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('#fecha_final')[0].focus();
        }).data('datepicker');
        var checkout = $('#fecha_final').datepicker({
            onRender: function(date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            checkout.hide();
        }).data('datepicker');


        $('.btn-toggle').click(function() {

            // http://www.bootply.com/92189

            $(this).find('.btn').toggleClass('active');

            if ($(this).find('.btn-primary').size() > 0) {
                $(this).find('.btn').toggleClass('btn-primary');
            }


        });

    },
    changeView: function(e) {

        console.log('== changeView ==');

        var view = $(this).attr("ui-sref");

        if (!view || 0 === view.length)
            view = 'dashboard';

        $.ajax({
            type: "GET",
            url: "/templates/sections/admin/" + view + ".tpl",
            success: function(msg) {
                $('#contenido').html(msg);
                App.events();
            }
        });

    },
    showModals: function(e) {

        console.log('== showModals == ');

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

        // Formulario de Planes
        $("#precio_plan").inputmask('decimal', {digits: 2, rightAlign: false, radixPoint: ".", autoGroup: true, groupSeparator: ",", groupSize: 3});

        // Formulario Descuentos
        $("#cantidad_minima, #cantidad_maxima").inputmask('integer', {rightAlign: false});
        $("#descuento").inputmask('integer');

        // Formulario de Accesorios
        $("#precio_accesorio, #precio_instalacion_accesorio, #precio_mesualidad_accesorio").inputmask('decimal', {digits: 2, rightAlign: false, radixPoint: ".", autoGroup: true, groupSeparator: ",", groupSize: 3});


    },
    putArguments: function(e) {

        e.stopImmediatePropagation();
        console.log('== putArguments ==');

        var id = $(this).data('id');
        var fn = $(this).data('fn');

        $("#modal").removeAttr('data-id');
        $("#modal").removeAttr('data-fn');

        $("#modal").attr('rel', $(this).data('id'));
        $("#modal").attr('data-fn', $(this).data('fn'));


    },
    dashboard: function(e) {

        e.stopImmediatePropagation();

        console.log(' == dasboard == ');
    },
    getUsuarios: function(e) {

        if (e)
            e.stopImmediatePropagation();

        console.log('== getUsuarios ==');

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

        e.stopImmediatePropagation();
        console.log('== showUsuarios ==');

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
                    $('#firma_actual').val(usuario.firma);

                    if (usuario.firma !== '') {
                        var html = "<img height='100' width='500' src='/images/firmas/" + usuario.firma + "' />";
                        $('#preview').html(html).fadeIn();
                    }
                    ;
                }
            });
        }
    },
    uploadFirmaDigital: function(e) {

        console.log('== uploadFirmaDigital ==');

        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data, textStatus, jqXHR)
            {
                console.log(data);
                var file = data.split('/').pop();

                console.log(file);
                $('#preview').html(data).fadeIn();
                $('#delete_image').removeAttr('disabled');
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            }
        });
        e.preventDefault(); //Prevent Default action. 
        //e.unbind();

        return false;

    },
    uploadCsvClientes: function(e) {

        console.log('== uploadCsvClientes ==');

        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data, textStatus, jqXHR)
            {
                console.log('======= result =====');
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            }
        });
        e.preventDefault(); //Prevent Default action. 
        //e.unbind();

        return false;

    },
    saveUsuario: function(e) {

        e.stopImmediatePropagation();
        console.log('== saveUsuario ==');

        var error = false;
        var usuario_id = $(e.target).attr('rel');

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

        var firma = '';

        var image = $('img').attr('id');
        if (image)
            firma = image.split('_').pop();

        if (!error) {

            var usuario = {
                id: usuario_id,
                codigo: $("#codigo_usuario").val(),
                nombres: $("#nombres_usuario").val(),
                apellidos: $("#apellidos_usuario").val(),
                telefono: $("#telefono_usuario").val(),
                email: $("#email_usuario").val(),
                clave: $("#clave_usuario").val(),
                rol: $("#rol_usuario").val(),
                firma_actual: $('#firma_actual').val(),
                firma: firma
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
                        App.getUsuarios(e);
                    }
                }
            });

        }
    },
    inactiveUsuario: function(id) {

        console.log('== inactiveUsuario ==');

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
    getAccesorios: function(e) {

        if (e)
            e.stopImmediatePropagation();

        console.log('== getAccesorios ==');

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
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-id='" + obj.id + "' data-fn='inactiveAccesorio' data-toggle='modal' data-target='#modal' >Desactivar</button>";
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

        e.stopImmediatePropagation();
        console.log('== showAccesorios ==');

        var action = $(e.target).attr('rel');

        App.request({
            data: {
                action: 'getUnidadesGPS'
            },
            success: function(response) {
                console.log('UnidadesGPS', response.unidades);

                var unidades = response.unidades;

                if (unidades.length > 0) {
                    $.each(unidades, function(i, u) {

                        var $div = $('<div/>').addClass('checkbox');
                        var $label = $('<label/>').html(u.nombre).attr('for', 'chk_unidad_' + u.id);
                        var $checkbox = $('<input/>').attr({id: 'chk_unidad_' + u.id, type: 'checkbox'}).val(u.id);

                        //add to parent
                        $label.appendTo($div);
                        $checkbox.appendTo($div);
                        $div.appendTo($('#unidades'));

                    });
                }
            }
        });

        App.request({
            data: {
                action: 'getPlanes'
            },
            success: function(response) {
                console.log('Planes', response.planes);

                var planes = response.planes;

                if (planes.length > 0) {
                    $.each(planes, function(i, p) {

                        var $div = $('<div/>').addClass('checkbox');
                        var $label = $('<label/>').html(p.nombre).attr('for', 'chk_plan_' + p.id);
                        var $checkbox = $('<input/>').attr({id: 'chk_plan_' + p.id, type: 'checkbox'}).val(p.id);

                        //add to parent
                        $label.appendTo($div);
                        $checkbox.appendTo($div);
                        $div.appendTo($('#planes'));

                    });
                }

            }
        });

        if (action === "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Accesorio', id);

            // Accesorios
            App.request({
                data: {
                    action: 'getAccesorio',
                    accesorio_id: id
                },
                success: function(response) {

                    console.log(response);

                    var accesorio = response.accesorio;
                    var restriciones_planes = response.restricciones_planes;
                    var restriciones_unidades = response.restricciones_unidades;

                    $('#btn_guardar_accesorio').attr('rel', accesorio.id)
                    $('#codigo_accesorio').attr('disabled', 'disabled')

                    $('#codigo_accesorio').val(accesorio.codAccesorio);
                    $('#nombre_accesorio').val(accesorio.nombre);
                    $('#descripcion_accesorio').val(accesorio.descripcion);
                    $('#beneficios_accesorio').val(accesorio.beneficios);
                    $('#aplicacion_accesorio').val(accesorio.aplicacion);
                    $('#precio_accesorio').val(accesorio.precioAccesorio);
                    $('#precio_instalacion_accesorio').val(accesorio.precioInstalacion);
                    $('#precio_mesualidad_accesorio').val(accesorio.precioMensualidad);

                    if (restriciones_planes.length > 0) {
                        $.each(restriciones_planes, function(i, plan) {
                            $('#chk_plan_' + plan.planes_id).prop('checked', true);

                        });
                    }
                    if (restriciones_unidades.length > 0) {
                        $.each(restriciones_unidades, function(i, unidad) {
                            $('#chk_unidad_' + unidad.unidad_gps_id).prop('checked', true);
                        });
                    }

                }
            });
        }

    },
    saveAccesorio: function(e) {

        e.stopImmediatePropagation();
        console.log('== saveAccesorio ==');

        var error = false;
        var accesorio_id = $(e.target).attr('rel');

        console.log('Guardar/Modificar Accesorio', accesorio_id);

        $("#msj_error").empty();
        $("#msj_error").addClass("hidden");

        if ($("#codigo_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese un código<br/>");
            error = true;
        }
        if ($("#nombre_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese un nombre<br/>");
            error = true;
        }
        if ($("#descripcion_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese una descripcion<br/>");
            error = true;
        }
        if ($("#beneficios_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese una beneficio<br/>");
            error = true;
        }
        if ($("#aplicacion_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese una aplicacion del accesorio<br/>");
            error = true;
        }
        if ($("#precio_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese precio del accesorio<br/>");
            error = true;
        }
        if ($("#precio_instalacion_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese precio de la instalacion<br/>");
            error = true;
        }
        if ($("#precio_mesualidad_accesorio").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese precio de la mensualidad<br/>");
            error = true;
        }

        if (!error) {

            var accesorio = {
                id: accesorio_id,
                codAccesorio: $("#codigo_accesorio").val(),
                codInstalacion: $("#codigo_accesorio").val(),
                nombre: $("#nombre_accesorio").val(),
                descripcion: $("#descripcion_accesorio").val(),
                beneficios: $("#beneficios_accesorio").val(),
                aplicacion: $("#aplicacion_accesorio").val(),
                precioAccesorio: $("#precio_accesorio").val().replace(',', ''),
                precioInstalacion: $("#precio_instalacion_accesorio").val().replace(',', ''),
                precioMensualidad: $("#precio_mesualidad_accesorio").val().replace(',', ''),
                image: '',
                imagen_aplicacion_uno: '',
                imagen_aplicacion_dos: '',
                posicionX: '',
                posicionY: ''
            };

            var restricciones_unidades = [];
            $('#unidades input:checked').each(function() {
                restricciones_unidades.push($(this).attr('id').split('_').pop());
            });

            var restricciones_planes = [];
            $('#planes input:checked').each(function() {
                restricciones_planes.push($(this).attr('id').split('_').pop());
            });

            var action = "";
            if (accesorio_id === "" || accesorio_id === undefined || accesorio_id === "0") {
                action = 'saveAccesorio';
            } else {
                action = 'updateAccesorio';
            }

            App.request({
                data: {
                    action: action,
                    accesorio: accesorio,
                    restricciones_unidades: restricciones_unidades,
                    restricciones_planes: restricciones_planes
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
                        App.getAccesorios();
                    }
                }
            });

        }

    },
    inactiveAccesorio: function(id) {

        console.log('== inactiveAccesorio ==');

        App.request({
            data: {
                action: 'inactiveAccesorio',
                accesorio_id: id
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
                    App.getAccesorios();
                }
            }
        });

    },
    getUnidadesGPS: function(e) {

        if (e)
            e.stopImmediatePropagation();

        console.log('== getUnidadesGPS ==');

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

        e.stopImmediatePropagation();
        console.log('== showUnidadGps ==');

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

        e.stopImmediatePropagation();
        console.log('== saveUnidadGps ==');

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
                    unidad_gps: plan
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

        console.log('== inactiveUnidadGps ==');

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
    getContratos: function(e) {

        if (e)
            e.stopImmediatePropagation();
        console.log('== getContratos ==');


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
                     <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-toggle='modal' data-target='#modal'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-toggle='modal' data-target='#modal'>Desactivar</button>";
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
    getDuracionesContrato: function(e) {

        e.stopImmediatePropagation();
        console.log('== getDuracionesContrato ==');


        var columns = [
            {"title": "Codigo", data: 'id'},
            {"title": "Cantidad Meses", data: 'cantidadMeses'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {

                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='duracion_" + obj.id + "' rel='show' type='button' ui-sref='gestionarMeses' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-id='" + obj.id + "' data-fn='inactiveDuracionesContrato' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getMeses'
            },
            success: function(response) {

                App.generateTable('duracionesContrato', response.duraciones_contrato, columns, columnDefs);
            }
        });

    },
    showDuracionesContrato: function(e) {

        e.stopImmediatePropagation();
        console.log('== showDuracionesContrato ==');

        var action = $(e.target).attr('rel');

        if (action === "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Tipo Contrato', id);

            App.request({
                data: {
                    action: 'getDuracionContrato',
                    duracion_id: id
                },
                success: function(response) {
                    console.log(response);

                    var duracion = response.duracion_contrato;

                    $('#btn_guardar_mes').attr('rel', duracion.id);

                    $('#cantidad_meses').val(duracion.cantidadMeses);
                }
            });
        }
    },
    saveDuracionesContrato: function(e) {

        e.stopImmediatePropagation();
        console.log('== saveDuracionesContrato ==');

        var error = false;
        var duracion_id = $(e.target).attr('rel');

        $("#msj_error").empty();
        $("#msj_error").addClass("hidden")

        if ($("#cantidad_meses").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese cantidad de Meses<br/>");
            error = true;
        }


        if (!error) {

            var duracion_contrato = {
                id: duracion_id,
                cantidad_meses: $("#cantidad_meses").val()
            };

            var action = "";
            if (duracion_id === "" || duracion_id === undefined || duracion_id === "0") {
                action = 'saveDuracionContrato';
            } else {
                action = 'updateDuracionContrato';
            }

            App.request({
                data: {
                    action: action,
                    duracion_contrato: duracion_contrato
                },
                success: function(response) {

                    console.log(response);
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
                        App.getDuracionesContrato();
                    }
                }
            });

        }
    },
    inactiveDuracionesContrato: function(id) {

        console.log('== inactiveDuracionesContrato ==');

        App.request({
            data: {
                action: 'inactiveDuracionContrato',
                duracion_id: id
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
                    App.getDuracionesContrato();
                }
            }
        });
    },
    getDescuentos: function(e) {

        if (e)
            e.stopImmediatePropagation();
        console.log('== getDescuentos ==');

        var columns = [
            {"title": "Codigo", data: 'id'},
            {"title": "Cantidad Mínima", data: 'cantidadMin'},
            {"title": "Cantidad Máxima", data: 'cantidadMax'},
            {"title": "Descuento", data: 'formato_descuento', class: 'text-center'},
            {"title": "Opciones", class: 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, obj, meta) {
                    /*return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='plan_" + obj.id + "' rel='show' type='button' ui-sref='gestionarPlanes' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                     <button class='btn btn-outline btn-danger btn-xs' type='button' sref='inactivarPlan'>Desactivar</button>";*/
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='descuento_" + obj.id + "' rel='show' type='button' ui-sref='gestionarDescuentos' data-toggle='modal' data-target='#modal'>Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-id='" + obj.id + "' data-fn='inactiveDescuento' data-toggle='modal' data-target='#modal'>Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getDescuentos'
            },
            success: function(response) {
                App.generateTable('descuentos', response.descuentos, columns, columnDefs);
            }
        });

    },
    showDescuento: function(e) {

        e.stopImmediatePropagation();
        console.log('== showDescuento ==');

        var action = $(e.target).attr('rel');

        if (action === "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Dscuento', id);

            App.request({
                data: {
                    action: 'getDescuento',
                    descuento_id: id
                },
                success: function(response) {
                    console.log(response);

                    var descuento = response.descuento;

                    $('#btn_guardar_descuento').attr('rel', descuento.id);

                    $('#cantidad_minima').val(descuento.cantidadMin);
                    $('#cantidad_maxima').val(descuento.cantidadMax);
                    $('#descuento').val(descuento.descuento);
                }
            });
        }
    },
    saveDescuento: function(e) {

        e.stopImmediatePropagation();
        console.log('== saveDescuento ==');

        var error = false;
        var descuento_id = $(e.target).attr('rel');

        $("#msj_error").empty();
        $("#msj_error").addClass("hidden")

        if ($("#cantidad_minima").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese la cantidad mínima.<br/>");
            error = true;
        }
        if ($("#cantidad_maxima").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese la cantidad máxima.<br/>");
            error = true;
        }

        if ($("#descuento").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el valor del descuento.<br/>");
            error = true;
        }

        if (!error) {

            var descuento = {
                id: descuento_id,
                cantidad_min: $("#cantidad_minima").val(),
                cantidad_max: $("#cantidad_maxima").val(),
                descuento: $("#descuento").val()
            };

            var action = "";
            if (descuento_id === "" || descuento_id === undefined || descuento_id === "0") {
                action = 'saveDescuento';
            } else {
                action = 'updateDescuento';
            }

            App.request({
                data: {
                    action: action,
                    descuento: descuento
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
                        App.getDescuentos();
                    }
                }
            });

        }
    },
    inactiveDescuento: function(id) {

        console.log('== inactiveDescuento ==');

        App.request({
            data: {
                action: 'inactiveDescuento',
                descuento_id: id
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
                    App.getDescuentos();
                }
            }
        });
    },
    getPlanes: function(e) {

        if (e)
            e.stopImmediatePropagation();
        console.log('== getPlanes ==');
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
                            <button class='btn btn-outline btn-danger btn-xs' type='button' ui-sref='confirmDialog' data-id='" + obj.id + "' data-fn='inactivePlan' data-toggle='modal' data-target='#modal'>Desactivar</button>";
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

        e.stopImmediatePropagation();
        console.log('== showPlan ==');

        var action = $(e.target).attr('rel');

        if (action === "show") {
            var id = $(e.target).attr('id').split('_').pop();

            App.request({
                data: {
                    action: 'getPlan',
                    plan_id: id
                },
                success: function(response) {
                    console.log(response);

                    var plan = response.plan;

                    $('#codigo_plan').attr('disabled', 'disabled');
                    $('#btn_guardar_plan').attr('rel', plan.id)

                    $('#codigo_plan').val(plan.codigo);
                    $('#nombre_plan').val(plan.nombre);
                    $('#precio_plan').val(plan.precio);
                }
            });
        }
    },
    savePlan: function(e) {

        e.stopImmediatePropagation();
        console.log('== savePlan ==');

        var error = false;
        var plan_id = $(e.target).attr('rel');

        $("#msj_error").empty();
        $("#msj_error").addClass("hidden")

        if ($("#codigo_plan").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese un código<br/>");
            error = true;
        }
        if ($("#nombre_plan").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el nombre del plan.<br/>");
            error = true;
        }

        if ($("#precio_plan").val() === '') {
            $("#msj_error").removeClass("hidden")
            $("#msj_error").append(" - Ingrese el precio del plan.<br/>");
            error = true;
        }

        if (!error) {

            var plan = {
                id: plan_id,
                codigo: $("#codigo_plan").val(),
                nombre: $("#nombre_plan").val(),
                precio: $("#precio_plan").val().replace(',', '')
            };

            var action = "";
            if (plan_id === "" || plan_id === undefined || plan_id === "0") {
                action = 'savePlan';
            } else {
                action = 'updatePlan';
            }

            App.request({
                data: {
                    action: action,
                    plan: plan
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
                        App.getPlanes();
                    }
                }
            });

        }

    },
    inactivePlan: function(id) {

        console.log('== inactivePlan ==');

        App.request({
            data: {
                action: 'inactivePlan',
                plan_id: id
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
                    App.getPlanes();
                }
            }
        });
    },
    getClientes: function(e) {

        if (e)
            e.stopImmediatePropagation();

        console.log(' == getClientes == ');

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

        e.stopImmediatePropagation();

        console.log('== showCliente == ');

        var action = $(e.target).attr('rel');
        if (action == "show") {
            var id = $(e.target).attr('id').split('_').pop();
            console.log('Mostrar Cliente', id);
        } else {
            console.log('Agregar Cliente');
        }
    },
    saveCliente: function(e) {
        e.stopImmediatePropagation();
        console.log('== saveCliente ==');
    },
    inactiveCliente: function(e) {
        e.stopImmediatePropagation();
        console.log('== inactiveCliente ==');
    },
    exportarImportarClientes: function(e) {

        e.stopImmediatePropagation();

        console.log(' == exportarImportarClientes == ');
    },
    reporteCotizaciones: function(e) {

        e.stopImmediatePropagation();
        console.log('== reporteCotizaciones ==');

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

        // Consultar Vendedores
        App.request({
            data: {
                action: 'getUsuarios'
            },
            success: function(response) {
                console.log('usuarios', response.usuarios);
                $.each(response.usuarios, function(i, usuario) {
                    console.log(usuario);
                    $('#vendedores').append('<option value="' + usuario.id + '">' + usuario.nombres + ' ' + usuario.apellidos + ' ( ' + usuario.codigo + ' )' + '</option>');
                });
            }
        });


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


        console.log('== totalesReporte ==');

        var api = this.api(), data;
        var total_unidades = 0;
        var total_recurrente = 0;
        var total_equipos = 0;
        var total = 0;

        data = api.column(5, {page: 'current'}).data();
        //data = api.column(5).data();
        total_unidades = data.length ? data.reduce(function(a, b) {
            return parseInt(a) + parseInt(b);
        }) : 0;


        data = api.column(6, {page: 'current'}).data();
        //data = api.column(6).data();
        total_recurrente = data.length ? data.reduce(function(a, b) {
            return parseFloat(a) + parseFloat(b);
        }) : 0;
        total_recurrente = Number(parseFloat(total_recurrente).toFixed(1)).toLocaleString();


        data = api.column(8, {page: 'current'}).data();
        //data = api.column(8).data();
        total_equipos = data.length ? data.reduce(function(a, b) {
            return parseFloat(a) + parseFloat(b);
        }) : 0;
        total_equipos = Number(parseFloat(total_equipos).toFixed(1)).toLocaleString();

        data = api.column(10, {page: 'current'}).data();
        //data = api.column(10).data();
        total = data.length ? data.reduce(function(a, b) {
            return parseFloat(a) + parseFloat(b);
        }) : 0;
        total = Number(parseFloat(total).toFixed(1)).toLocaleString();

        $(api.column(5).footer()).html(total_unidades);
        $(api.column(7).footer()).html('$' + total_recurrente);
        $(api.column(9).footer()).html('$' + total_equipos);
        $(api.column(11).footer()).html('$' + total);

    },
    verPdf: function(e) {

        e.stopImmediatePropagation();
        console.log('== verPdf ==');

        var id = $(e.target).attr('id').split('_').pop();
        id = window.btoa(id);
        window.open("/sections/cotizador/showPdf.php?cotizacion=" + id, '_blank');
    },
    filtraReporteCotizaciones: function(e) {

        e.stopImmediatePropagation();
        console.log('== filtraReporteCotizaciones ==');


        var vendedor_id = $('#vendedores').val();
        var fecha_inicial = $('#fecha_inicial').val();
        var fecha_final = $('#fecha_final').val();

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
                action: 'filtrarCotizaciones',
                vendedor_id: vendedor_id,
                fecha_inicial: fecha_inicial,
                fecha_final: fecha_final
            },
            success: function(response) {
                console.log(response.cotizaciones);
                App.generateTable('cotizaciones', response.cotizaciones, columns, columnDefs, App.totalesReporte);
            }
        });
    },
    getFiles: function(e) {

        e.stopImmediatePropagation();
        console.log('== getFiles ==');

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

        console.log('== request ==');

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

                //$('#loading').modal('hide');
            },
            error: function(e) {
                console.log('error', e);
                $('#loading').modal('hide');
            }
        });
    },
    generateTable: function(table, data, columns, columnDefs, footerCallback) {
        console.log('== generateTable ==');
        var paging = true;
        if (table === 'cotizaciones') {
            paging = false;
        }

        $('#' + table).dataTable({
            "destroy": true,
            "paging": paging,
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

};