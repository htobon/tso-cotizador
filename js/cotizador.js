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

  /*
   * ********************** PRE-VISUALIZACIÓN DE COTIZACIÓN ************************
   */
  var total;
  var totalEnMeses;
  var totalPlanServicio;
  var porcentajeDescuento;
  var valorDescuento;
  var totalAccesorios;
  // Se corre este evento antes de que la sección de previsualización de la cotización se muestre.

  $("#prev-cotizacion").on("pagebeforeshow", function(event) {
    // Evitando Ghost Click
    if (isJqmGhostClick(event)) {
      return false;
    }

    // 1. Resetear todos los items para que se oculten.
    $("#prev-cotizacion .item").hide();
    total = 0;
    totalAccesorios = 0;
    totalPlanServicio = 0;
    totalEnMeses = 0;
    valorDescuento = 0;


    // 2. Mostrar la información de aquellos items que se seleccionaron en la cotización.

    ////// Gps:
    var gpsId = $("input[name=gps]:checked", "#seleccion-accesorios").val();
    if (gpsId !== undefined) {
      $("#prev-cotizacion #gps-" + gpsId).show();
      // Cantidad.
      var cantidad = $("#tabla-cantidad-accesorios #cantidad-unidad-gps").val();
      $("#prev-cotizacion #gps-" + gpsId).find(".cantidad").html(cantidad);

      // arregloGpsJSON es una variable en JSON que proviene desde el TPL.
      // Encontrando al GPS y cambiando el label de nombre.
      for (var i = 0; i < arregloGpsJSON.length; i++) {
        if (arregloGpsJSON[i].id === gpsId) {
          var numero = arregloGpsJSON[i].precioUnidad * cantidad;
          // Sumando el valor del GPS.
          total += numero;
          totalAccesorios += numero;
          numero = Number(numero.toFixed(1)).toLocaleString(); // Formateando a moneda.
          $("#prev-cotizacion #gps-" + gpsId).find(".precio").html("$" + numero);
          i = arregloGpsJSON.length;
        }
      }

    }
    ////// Accesorios e Instalaciones:
    var accesoriosIds = $(".point.seleccionado", "#seleccion-accesorios");

    accesoriosIds.each(function() {
      // id = accesorio-##
      // Accesorio id unidad-gps es el GPS y no se necesita aquí.     
      if ($(this).attr("id") !== "unidad-gps") {
        // Mostrando el item de accesorio.
        $("#prev-cotizacion #" + $(this).attr("id")).show();
        // Actualizando cantidad.
        var cantidadAccesorio = $("#tabla-cantidad-accesorios #" + $(this).attr("id")).find("input").val();
        var valorAccesorio = 0;
        var valorInstalacionAccesorio = 0;
        // accesoriosJSON es una variable en JSON que proviene desde el TPL.
        // Encontrando el accesorio y calculando el valor por cantidad.
        for (var i = 0; i < accesoriosJSON.length; i++) {
          if ("accesorio-" + accesoriosJSON[i].id === $(this).attr("id")) {
            valorAccesorio = accesoriosJSON[i].precioAccesorio * cantidadAccesorio;
            valorInstalacionAccesorio = Number(accesoriosJSON[i].precioInstalacion) * cantidadAccesorio;
            i = accesoriosJSON.length;
          }
        }
        // Actualizando cantidad y valor del accesorio.
        $("#prev-cotizacion #" + $(this).attr("id")).find(".cantidad").html(cantidadAccesorio);
        var numero = Number(valorAccesorio.toFixed(1)).toLocaleString();
        $("#prev-cotizacion #" + $(this).attr("id")).find(".precio").html("$" + numero);
        // Actualizando cantidad y valor de la instalación del accesorio.
        $("#prev-cotizacion #instalacion-" + $(this).attr("id")).find(".cantidad").html(cantidadAccesorio);
        numero = Number(valorInstalacionAccesorio.toFixed(1)).toLocaleString();
        $("#prev-cotizacion #instalacion-" + $(this).attr("id")).find(".precio").html("$" + numero);

        // Sumando el valor de los accesorios y su correspondiente instalación.
        total += valorAccesorio;
        total += valorInstalacionAccesorio;
        totalAccesorios += valorAccesorio;
        totalAccesorios += valorInstalacionAccesorio;

        // Mostrando el item de instalación de accesorio.
        $("#prev-cotizacion #instalacion-" + $(this).attr("id")).show();
      }
    });

    ////// Plan de Servicio:
    var planServicioId = $("#plan", "#adicionales").val();
    if (planServicioId !== -1) {
      // Mostrando el plan de servicio.
      $("#prev-cotizacion #plan-" + planServicioId).show();
      // Buscando el precio del plan para guardarlo en variable totalPlanServicios
      // para usarlo posteriormente.
      for (var i = 0; i < planesJSON.length; i++) {
        if (planesJSON[i].id === planServicioId) {
          totalPlanServicio = Number(planesJSON[i].precio);
          i = planesJSON.length;
        }
      }
    }

    ////// Tipo Contrato
    var tipoContrato = $("#contrato", "#adicionales").val();
    if (tipoContrato !== -1) {
      $("#prev-cotizacion #contrato-" + tipoContrato).show();
      if (tipoContrato === "1") {
        // Mostrar la duración del contrato.
        $("#prev-cotizacion #duracion").show();
        // Si el tipo de contrato es comodato.
        // Averiguar la cantidad de meses (#duraciones).
        var mesesId = $("#duracion", "#adicionales").val();
        // mostrando la cantidad respectiva.
        $("#prev-cotizacion #duracion-" + mesesId).show();
        var meses = 0;
        for (var i = 0; i < duracionesJSON.length; i++) {
          if (duracionesJSON[i].id === mesesId) {
            meses = Number(duracionesJSON[i].cantidadMeses);
            i = duracionesJSON.length;
          }
        }
        totalEnMeses = ((totalAccesorios / meses) + totalPlanServicio);
        var numero = Number(totalEnMeses.toFixed(1)).toLocaleString();
        $("#prev-cotizacion #duracion-" + mesesId).find(".precio").html("$" + numero);
      } else {
        // Si el tipo de contato es compra.
        // 
        // Ocultar Duración del contrato porque se paga todo de una.
        $("#prev-cotizacion #duracion").hide();
      }
    }

    // Cantidad vehiculos. La cantidad de vehículos está dada por la
    // cantidad de unidades GPS.
    var cantidadVehiculos = Number($("#tabla-cantidad-accesorios #unidad-gps", "#adicionales").find("#cantidad-unidad-gps").val());
    if (cantidadVehiculos !== undefined && $.isNumeric(cantidadVehiculos)) {
      $("#numero-vehiculos .item", "#prev-cotizacion").show();
      $("#numero-vehiculos .item", "#prev-cotizacion").html(cantidadVehiculos);
    }

    // Descuento: El descuento depende de la variable anterior Cantidad de Vehiculos.
    porcentajeDescuento = 0;
    for (var i = 0; i < descuentosJSON.length; i++) {
      if (cantidadVehiculos >= descuentosJSON[i].cantidadMin &&
              cantidadVehiculos <= descuentosJSON[i].cantidadMax) {
        $("#porcentaje-descuento .item", "#prev-cotizacion").show();
        porcentajeDescuento = Number(descuentosJSON[i].descuento);
        var numero = Number(porcentajeDescuento.toFixed(1)).toLocaleString();
        $("#porcentaje-descuento .item", "#prev-cotizacion").html(numero + "%");
      }
    }

    // Valor del descuento: 
    valorDescuento = Number(totalAccesorios * porcentajeDescuento / 100);
    $("#valor-descuento .item", "#prev-cotizacion").show();
    var numero = Number(valorDescuento.toFixed(1)).toLocaleString();
    $("#valor-descuento .item", "#prev-cotizacion").html("$"+numero);
    
    /*
     * TODO -  
     * 1. Resetear todos los items para que se oculten.
     * 2. Mostrar la información de aquellos items que se seleccionaron en la cotización.
     * 3. Calcular los valores.
     *      
     */

  });
  /************************ INGRESO DE DATOS DE CLIENTE *************************/
  
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
  if(gpsSeleccionado.length == 0)
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