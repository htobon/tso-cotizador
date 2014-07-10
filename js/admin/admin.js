$(document).ready(function() {
    App.init();
});

var App = {
    init: function() {
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
    getUsuarios: function() {

        var columns = [
            {"title": "Codigo", data : 'codigo'},
            {"title": "Nombre", data : 'nombres'},
            {"title": "Apellidos", data : 'apellidos'},
            {"title": "Telefono", data : 'telefono'},
            {"title": "Email", data : 'correo'},
            {"title": "Rol", data : 'rol'},
            {"title": "Opciones", class : 'text-center'}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {                    
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' >Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' >Desactivar</button>";
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
    getAccesorios: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Precio"},
            {"title": "Cod. Instalacion"},
            {"title": "Precio Instalacion"},
            {"title": "Mensualidad"},
            {"title": "Descripcion"},
            {"title": "Estado"},
            {"title": "Opciones", class : 'text-center'}
        ];
        
        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {                    
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' >Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' >Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getAccesorios'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.accesorios, function(i, a) {
                    var row = [a.codAccesorio, a.nombre, a.precioAccesorio, a.codInstalacion, a.precioInstalacion, a.precioMensualidad, a.descripcion, a.esta_activo, ''];
                    dataSet.push(row);
                });

                App.generateTable('accesorios', dataSet, columns, columnDefs);
            }
        });

    },
    getUnidadesGPS: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Precio"},
            {"title": "Cod. Instalacion"},
            {"title": "Precio Instalacion"},
            {"title": "Descripcion"},
            {"title": "Estado"},
            {"title": "Opciones", class : 'text-center'}
        ];
        
        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {                    
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' >Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' >Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getUnidadesGPS'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.unidades, function(i, u) {
                    var row = [u.codUnidad, u.nombre, u.precioUnidad, u.codInstalacion, u.precioInstalacion, u.descripcion, u.esta_activo, ''];
                    dataSet.push(row);
                });

                App.generateTable('unidadesgps', dataSet, columns, columnDefs);
            }
        });

    },
    getContratos: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Opciones", class : 'text-center'}
        ];
        
        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {                    
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' >Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' >Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getTiposContratos'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.tiposContratos, function(i, c) {
                    var row = [c.id, c.nombre, ''];
                    dataSet.push(row);
                });

                App.generateTable('contratos', dataSet, columns, columnDefs);
            }
        });

    },
    getPlanes: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Precio"},
            {"title": "Estado"},
            {"title": "Opciones", class : 'text-center'}
        ];
        
         var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {                    
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' >Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' >Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getPlanes'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.planes, function(i, p) {
                    var row = [p.codigo, p.nombre, p.precio, p.estaActivo, ''];
                    dataSet.push(row);
                });

                App.generateTable('planes', dataSet, columns, columnDefs);
            }
        });

    },
    getClientes: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nit"},
            {"title": "Nombre"},
            {"title": "Opciones", class:'text-center'}
        ];
        
        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {                    
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' >Modificar</button>\n\
                            <button class='btn btn-outline btn-danger btn-xs' type='button' >Desactivar</button>";
                }
            }];

        App.request({
            data: {
                action: 'getClientes'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.clientes, function(i, c) {
                    var row = [c.id, c.nit, c.nombre, ''];
                    dataSet.push(row);
                });

                App.generateTable('clientes', dataSet, columns, columnDefs);
            }
        });
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
            {"title": "Valor Equipos", data: 'valor_equipos'},
            {"title": "Valor Total", data: 'valor_total'},
            {"title": "Ver PDF"}
        ];

        var columnDefs = [{
                "targets": -1,
                "data": "",
                "render": function(data, type, full, meta) {
                    //var id = window.btoa(full.id);                    
                    //return '<a href="/sections/cotizador/showPdf.php?cotizacion='+id+'" target="_blank">ver PDF</a>';
                    return "<button class='btn btn-outline btn-primary btn-xs' type='button' id='btn_" + full.id + "'>ver PDF</button>";
                }
            }];



        App.request({
            data: {
                action: 'reporteCotizaciones'
            },
            success: function(response) {

                var dataSet = [];

                $.each(response.cotizaciones, function(i, c) {
                    var row = [c.codigo_vendedor,
                        c.cliente, "No. " + c.id,
                        c.tipo_contrato,
                        c.nombre_plan,
                        c.cantidad_vehiculos,
                        c.valor_recurrencia,
                        c.valor_equipos,
                        c.valor_total
                    ];
                    dataSet.push(row);
                });

                var callback = function() {

                    /*$('<button/>').addClass('btn btn-outline btn-default').prependTo($('#cotizaciones_length')).html('Agregar registro');
                     
                     $('#cotizaciones_length label').addClass('col-sm-offset-3');*/

                    $('#cotizaciones').delegate('button', 'click', function(e) {
                        var id = $(e.target).attr('id').split('_').pop();
                        id = window.btoa(id);
                        window.open("/sections/cotizador/showPdf.php?cotizacion=" + id, '_blank');

                    });
                };

                App.generateTable('cotizaciones', response.cotizaciones, columns, columnDefs, callback);
            }
        });
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
                            var $button = $('<button/>').addClass('btn btn-outline btn-primary btn-xs').html('Decargar');;
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
            },
            complete: function() {
                if (typeof json.complete === 'function')
                    json.complete();
            },
            error: function(e) {
                console.log('error', e);
            }
        });
    },
    generateTable: function(table, data, columns, columnDefs, callback) {

        $('#' + table).dataTable({
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
            "columnDefs": columnDefs
        });

        if (typeof callback === 'function') {
            callback();
        }



    },
}