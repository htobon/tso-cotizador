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
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Apellidos"},
            {"title": "Telefono"},
            {"title": "Email"},
            {"title": "Rol"},
            {"title": "Estado"},
            {"title": "Opciones"}
        ];

        App.request({
            data: {
                action: 'getUsuarios'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.usuarios, function(i, u) {
                    var row = [u.codigo, u.nombres, u.apellidos, u.telefono, u.correo, u.rol, u.estaActivo, ''];
                    dataSet.push(row);
                });

                App.generateTable('usuarios', dataSet, columns);
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
            {"title": "Opciones"}
        ];

        App.request({
            data: {
                action: 'getAccesorios'
            },
            success: function(response) {

                var dataSet = [];
                $.each(response.accesorios, function(i, a) {
                    var row = [a.codAccesorio, a.nombre, a.precioAccesorio, a.codInstalacion, a.precioInstalacion, a.precioMensualidad,  a.descripcion, a.esta_activo, ''];
                    dataSet.push(row);
                });

                App.generateTable('accesorios', dataSet, columns);
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
            {"title": "Opciones"}
        ];

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

                App.generateTable('unidadesgps', dataSet, columns);
            }
        });

    },
    getContratos: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Opciones"}
        ];

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

                App.generateTable('contratos', dataSet, columns);
            }
        });

    },
    getPlanes: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nombre"},
            {"title": "Precio"},
            {"title": "Estado"},
            {"title": "Opciones"}
        ];

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

                App.generateTable('planes', dataSet, columns);
            }
        });

    },
    getClientes: function() {

        var columns = [
            {"title": "Codigo"},
            {"title": "Nit"},
            {"title": "Nombre"},
            {"title": "Opciones"}
        ];

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

                App.generateTable('clientes', dataSet, columns);
            }
        });
    },
    reporteCotizaciones: function() {

        var columns = [
            {"title": "Codigo Vendedor"},
            {"title": "Empresa"},
            {"title": "No. Cotización", "class": "center"},
            {"title": "Tipo Contrato"},
            {"title": "Plan"},
            {"title": "Cnt. Unidades", "class": "center"},
            {"title": "Valor Recurrente"},
            {"title": "Valor Equipos"},
            {"title": "Valor Total"}
        ];

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

                App.generateTable('cotizaciones', dataSet, columns);
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
                            var $button = $('<button/>').addClass('btn-delete-file');
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
                            var $button = $('<button/>').addClass('btn-delete-file');
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
    generateTable: function(table, data, columns) {

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
            "columns": columns
        });

    },
}