$(document).on('pageinit', function()
{
  $(".point").bind("tap", eventoPuntosTap);

  // Evento cuando selecciona una unidad GPS     
  $("input[name='gps']").on("click", function(event) {
    $("#accesorio-7").addClass("seleccionado");
    $("#checkbox-accesorio-7").prop('checked', true).checkboxradio('refresh');
    $("#modal-accesorio-7").popup("close");
    deshabilitarAccesoriosIncompatibleConGPS();

    if (isJqmGhostClick(event)) {
      return false;
    }

    // Actualizando la lista de Cantidad Accesorios en el formulario de #adicionales.    
    var $point = $(event.target);
    var $labelGPS = $("#seleccion-accesorios #modal-accesorio-7").find("label[for='gps-" + $point.val() + "']");
    var $labelCantidadGPS = $("#tabla-cantidad-accesorios #unidad-gps").find("label");
    $labelCantidadGPS.text($labelGPS.text());

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

  /*
   * ********************** PRE-VISUALIZACIÓN DE COTIZACIÓN ************************
   */

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
    }
    ////// Accesorios e Instalaciones:
    var accesoriosIds = $(".point.seleccionado", "#seleccion-accesorios");
    accesoriosIds.each(function() {
      $("#prev-cotizacion #" + $(this).attr("id")).show();
      $("#prev-cotizacion #instalacion-" + $(this).attr("id")).show();
    });

    ////// Plan de Servicio:
    var planServicio = $("#plan", "#adicionales").val();
    if (planServicio !== -1) {
      $("#prev-cotizacion #plan-" + planServicio).show();
    }

    ////// Tipo Contrato
    var tipoContrato = $("#contrato", "#adicionales").val();
    if (tipoContrato !== -1) {
      $("#prev-cotizacion #contrato-" + tipoContrato).show();
    }

    // Cantidad vehiculos
    var cantidadVehiculos = $("#cantidad-vehiculos", "#adicionales").val();
    if (cantidadVehiculos !== undefined && $.isNumeric(cantidadVehiculos)) {
      $("#numero-vehiculos .item", "#prev-cotizacion").show();
      $("#numero-vehiculos .item", "#prev-cotizacion").text(cantidadVehiculos);
    }

    /*
     * TODO -  
     * 1. Resetear todos los items para que se oculten.
     * 2. Mostrar la información de aquellos items que se seleccionaron en la cotización.
     * 3. Calcular los valores.
     *      
     */

  });
});

function abrirPanel() {
  $("#accesorios-seleccionados").panel("open");
}

function eventoPuntosTap(event) {

  var punto = event.target;
  var puntoID = $(punto).attr("id"); // retorna accesorio-##
  var $accesorioCheckbox = $("#checkbox-" + puntoID);

  // accesorio-7 -> Unidad Satelital (Dual) GPS  
  if ($(punto).attr("id") !== "accesorio-7") {

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

      deshabilitarGpsIncompatiblesConAccesorios();
      deshabilitarPlanesIncompatiblesAccesorios();
    }
  }

}

/* ---------------------------------------------
 Validacion de restricciones
 -------------------------------------------------  */

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
  var gpsID = $(gpsSeleccionado).prop("id").split("-")[1];
  habilitarAccesorios();

  if (accesoriosIncompatiblesGPS[gpsID]) {
    $.each(accesoriosIncompatiblesGPS[gpsID], function() {
      deshabilitarAccesorio(this);
    });
  }
}

function deshabilitarPlanesIncompatiblesAccesorios() {
  var accesoriosSeleccionados = $("input[name$='accesorios']:checked");
  habilitarPlanesServicio();

  $(accesoriosSeleccionados).each(function() {
    var accesorioID = $(this).attr("id").split("-")[2];

    if (planesIncompatiblesAccesorio[accesorioID]) {
      $.each(planesIncompatiblesAccesorio[accesorioID], function() {
        console.log(this);
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