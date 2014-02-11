$(document).on('pageinit', function()
{
    $(".point").on("tap", function(event) {
        /*
         * Aquí se valida que el accesorio ya se encuentre seleccionado.
         * De ser así simplemente se deselecciona.
         * Si por el contrario no estaba seleccionado, entonces se valida
         * que el GPS que se seleccionó le sirva a este accesorio.
         * De no ser así enotnces se le debe indicar al usuario.
         * Si por el contrario, ningún GPS se ha seleccionado, entonces el
         * accesorio podrá ser seleccionado.
         */
        var checkbox = $( "#checkbox-"+$(event.target).attr("id") );  

        // accesorio-7 -> Unidad Satelital (Dual) GPS
        if($(event.target).attr("id") != "accesorio-7"){ 
            // Se cambiara el estilo cuando el punto es "tapeado(clickeado)"
            $(event.target).toggleClass("seleccionado"); 
            // Se seleccionara automaticamente el listado que se visualiza en el panel derecho 
            if( checkbox.is(':checked'))
                checkbox.prop('checked', false).checkboxradio('refresh');
            else
                checkbox.prop('checked', true).checkboxradio('refresh');
        }
        
    });
    
    // Evento cuando selecciona una unidad GPS     
    $("input[name^='losgps']").on("click", function(event) {  
        $("#accesorio-7").addClass("seleccionado"); 
        $( "#checkbox-accesorio-7").prop('checked', true).checkboxradio('refresh');
        $( "#modal-accesorio-7" ).popup( "close" );

        deshabilitarAccesoriosIncompatibles();
    });    

    //Evento para abrir panel deslizando el dedo a la izquierda
    $("body").on( "swipeleft", swipeleftHandler ); 
    function swipeleftHandler( event ){
        $( "#mypanel" ).panel( "open" );
    }
    
});

function habilitarAccesorios(){
    $("[id^='accesorio']").removeClass("deshabilitado");
}

function habilitarGps(){
    $( "#checkbox-accesorio-7").removeClass("deshabilitado");
}

function deshabilitarAccesoriosIncompatibles(){
    var gpsSeleccionado = $("input[name^='losgps']:checked");
    var gpsID = $(gpsSeleccionado).prop("id").split("-")[1];
    habilitarAccesorios();

    $.each(accesoriosIncompatibles[gpsID], function(){
        var accesorioID = this;
        $("#accesorio-" + accesorioID).addClass("deshabilitado");
    });
}

function deshabilitarGpsIncompatibles(){
    var accesoriosSeleccionados = $("input[name$='accesorios']:checked");
    habilitarGps();

    $(accesoriosSeleccionados).each(function(){
        var accesorioID = $(this).prop("id").split("-")[2];

        $.each(gpsIncompatibles[accesorioID], function(){
            var gpsID = this;
        });
    });
}