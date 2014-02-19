$(document).on('pageinit', function()
{
  $(".point").bind("tap", eventoPuntosTap);

  // Evento cuando selecciona una unidad GPS     
  $("input[name='gps']").on("change", function(event) {
    $("#accesorio-7").addClass("seleccionado");
    $("#checkbox-accesorio-7").prop('checked', true).checkboxradio('refresh');
    $("#modal-accesorio-7").popup("close");

    deshabilitarAccesoriosIncompatibles();
  });

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

  // Se corre este evento antes de que el div de previsualización se muestre.
  $("#prev-cotizacion").on("pagebeforeshow", function(event) {
    // TEMPORAL Mientras Hernán desarrolla el método para evitar el Ghost Click.
    var lastclickpoint = $(this).attr('data-clickpoint');
    var curclickpoint = event.clientX + 'x' + event.clientY;
    if (lastclickpoint == curclickpoint) {
      return false;
    }
    $(this).attr('data-clickpoint', curclickpoint);
    // FIN TEMPORAL
    
    //alert("Alerta");
  });
});

function abrirPanel() {
  $("#accesorios-seleccionados").panel("open");
}

function eventoPuntosTap(event) {
  
  if(isJqmGhostClick(event)){
    return false;
  }

  var punto = event.target;
  var puntoID = $(punto).attr("id");
  var $accesorioCheckbox = $("#checkbox-" + puntoID);

  // accesorio-7 -> Unidad Satelital (Dual) GPS  
  if ($(punto).attr("id") != "accesorio-7") {
    if (!$(punto).hasClass("deshabilitado")) {
      // Se cambiara el estilo cuando el punto es "tapeado(clickeado)"
      $(punto).toggleClass("seleccionado");
      // Se seleccionara automaticamente el listado que se visualiza en el panel derecho 
      if ($accesorioCheckbox.is(':checked')) {
        $accesorioCheckbox.prop('checked', false).checkboxradio('refresh');
      } else {
        $accesorioCheckbox.prop('checked', true).checkboxradio('refresh');
      }
      deshabilitarGpsIncompatibles();
    }
  }

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


function deshabilitarAccesoriosIncompatibles() {
  var gpsSeleccionado = $("input[name^='gps']:checked");
  var gpsID = $(gpsSeleccionado).prop("id").split("-")[1];
  habilitarAccesorios();

  if (accesoriosIncompatiblesGPS[gpsID]) {
    $.each(accesoriosIncompatiblesGPS[gpsID], function() {
      var accesorioID = this;
      $("#accesorio-" + accesorioID).addClass("deshabilitado");
    });
  }
}


function deshabilitarGpsIncompatibles() {
  var accesoriosSeleccionados = $("input[name$='accesorios']:checked");
  habilitarGps();

  $(accesoriosSeleccionados).each(function() {
    var accesorioID = $(this).prop("id").split("-")[2];

    if (gpsIncompatiblesAccesorio[accesorioID]) {
      $.each(gpsIncompatiblesAccesorio[accesorioID], function() {
        var gpsID = this;

        $("#gps-" + gpsID).checkboxradio({disabled: true});
        $("#gps-" + gpsID).checkboxradio('refresh');
      });
    }
  });
}