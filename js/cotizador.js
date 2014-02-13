$(document).on('pageinit', function()
{
  $(".point").on("tap", eventoPuntosTap );

  // Evento cuando selecciona una unidad GPS     
  $("input[name='gps']").on("click", function(event) {
    $("#accesorio-7").addClass("seleccionado");
    $("#checkbox-accesorio-7").prop('checked', true).checkboxradio('refresh');
    $("#modal-accesorio-7").popup("close");

    deshabilitarAccesoriosIncompatibles();
  });

  //Evento para abrir panel deslizando el dedo a la izquierda
  $("body").on("swipeleft", abrirPanel);

});

function abrirPanel() {
  $("#mypanel").panel("open");
}

function eventoPuntosTap(event){
  var punto = event.target;
  var puntoID = $(punto).attr("id");  
  var accesorioCheckbox = $("#checkbox-" + puntoID);

    // accesorio-7 -> Unidad Satelital (Dual) GPS
    if ($(punto).attr("id") != "accesorio-7") {
      // Se cambiara el estilo cuando el punto es "tapeado(clickeado)"
      $(punto).toggleClass("seleccionado");
      // Se seleccionara automaticamente el listado que se visualiza en el panel derecho 
      if (accesorioCheckbox.is(':checked'))
        accesorioCheckbox.prop('checked', false).checkboxradio('refresh');
      else
        accesorioCheckbox.prop('checked', true).checkboxradio('refresh');
    }
}


function habilitarAccesorios() {
  $("[id^='accesorio']").removeClass("deshabilitado");
}


function habilitarGps() {
  $("#checkbox-accesorio-7").removeClass("deshabilitado");
}


function deshabilitarAccesoriosIncompatibles() {
  var gpsSeleccionado = $("input[name^='gps']:checked");
  var gpsID = $(gpsSeleccionado).prop("id").split("-")[1];
  habilitarAccesorios();

  $.each(accesoriosIncompatibles[gpsID], function() {
    var accesorioID = this;
    $("#accesorio-" + accesorioID).addClass("deshabilitado");
  });
}


function deshabilitarGpsIncompatibles() {
  var accesoriosSeleccionados = $("input[name$='accesorios']:checked");
  habilitarGps();

  $(accesoriosSeleccionados).each(function() {
    var accesorioID = $(this).prop("id").split("-")[2];

    $.each(gpsIncompatibles[accesorioID], function() {
      var gpsID = this;
    });
  });
}