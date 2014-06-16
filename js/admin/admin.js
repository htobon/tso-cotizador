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
                App.initializationTables();
            }

        });

    },
    initializationTables: function() {
        // Tabla Cotizaciones Generadas
        $('#cotizaciones').dataTable({
            "paging": false,
            "ordering": false,
            "info": false
        });

        // Tabla Cotizaciones Generadas
        $('#clientes').dataTable({
            "paging": false,
            "ordering": false,
            "info": false
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
    }
}