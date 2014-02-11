{include file='head.tpl' jsIncludes=["jquery-mobile"] pageTitle="TSO Cotizador"}
<script>
  var gpsIncompatibles = {$gpsIncompatibles|@json_encode};
  var accesoriosIncompatibles = {$accesoriosIncompatibles|@json_encode};
</script>
<script type="text/javascript" src="{$smarty.const.SMARTY_ROOT_URI}/js/cotizador.js"></script>

<!-- Selección de Accesorios en el Camión -->
<div id="seleccion-accesorios" data-role="page">
  {include file='header.tpl'}
  <div class="row">
    {include file='sections/camion.tpl'}
  </div> 
  <a href="#mypanel" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-left ui-icon-bars">Accesorios</a>
  <a href="#seleccion-adicionales" data-transition="flip" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-left ui-icon-bars">Datos Adicionales</a>

  <div id='modal-accesorio-7' class="modal-accesorio" data-role="popup">
    <form>
      <div class="row">
        <fieldset data-role="controlgroup"> 
          <legend>Seleccione una unidad GPS:</legend>
          {foreach from=$arregloGps item=gps}
            <input type="radio" name="losgps" value="{$gps->id}" id="gps-{$gps->id}">
            <label for="gps-{$gps->id}">{$gps->nombre} ({$gps->precioUnidad})</label>
          {/foreach}
        </fieldset>
      </div>
    </form>
  </div>

  <div id="mypanel" data-role="panel" data-position="right" data-display="overlay">
    <div id="accesorios-contenido" class="ui-body-a ui-body ui-corner-all">
      <fieldset data-role="controlgroup">
        <legend>Accesorios Seleccionados:</legend>
        {foreach from=$accesorios item=accesorio}
          <input type="checkbox" name="checkbox-accesorios" id="checkbox-accesorio-{$accesorio->id}" disabled="disabled">
          <label for="checkbox-accesorio-{$accesorio->id}">{$accesorio->nombre}</label>  
        {/foreach}
      </fieldset>
    </div>
    <a href="#" class="ui-btn ui-icon-delete ui-btn-icon-left" data-rel="close">Cerrar</a>
  </div><!-- /panel -->
  {include file='footer.tpl'}
</div>

<!-- Selección de datos adicionales (Planes, tipo de contrato, etc.). -->
<div id="seleccion-adicionales" data-role="page">
  {include file='header.tpl'}
  <div class="row">
    <br>
    <br>
    <h1>Datos Adicionales</h1>
    <p>
      Para este paso la interfaz gráfica mostrará dos tablas. La primera contendrá los diferentes planes que ofrece la empresa, opcionalmente se puede agregar una descripción detallada del plan que se despliega al hacer clic en el ícono correspondiente.
      La segunda tabla que muestra la interfaz permite seleccionar el tipo de contrato (Compra o Comodato). Finalmente se selecciona la cantidad de vehículos y se presiona el botón siguiente para conocer la cotización.
    </p>  
  </div>
  {include file='footer.tpl'}
</div>
